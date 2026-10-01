<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_credentials(): void
    {
        Otp::create([
            'email' => 'jane@example.com',
            'otp' => '123456',
            'expires_at' => now()->addMinutes(5),
            'last_sent_at' => now(),
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'otp' => '123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('user.name', 'Jane Doe')
            ->assertJsonPath('user.email', 'jane@example.com')
            ->assertJsonStructure(['user', 'token']);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Doe',
        ]);
        $this->assertDatabaseMissing('otps', ['email' => 'jane@example.com']);
    }

    public function test_registration_requires_a_valid_otp(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('otp');

        $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'otp' => '123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertUnprocessable()
            ->assertJsonPath('message', 'Invalid OTP');

        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_registration_rejects_an_expired_otp(): void
    {
        Otp::create([
            'email' => 'jane@example.com',
            'otp' => '123456',
            'expires_at' => now()->subMinute(),
            'last_sent_at' => now()->subMinutes(6),
        ]);

        $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'otp' => '123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertUnprocessable()
            ->assertJsonPath('message', 'OTP has expired');

        $this->assertDatabaseMissing('otps', ['email' => 'jane@example.com']);
        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_registration_otp_can_be_requested(): void
    {
        Mail::fake();

        $this->postJson('/api/auth/send-register-otp', [
            'email' => 'jane@example.com',
        ])->assertOk()
            ->assertJsonPath('retry_after', 60);

        $this->assertDatabaseHas('otps', ['email' => 'jane@example.com']);
        Mail::assertSent(OtpMail::class, fn (OtpMail $mail) => $mail->hasTo('jane@example.com'));
    }

    public function test_user_can_view_and_update_profile_information(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);
        $otherUser = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($user)
            ->getJson('/api/auth/profile')
            ->assertOk()
            ->assertJsonPath('name', 'Jane Doe')
            ->assertJsonPath('email', 'jane@example.com');

        $this->patchJson('/api/auth/profile', ['name' => 'Jane Smith'])
            ->assertOk()
            ->assertJsonPath('name', 'Jane Smith')
            ->assertJsonPath('email', 'jane@example.com');

        $this->patchJson('/api/auth/profile', ['email' => 'jane.smith@example.com'])
            ->assertOk()
            ->assertJsonPath('name', 'Jane Smith')
            ->assertJsonPath('email', 'jane.smith@example.com');

        $this->patchJson('/api/auth/profile', ['email' => $otherUser->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);

        $this->actingAs($user)
            ->postJson('/api/auth/change-password', [
                'current_password' => 'old-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertOk();

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}

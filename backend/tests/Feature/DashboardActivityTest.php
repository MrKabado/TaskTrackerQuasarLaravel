<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_returns_zero_filled_daily_counts_scoped_to_the_user(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-09 12:00:00'));
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $createdTask = $user->tasks()->create([
            'title' => 'Created task',
            'status' => 'pending',
            'due_date' => '2026-11-07',
        ]);
        $createdTask->forceFill([
            'created_at' => '2026-11-08 10:00:00',
            'updated_at' => '2026-11-08 10:00:00',
        ])->save();
        $completedLateTask = $user->tasks()->create([
            'title' => 'Completed late',
            'status' => 'completed',
            'due_date' => '2026-11-07',
        ]);
        $completedLateTask->forceFill([
            'completed_at' => '2026-11-09 09:00:00',
            'created_at' => '2026-11-06 10:00:00',
            'updated_at' => '2026-11-09 09:00:00',
        ])->save();
        $completedOnTimeTask = $user->tasks()->create([
            'title' => 'Completed on time',
            'status' => 'completed',
            'due_date' => '2026-11-08',
        ]);
        $completedOnTimeTask->forceFill([
            'completed_at' => '2026-11-08 09:00:00',
            'created_at' => '2026-11-07 10:00:00',
            'updated_at' => '2026-11-08 09:00:00',
        ])->save();
        $otherUsersTask = $otherUser->tasks()->create([
            'title' => 'Other user task',
            'status' => 'completed',
        ]);
        $otherUsersTask->forceFill([
            'completed_at' => '2026-11-09 09:00:00',
            'created_at' => '2026-11-09 08:00:00',
            'updated_at' => '2026-11-09 09:00:00',
        ])->save();

        $response = $this->actingAs($user)
            ->getJson('/api/dashboard/activity?range=7d')
            ->assertOk()
            ->assertJsonPath('range', '7d')
            ->assertJsonPath('interval', 'day');

        $data = $response->json();
        $this->assertCount(7, $data['labels']);
        $this->assertCount(7, $data['series']['created']);
        $this->assertCount(7, $data['series']['completed']);
        $this->assertCount(7, $data['series']['overdue']);
        $this->assertSame(1, $data['series']['created'][array_search('2026-11-08', $data['labels'], true)]);
        $this->assertSame(1, $data['series']['completed'][array_search('2026-11-08', $data['labels'], true)]);
        $this->assertSame(1, $data['series']['completed'][array_search('2026-11-09', $data['labels'], true)]);
        $this->assertSame(2, $data['series']['overdue'][array_search('2026-11-07', $data['labels'], true)]);
        $this->assertSame(0, $data['series']['created'][array_search('2026-11-05', $data['labels'], true)]);
    }

    public function test_activity_uses_weekly_and_monthly_buckets_for_longer_ranges(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/dashboard/activity?range=3m')
            ->assertOk()
            ->assertJsonPath('interval', 'week')
            ->assertJsonPath('series.created.0', 0);

        $this->getJson('/api/dashboard/activity?range=6m')
            ->assertOk()
            ->assertJsonPath('interval', 'month')
            ->assertJsonPath('labels.0', now()->startOfMonth()->subMonths(5)->toDateString());
    }

    public function test_activity_requires_authentication_and_valid_range(): void
    {
        $this->getJson('/api/dashboard/activity')->assertUnauthorized();

        $this->actingAs(User::factory()->create())
            ->getJson('/api/dashboard/activity?range=invalid')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('range');
    }
}

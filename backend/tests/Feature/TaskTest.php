<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_and_delete_a_task(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->postJson('/api/tasks', [
            'title' => 'Write report',
            'description' => 'Quarterly summary',
            'due_date' => '2026-10-15',
        ])->assertCreated()
            ->assertJsonPath('title', 'Write report')
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('user_id', $user->id);

        $task = Task::firstOrFail();

        $this->patchJson("/api/tasks/{$task->id}", [
            'title' => 'Finish report',
            'status' => 'completed',
        ])->assertOk()
            ->assertJsonPath('title', 'Finish report')
            ->assertJsonPath('status', 'completed');

        $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_users_can_only_list_and_access_their_own_tasks(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $ownTask = $owner->tasks()->create(['title' => 'My task']);
        $otherTask = $otherUser->tasks()->create(['title' => 'Other task']);

        $this->actingAs($owner)
            ->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $ownTask->id);

        $this->getJson("/api/tasks/{$otherTask->id}")->assertNotFound();
        $this->patchJson("/api/tasks/{$otherTask->id}", ['title' => 'Hijacked'])->assertNotFound();
        $this->deleteJson("/api/tasks/{$otherTask->id}")->assertNotFound();

        $this->assertDatabaseHas('tasks', [
            'id' => $otherTask->id,
            'title' => 'Other task',
        ]);
    }

    public function test_task_endpoints_require_authentication(): void
    {
        $this->getJson('/api/tasks')->assertUnauthorized();
        $this->postJson('/api/tasks', ['title' => 'Private'])->assertUnauthorized();
    }
}
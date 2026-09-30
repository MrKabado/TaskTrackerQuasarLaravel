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

    public function test_dashboard_returns_counts_for_the_authenticated_users_tasks(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $user->tasks()->createMany([
            ['title' => 'Pending overdue', 'status' => 'pending', 'due_date' => $yesterday],
            ['title' => 'In progress today', 'status' => 'in_progress', 'due_date' => $today],
            ['title' => 'Completed overdue', 'status' => 'completed', 'due_date' => $yesterday],
            ['title' => 'Pending without due date', 'status' => 'pending'],
        ]);
        $otherUser->tasks()->create([
            'title' => 'Another users overdue task',
            'status' => 'pending',
            'due_date' => $yesterday,
        ]);

        $this->actingAs($user)
            ->getJson('/api/dashboard')
            ->assertOk()
            ->assertExactJson([
                'total_tasks' => 4,
                'pending_tasks' => 2,
                'in_progress_tasks' => 1,
                'completed_tasks' => 1,
                'overdue_tasks' => 1,
                'tasks_due_today' => 1,
            ]);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->getJson('/api/dashboard')->assertUnauthorized();
    }
}
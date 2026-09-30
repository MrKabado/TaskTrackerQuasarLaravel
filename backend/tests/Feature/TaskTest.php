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
            'category' => 'Work',
            'notes' => 'Gather the latest figures',
            'due_date' => '2026-10-15',
        ])->assertCreated()
            ->assertJsonPath('title', 'Write report')
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('priority', 'medium')
            ->assertJsonPath('category', 'Work')
            ->assertJsonPath('notes', 'Gather the latest figures')
            ->assertJsonPath('user_id', $user->id);

        $task = Task::firstOrFail();

        $this->patchJson("/api/tasks/{$task->id}", [
            'title' => 'Finish report',
            'status' => 'completed',
            'category' => 'Personal',
            'notes' => 'Waiting for final review',
        ])->assertOk()
            ->assertJsonPath('title', 'Finish report')
            ->assertJsonPath('status', 'completed')
            ->assertJsonPath('category', 'Personal')
            ->assertJsonPath('notes', 'Waiting for final review');

        $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent();
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_users_can_trash_restore_and_permanently_delete_their_tasks(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = $user->tasks()->create(['title' => 'Recoverable task']);
        $otherTask = $otherUser->tasks()->create(['title' => 'Other users task']);
        $otherTask->delete();

        $this->actingAs($user)
            ->deleteJson("/api/tasks/{$task->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
        $this->getJson('/api/tasks')->assertOk()->assertJsonCount(0);
        $this->getJson('/api/tasks/trash')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $task->id);

        $this->postJson("/api/tasks/{$otherTask->id}/restore")->assertNotFound();
        $this->deleteJson("/api/tasks/{$otherTask->id}/force-delete")->assertNotFound();

        $this->postJson("/api/tasks/{$task->id}/restore")
            ->assertOk()
            ->assertJsonPath('id', $task->id);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'deleted_at' => null]);

        $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent();
        $this->deleteJson("/api/tasks/{$task->id}/force-delete")->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_users_can_track_task_status_through_supported_options(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = $this->postJson('/api/tasks', ['title' => 'Track progress'])
            ->assertCreated()
            ->assertJsonPath('status', 'pending')
            ->json();

        foreach (['in_progress', 'completed', 'pending'] as $status) {
            $this->patchJson("/api/tasks/{$task['id']}", ['status' => $status])
                ->assertOk()
                ->assertJsonPath('status', $status);
        }

        $this->patchJson("/api/tasks/{$task['id']}", ['status' => 'blocked'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');

        $this->assertDatabaseHas('tasks', [
            'id' => $task['id'],
            'status' => 'pending',
        ]);
    }

    public function test_users_can_set_and_update_task_priority(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = $this->postJson('/api/tasks', [
            'title' => 'Prioritize work',
            'priority' => 'high',
        ])->assertCreated()
            ->assertJsonPath('priority', 'high')
            ->json();

        foreach (['medium', 'low', 'high'] as $priority) {
            $this->patchJson("/api/tasks/{$task['id']}", ['priority' => $priority])
                ->assertOk()
                ->assertJsonPath('priority', $priority);
        }

        $this->patchJson("/api/tasks/{$task['id']}", ['priority' => 'urgent'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('priority');

        $this->assertDatabaseHas('tasks', [
            'id' => $task['id'],
            'priority' => 'high',
        ]);
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

    public function test_users_can_search_tasks_by_title_and_description(): void
    {
        $user = User::factory()->create();
        $titleMatch = $user->tasks()->create([
            'title' => 'Prepare launch plan',
            'description' => 'Review the timeline',
        ]);
        $descriptionMatch = $user->tasks()->create([
            'title' => 'Follow up',
            'description' => 'Send the launch proposal',
        ]);
        $user->tasks()->create([
            'title' => 'Unrelated task',
            'description' => 'No matching content',
        ]);

        $this->actingAs($user)
            ->getJson('/api/tasks?search=launch')
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment(['id' => $titleMatch->id])
            ->assertJsonFragment(['id' => $descriptionMatch->id]);
    }

    public function test_users_can_filter_tasks_by_status_priority_and_deadline(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $dueToday = $user->tasks()->create([
            'title' => 'Due today',
            'status' => 'in_progress',
            'priority' => 'high',
            'category' => 'Work',
            'due_date' => $today,
        ]);
        $upcoming = $user->tasks()->create([
            'title' => 'Upcoming task',
            'status' => 'pending',
            'priority' => 'medium',
            'category' => 'School',
            'due_date' => $tomorrow,
        ]);
        $overdue = $user->tasks()->create([
            'title' => 'Overdue task',
            'status' => 'pending',
            'priority' => 'low',
            'due_date' => $yesterday,
        ]);
        $user->tasks()->create([
            'title' => 'Completed overdue task',
            'status' => 'completed',
            'due_date' => $yesterday,
        ]);
        $user->tasks()->create(['title' => 'No deadline']);
        $otherUser->tasks()->create([
            'title' => 'Another users upcoming task',
            'due_date' => $tomorrow,
        ]);

        $this->actingAs($user)
            ->getJson('/api/tasks?status=in_progress&priority=high&deadline=today')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $dueToday->id);

        $this->getJson('/api/tasks?deadline=upcoming')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $upcoming->id);

        $this->getJson('/api/tasks?deadline=overdue')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $overdue->id);

        $this->getJson("/api/tasks?due_date_from={$today}&due_date_to={$tomorrow}")
            ->assertOk()
            ->assertJsonCount(2);

        $this->getJson('/api/tasks?deadline=invalid')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('deadline');

        $this->getJson('/api/tasks?category=School')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $upcoming->id);
    }

    public function test_users_can_sort_tasks_by_each_supported_order(): void
    {
        $user = User::factory()->create();
        $oldest = $user->tasks()->create([
            'title' => 'Oldest task',
            'status' => 'completed',
            'priority' => 'medium',
            'due_date' => now()->subDay()->toDateString(),
        ]);
        $middle = $user->tasks()->create([
            'title' => 'Middle task',
            'status' => 'in_progress',
            'priority' => 'low',
            'due_date' => now()->toDateString(),
        ]);
        $newest = $user->tasks()->create([
            'title' => 'Newest task',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->addDay()->toDateString(),
        ]);

        $oldest->forceFill(['created_at' => now()->subDays(3)])->save();
        $middle->forceFill(['created_at' => now()->subDays(2)])->save();
        $newest->forceFill(['created_at' => now()->subDay()])->save();

        $this->actingAs($user);

        $this->getJson('/api/tasks?sort_by=newest')
            ->assertOk()
            ->assertJsonPath('0.id', $newest->id)
            ->assertJsonPath('1.id', $middle->id)
            ->assertJsonPath('2.id', $oldest->id);

        $this->getJson('/api/tasks?sort_by=oldest')
            ->assertOk()
            ->assertJsonPath('0.id', $oldest->id)
            ->assertJsonPath('1.id', $middle->id)
            ->assertJsonPath('2.id', $newest->id);

        $this->getJson('/api/tasks?sort_by=deadline')
            ->assertOk()
            ->assertJsonPath('0.id', $oldest->id)
            ->assertJsonPath('1.id', $middle->id)
            ->assertJsonPath('2.id', $newest->id);

        $this->getJson('/api/tasks?sort_by=priority')
            ->assertOk()
            ->assertJsonPath('0.id', $newest->id)
            ->assertJsonPath('1.id', $oldest->id)
            ->assertJsonPath('2.id', $middle->id);

        $this->getJson('/api/tasks?sort_by=status')
            ->assertOk()
            ->assertJsonPath('0.id', $newest->id)
            ->assertJsonPath('1.id', $middle->id)
            ->assertJsonPath('2.id', $oldest->id);

        $this->getJson('/api/tasks?sort_by=invalid')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('sort_by');
    }

    public function test_task_endpoints_require_authentication(): void
    {
        $this->getJson('/api/tasks')->assertUnauthorized();
        $this->getJson('/api/tasks/trash')->assertUnauthorized();
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
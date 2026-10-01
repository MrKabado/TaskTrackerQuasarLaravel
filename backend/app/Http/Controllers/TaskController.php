<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'priority' => ['sometimes', 'in:high,medium,low'],
            'category' => ['sometimes', 'string', 'max:255'],
            'deadline' => ['sometimes', 'in:today,upcoming,overdue,none'],
            'due_date' => ['sometimes', 'date_format:Y-m-d'],
            'due_date_from' => ['sometimes', 'date_format:Y-m-d'],
            'due_date_to' => ['sometimes', 'date_format:Y-m-d'],
            'sort_by' => ['sometimes', 'in:newest,oldest,deadline,priority,status'],
        ]);

        $tasks = $request->user()->tasks();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $tasks->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $tasks->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $tasks->where('priority', $filters['priority']);
        }

        if (isset($filters['category'])) {
            $tasks->where('category', $filters['category']);
        }

        if (isset($filters['deadline'])) {
            $today = now()->toDateString();

            match ($filters['deadline']) {
                'today' => $tasks->whereDate('due_date', $today)->where('status', '!=', 'completed'),
                'upcoming' => $tasks->whereDate('due_date', '>', $today)->where('status', '!=', 'completed'),
                'overdue' => $tasks->whereDate('due_date', '<', $today)->where('status', '!=', 'completed'),
                'none' => $tasks->whereNull('due_date'),
            };
        }

        if (isset($filters['due_date'])) {
            $tasks->whereDate('due_date', $filters['due_date']);
        }

        if (isset($filters['due_date_from'])) {
            $tasks->whereDate('due_date', '>=', $filters['due_date_from']);
        }

        if (isset($filters['due_date_to'])) {
            $tasks->whereDate('due_date', '<=', $filters['due_date_to']);
        }

        $sortBy = $filters['sort_by'] ?? (
            isset($filters['deadline']) || isset($filters['due_date']) || isset($filters['due_date_from']) || isset($filters['due_date_to'])
                ? 'deadline'
                : 'newest'
        );

        match ($sortBy) {
            'newest' => $tasks->latest(),
            'oldest' => $tasks->oldest(),
            'deadline' => $tasks->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')->orderBy('due_date')->latest(),
            'priority' => $tasks->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END")->latest(),
            'status' => $tasks->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'completed' THEN 3 ELSE 4 END")->latest(),
        };

        return response()->json($tasks->get());
    }

    public function trash(Request $request): JsonResponse
    {
        return response()->json($request->user()->tasks()->onlyTrashed()->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'priority' => ['sometimes', 'in:high,medium,low'],
            'category' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task = $request->user()->tasks()->create($validated)->refresh();

        return response()->json($task, 201);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task->id);

        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task->id);
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'priority' => ['sometimes', 'in:high,medium,low'],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ]);

        $task->update($validated);

        return response()->json($task->refresh());
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task->id);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.',
        ], 200);
    }

    public function restore(Request $request, string $taskId): JsonResponse
    {
        $task = $request->user()->tasks()->onlyTrashed()->findOrFail($taskId);
        $task->restore();

        return response()->json($task->refresh());
    }

    public function forceDestroy(Request $request, string $taskId): JsonResponse
    {
        $task = $request->user()->tasks()->onlyTrashed()->findOrFail($taskId);
        $task->forceDelete();

        return response()->json(null, 204);
    }
}
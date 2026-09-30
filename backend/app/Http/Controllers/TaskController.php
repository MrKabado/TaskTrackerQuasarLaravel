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
            'deadline' => ['sometimes', 'in:today,upcoming,overdue,none'],
            'due_date' => ['sometimes', 'date_format:Y-m-d'],
            'due_date_from' => ['sometimes', 'date_format:Y-m-d'],
            'due_date_to' => ['sometimes', 'date_format:Y-m-d'],
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

        if (isset($filters['deadline']) || isset($filters['due_date']) || isset($filters['due_date_from']) || isset($filters['due_date_to'])) {
            $tasks->orderBy('due_date')->latest();
        } else {
            $tasks->latest();
        }

        return response()->json($tasks->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'priority' => ['sometimes', 'in:high,medium,low'],
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
            'due_date' => ['sometimes', 'nullable', 'date'],
        ]);

        $task->update($validated);

        return response()->json($task->refresh());
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task->id);
        $task->delete();

        return response()->json(null, 204);
    }
}
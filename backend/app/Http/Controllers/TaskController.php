<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->tasks()->latest()->get());
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
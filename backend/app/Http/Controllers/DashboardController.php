<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $today = now()->toDateString();
        $counts = $request->user()->tasks()
            ->selectRaw('COUNT(*) as total_tasks')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_tasks', ['pending'])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as in_progress_tasks', ['in_progress'])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_tasks', ['completed'])
            ->selectRaw('SUM(CASE WHEN due_date < ? AND status != ? THEN 1 ELSE 0 END) as overdue_tasks', [$today, 'completed'])
            ->selectRaw('SUM(CASE WHEN due_date = ? AND status != ? THEN 1 ELSE 0 END) as tasks_due_today', [$today, 'completed'])
            ->toBase()
            ->first();

        return response()->json([
            'total_tasks' => (int) $counts->total_tasks,
            'pending_tasks' => (int) $counts->pending_tasks,
            'in_progress_tasks' => (int) $counts->in_progress_tasks,
            'completed_tasks' => (int) $counts->completed_tasks,
            'overdue_tasks' => (int) $counts->overdue_tasks,
            'tasks_due_today' => (int) $counts->tasks_due_today,
        ]);
    }
}
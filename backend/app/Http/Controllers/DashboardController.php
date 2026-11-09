<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function activity(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'range' => ['sometimes', 'in:7d,30d,3m,6m,year'],
        ]);
        $range = $validated['range'] ?? '7d';
        $today = CarbonImmutable::today();
        [$start, $interval] = match ($range) {
            '7d' => [$today->subDays(6), 'day'],
            '30d' => [$today->subDays(29), 'day'],
            '3m' => [$today->subMonths(3), 'week'],
            '6m' => [$today->startOfMonth()->subMonths(5), 'month'],
            'year' => [$today->startOfYear(), 'month'],
        };
        $end = $today->endOfDay();
        $buckets = $this->activityBuckets($start, $today, $interval);
        $dates = array_map(fn (CarbonImmutable $date) => $date->toDateString(), $buckets);

        $created = $this->aggregateTasksByDate(
            $request,
            'created_at',
            $interval,
            $start->startOfDay(),
            $end,
        );
        $completed = $this->aggregateTasksByDate(
            $request,
            'completed_at',
            $interval,
            $start->startOfDay(),
            $end,
            fn ($query) => $query->where('status', 'completed'),
        );
        $overdueEnd = min($today->subDay()->toDateString(), $today->toDateString());
        $overdue = $this->aggregateTasksByDate(
            $request,
            'due_date',
            $interval,
            $start->toDateString(),
            $overdueEnd,
            fn ($query) => $query->where(function ($query) {
                $query->where('status', '!=', 'completed')
                    ->orWhereRaw('DATE(completed_at) > due_date');
            }),
        );

        return response()->json([
            'range' => $range,
            'interval' => $interval,
            'labels' => $dates,
            'series' => [
                'created' => array_map(fn ($date) => (int) ($created[$date] ?? 0), $dates),
                'completed' => array_map(fn ($date) => (int) ($completed[$date] ?? 0), $dates),
                'overdue' => array_map(fn ($date) => (int) ($overdue[$date] ?? 0), $dates),
            ],
        ]);
    }

    private function activityBuckets(
        CarbonImmutable $start,
        CarbonImmutable $end,
        string $interval,
    ): array {
        $bucket = match ($interval) {
            'week' => $start->startOfWeek(CarbonImmutable::MONDAY),
            'month' => $start->startOfMonth(),
            default => $start->startOfDay(),
        };
        $buckets = [];

        while ($bucket <= $end) {
            $buckets[] = $bucket;
            $bucket = match ($interval) {
                'week' => $bucket->addWeek(),
                'month' => $bucket->addMonth(),
                default => $bucket->addDay(),
            };
        }

        return $buckets;
    }

    private function aggregateTasksByDate(
        Request $request,
        string $column,
        string $interval,
        CarbonImmutable|string $start,
        CarbonImmutable|string $end,
        ?callable $constraint = null,
    ): array {
        if (is_string($end) && $end < (string) $start) {
            return [];
        }

        $driver = DB::connection()->getDriverName();
        $bucket = match ([$interval, $driver]) {
            ['week', 'sqlite'] => "date($column, '-' || ((CAST(strftime('%w', $column) AS INTEGER) + 6) % 7) || ' days')",
            ['week', 'mysql'] => "DATE_SUB(DATE($column), INTERVAL WEEKDAY($column) DAY)",
            ['month', 'sqlite'] => "strftime('%Y-%m-01', $column)",
            ['month', 'mysql'] => "DATE_FORMAT($column, '%Y-%m-01')",
            default => "DATE($column)",
        };
        $startValue = $start instanceof CarbonImmutable ? $start->toDateTimeString() : $start;
        $endValue = $end instanceof CarbonImmutable ? $end->toDateTimeString() : $end;
        $query = $request->user()->tasks()
            ->whereNotNull($column)
            ->whereBetween($column, [$startValue, $endValue]);

        if ($constraint) {
            $constraint($query);
        }

        return $query
            ->selectRaw("$bucket as activity_date, COUNT(*) as task_count")
            ->groupBy('activity_date')
            ->pluck('task_count', 'activity_date')
            ->all();
    }
}

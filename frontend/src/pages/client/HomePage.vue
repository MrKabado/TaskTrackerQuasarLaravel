<template>
  <section class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 sm:py-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col gap-4 sm:mb-10 md:flex-row md:items-center md:justify-between">
      <div>
        <p class="text-sm font-medium text-zinc-500">Dashboard</p>

        <h1 class="heading-page mt-2 text-zinc-950">
          Welcome back.
        </h1>

        <p class="mt-2 text-sm text-zinc-500">
          Here's an overview of your productivity and task progress.
        </p>
      </div>

      <router-link
        to="/tasks"
        class="inline-flex h-11 items-center justify-center rounded-xl bg-zinc-950 px-5 text-sm font-medium text-white transition hover:bg-zinc-800"
      >
        View Tasks
      </router-link>
    </div>

    <!-- Error -->
    <div
      v-if="error"
      class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
      {{ error }}
    </div>

    <!-- Loading -->
    <div
      v-else-if="isLoading"
      class="flex min-h-[300px] items-center justify-center text-zinc-500"
    >
      Loading dashboard...
    </div>

    <template v-else-if="summary">
      <!-- Stats -->
      <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article
          v-for="card in summaryCards"
          :key="card.label"
          class="rounded-2xl border border-zinc-200 bg-white p-5 transition hover:border-zinc-300"
        >
          <div class="flex items-center justify-between">
            <span class="text-sm text-zinc-500">
              {{ card.label }}
            </span>

            <q-icon
              :name="card.icon"
              size="20px"
              class="rounded-lg p-2"
              :class="card.iconTone"
            />
          </div>

          <h2
            class="heading-metric mt-4"
            :class="card.valueTone"
          >
            {{ card.value }}
          </h2>

          <p class="mt-2 text-xs" :class="card.captionTone">
            {{ card.caption }}
          </p>
        </article>
      </div>

      <section class="mt-6 rounded-2xl border border-zinc-200 bg-white">
        <div
          class="flex flex-col gap-4 border-b border-zinc-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6"
        >
          <div>
            <h2 class="text-lg font-semibold tracking-tight text-zinc-950">
              Task Activity Overview
            </h2>
            <p class="mt-1 text-sm text-zinc-500">
              Created, completed, and overdue tasks over time.
            </p>
          </div>
          <label class="flex items-center gap-2 text-xs font-medium text-zinc-600">
            <span>Date range</span>
            <select
              v-model="activityRange"
              class="h-9 cursor-pointer rounded-lg border border-zinc-200 bg-white px-3 text-xs text-zinc-700 outline-none transition focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
              @change="loadActivity"
            >
              <option value="7d">Last 7 Days</option>
              <option value="30d">Last 30 Days</option>
              <option value="3m">Last 3 Months</option>
              <option value="6m">Last 6 Months</option>
              <option value="year">This Year</option>
            </select>
          </label>
        </div>

        <div
          v-if="isActivityLoading"
          class="grid min-h-[300px] place-items-center text-sm text-zinc-500"
          role="status"
        >
          Loading task activity…
        </div>
        <div
          v-else-if="activityError"
          class="flex min-h-[300px] flex-col items-center justify-center gap-3 px-5 text-center"
        >
          <p class="m-0 text-sm text-red-700" role="alert">{{ activityError }}</p>
          <button
            class="rounded-lg border border-zinc-200 px-3 py-2 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50"
            type="button"
            @click="loadActivity"
          >
            Try again
          </button>
        </div>
        <div
          v-else-if="isActivityEmpty"
          class="grid min-h-[300px] place-items-center px-5 text-center text-sm text-zinc-500"
        >
          No task activity for this date range yet.
        </div>
        <div v-else class="h-[300px] px-2 py-4 sm:h-[340px] sm:px-5">
          <VChart
            class="h-full w-full"
            :option="activityChartOption"
            autoresize
            aria-label="Line graph of created, completed, and overdue tasks"
          />
        </div>
      </section>
    </template>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import VChart from 'vue-echarts';
import { use } from 'echarts/core';
import { LineChart } from 'echarts/charts';
import { GridComponent, LegendComponent, TooltipComponent } from 'echarts/components';
import { CanvasRenderer } from 'echarts/renderers';
import { getApiErrorMessage } from '@/services/auth';
import {
  getDashboardSummary,
  getTaskActivity,
  type DashboardSummary,
  type TaskActivity,
  type TaskActivityRange,
} from '@/services/tasks';

use([CanvasRenderer, GridComponent, LegendComponent, LineChart, TooltipComponent]);

defineOptions({ name: 'DashboardPage' });

const summary = ref<DashboardSummary | null>(null);
const isLoading = ref(true);
const error = ref('');
const activityRange = ref<TaskActivityRange>('7d');
const activity = ref<TaskActivity | null>(null);
const isActivityLoading = ref(true);
const activityError = ref('');
let activityRequestId = 0;

const isActivityEmpty = computed(
  () =>
    activity.value !== null &&
    Object.values(activity.value.series).every((values) => values.every((value) => value === 0)),
);

const activityChartOption = computed(() => ({
  color: ['#6366f1', '#10b981', '#f43f5e'],
  aria: { enabled: true },
  tooltip: { trigger: 'axis' as const },
  legend: {
    bottom: 0,
    data: ['Created Tasks', 'Completed Tasks', 'Overdue Tasks'],
    itemGap: 8,
    textStyle: { color: '#52525b', fontFamily: 'Geist Variable, sans-serif', fontSize: 10 },
  },
  grid: { left: 36, right: 16, top: 16, bottom: 56, containLabel: true },
  xAxis: {
    type: 'category' as const,
    boundaryGap: false,
    data: activity.value?.labels ?? [],
    axisLabel: {
      color: '#71717a',
      hideOverlap: true,
      formatter: (label: string) => formatActivityLabel(label, activity.value?.interval),
    },
    axisLine: { lineStyle: { color: '#e4e4e7' } },
    axisTick: { show: false },
  },
  yAxis: {
    type: 'value' as const,
    minInterval: 1,
    axisLabel: { color: '#71717a' },
    axisLine: { show: false },
    axisTick: { show: false },
    splitLine: { lineStyle: { color: '#f4f4f5' } },
  },
  series: [
    {
      name: 'Created Tasks',
      type: 'line',
      smooth: true,
      symbol: 'circle',
      symbolSize: 6,
      lineStyle: { width: 2 },
      data: activity.value?.series.created ?? [],
    },
    {
      name: 'Completed Tasks',
      type: 'line',
      smooth: true,
      symbol: 'circle',
      symbolSize: 6,
      lineStyle: { width: 2 },
      data: activity.value?.series.completed ?? [],
    },
    {
      name: 'Overdue Tasks',
      type: 'line',
      smooth: true,
      symbol: 'circle',
      symbolSize: 6,
      lineStyle: { width: 2 },
      data: activity.value?.series.overdue ?? [],
    },
  ],
}));

const summaryCards = computed(() => {
  if (!summary.value) return [];

  return [
    {
      label: 'Total Tasks',
      value: summary.value.total_tasks,
      caption: 'All tasks in workspace',
      icon: 'checklist',
      iconTone: 'bg-zinc-100 text-zinc-600',
      valueTone: 'text-zinc-900',
      captionTone: 'text-zinc-400',
    },
    {
      label: 'In Progress',
      value: summary.value.in_progress_tasks,
      caption: 'Currently active',
      icon: 'autorenew',
      iconTone: 'bg-sky-50 text-sky-700',
      valueTone: 'text-sky-800',
      captionTone: 'text-sky-700/70',
    },
    {
      label: 'Completed',
      value: summary.value.completed_tasks,
      caption: 'Successfully finished',
      icon: 'task_alt',
      iconTone: 'bg-emerald-50 text-emerald-700',
      valueTone: 'text-emerald-800',
      captionTone: 'text-emerald-700/70',
    },
    {
      label: 'Overdue',
      value: summary.value.overdue_tasks,
      caption: 'Require attention',
      icon: 'schedule',
      iconTone: 'bg-rose-50 text-rose-700',
      valueTone: 'text-rose-800',
      captionTone: 'text-rose-700/70',
    },
  ];
});

onMounted(() => {
  void loadSummary();
  void loadActivity();
});

async function loadSummary() {
  try {
    summary.value = await getDashboardSummary();
  } catch (requestError: unknown) {
    error.value = getApiErrorMessage(requestError);
  } finally {
    isLoading.value = false;
  }
}

async function loadActivity() {
  const requestId = ++activityRequestId;
  isActivityLoading.value = true;
  activityError.value = '';

  try {
    const result = await getTaskActivity(activityRange.value);
    if (requestId === activityRequestId) activity.value = result;
  } catch (requestError: unknown) {
    if (requestId === activityRequestId) {
      activityError.value = getApiErrorMessage(requestError);
    }
  } finally {
    if (requestId === activityRequestId) isActivityLoading.value = false;
  }
}

function formatActivityLabel(label: string, interval?: TaskActivity['interval']): string {
  const [yearValue, monthValue, dayValue] = label.split('-');
  const year = Number(yearValue);
  const month = Number(monthValue);
  const day = Number(dayValue);
  if (
    !Number.isFinite(year) ||
    !Number.isFinite(month) ||
    (interval !== 'month' && !Number.isFinite(day))
  ) {
    return label;
  }
  const date = new Date(year, month - 1, interval === 'month' ? 1 : day);

  return new Intl.DateTimeFormat(undefined, {
    month: 'short',
    ...(interval === 'month' ? {} : { day: 'numeric' as const }),
  }).format(date);
}
</script>

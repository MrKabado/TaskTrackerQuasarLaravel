<template>
  <section class="mx-auto w-full max-w-7xl px-6 py-8">
    <!-- Header -->
    <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
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
              class="text-zinc-400"
            />
          </div>

          <h2
            class="heading-metric mt-4 text-zinc-950"
          >
            {{ card.value }}
          </h2>

          <p class="mt-2 text-xs text-zinc-400">
            {{ card.caption }}
          </p>
        </article>
      </div>

      <!-- Main Grid -->
      <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <!-- Overview -->
        <section
          class="xl:col-span-2 rounded-2xl border border-zinc-200 bg-white"
        >
          <div class="border-b border-zinc-100 px-6 py-5">
            <h2
              class="text-lg font-semibold tracking-tight text-zinc-950"
            >
              Task Overview
            </h2>

            <p class="mt-1 text-sm text-zinc-500">
              Track your progress and stay productive.
            </p>
          </div>

          <div class="p-6">
            <div
              class="flex flex-col gap-4 rounded-2xl border border-zinc-100 bg-zinc-50 p-5 md:flex-row md:items-center md:justify-between"
            >
              <div>
                <h3
                  class="text-base font-semibold text-zinc-950"
                >
                  {{
                    summary.total_tasks
                      ? 'Keep your momentum going'
                      : 'Ready to get started?'
                  }}
                </h3>

                <p class="mt-2 text-sm text-zinc-500">
                  {{
                    summary.total_tasks
                      ? 'Continue working on your active tasks and complete more goals today.'
                      : 'Create your first task and start organizing your workflow.'
                  }}
                </p>
              </div>

              <router-link
                to="/tasks"
                class="inline-flex h-10 items-center justify-center rounded-xl border border-zinc-200 bg-white px-4 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100"
              >
                Open Tasks
              </router-link>
            </div>
          </div>
        </section>

        <!-- Quick Insights -->
        <section
          class="rounded-2xl border border-zinc-200 bg-white"
        >
          <div class="border-b border-zinc-100 px-6 py-5">
            <h2
              class="text-lg font-semibold tracking-tight text-zinc-950"
            >
              Quick Insights
            </h2>

            <p class="mt-1 text-sm text-zinc-500">
              Summary of your workspace.
            </p>
          </div>

          <div class="space-y-5 p-6">
            <div>
              <p class="text-xs uppercase tracking-wide text-zinc-400">
                Pending Tasks
              </p>
              <p class="mt-1 text-2xl font-semibold">
                {{ summary.pending_tasks }}
              </p>
            </div>

            <div>
              <p class="text-xs uppercase tracking-wide text-zinc-400">
                Due Today
              </p>
              <p class="mt-1 text-2xl font-semibold">
                {{ summary.tasks_due_today }}
              </p>
            </div>

            <div>
              <p class="text-xs uppercase tracking-wide text-zinc-400">
                Completion Rate
              </p>

              <p class="mt-1 text-2xl font-semibold">
                {{
                  summary.total_tasks
                    ? Math.round(
                        (summary.completed_tasks /
                          summary.total_tasks) *
                          100
                      )
                    : 0
                }}%
              </p>
            </div>
          </div>
        </section>
      </div>

      <!-- Upcoming -->
      <section
        class="mt-6 rounded-2xl border border-zinc-200 bg-white"
      >
        <div class="border-b border-zinc-100 px-6 py-5">
          <h2
            class="text-lg font-semibold tracking-tight text-zinc-950"
          >
            Productivity Snapshot
          </h2>

          <p class="mt-1 text-sm text-zinc-500">
            Stay consistent and keep moving forward.
          </p>
        </div>

        <div class="grid gap-4 p-6 md:grid-cols-3">
          <div class="rounded-xl bg-zinc-50 p-4">
            <p class="text-xs text-zinc-500">Completed</p>
            <p class="mt-2 text-2xl font-semibold">
              {{ summary.completed_tasks }}
            </p>
          </div>

          <div class="rounded-xl bg-zinc-50 p-4">
            <p class="text-xs text-zinc-500">In Progress</p>
            <p class="mt-2 text-2xl font-semibold">
              {{ summary.in_progress_tasks }}
            </p>
          </div>

          <div class="rounded-xl bg-zinc-50 p-4">
            <p class="text-xs text-zinc-500">Overdue</p>
            <p class="mt-2 text-2xl font-semibold">
              {{ summary.overdue_tasks }}
            </p>
          </div>
        </div>
      </section>
    </template>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { getApiErrorMessage } from '@/services/auth';
import { getDashboardSummary, type DashboardSummary } from '@/services/tasks';

defineOptions({ name: 'DashboardPage' });

const summary = ref<DashboardSummary | null>(null);
const isLoading = ref(true);
const error = ref('');

const summaryCards = computed(() => {
  if (!summary.value) return [];

  return [
    {
      label: 'Total Tasks',
      value: summary.value.total_tasks,
      caption: 'All tasks in workspace',
      icon: 'checklist',
    },
    {
      label: 'In Progress',
      value: summary.value.in_progress_tasks,
      caption: 'Currently active',
      icon: 'autorenew',
    },
    {
      label: 'Completed',
      value: summary.value.completed_tasks,
      caption: 'Successfully finished',
      icon: 'task_alt',
    },
    {
      label: 'Overdue',
      value: summary.value.overdue_tasks,
      caption: 'Require attention',
      icon: 'schedule',
    },
  ];
});

onMounted(async () => {
  try {
    summary.value = await getDashboardSummary();
  } catch (requestError: unknown) {
    error.value = getApiErrorMessage(requestError);
  } finally {
    isLoading.value = false;
  }
});
</script>

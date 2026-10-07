<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex items-start justify-between gap-5">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Overview</p>
        <h1 class="m-0 text-3xl font-semibold tracking-tight text-zinc-900">Good to see you.</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Here’s a quick look at what’s happening with your tasks.
        </p>
      </div>
      <router-link
        to="/tasks"
        class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700"
      >
        <q-icon name="add" size="18px" />
        View tasks
      </router-link>
    </div>

    <p
      v-if="error"
      class="rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-xs text-red-700"
      role="alert"
    >
      {{ error }}
    </p>
    <div v-else-if="isLoading" class="px-5 py-16 text-center text-sm text-zinc-500" role="status">
      Loading your workspace…
    </div>

    <template v-else-if="summary">
      <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <article
          v-for="card in summaryCards"
          :key="card.label"
          class="grid min-h-[142px] content-start rounded-xl border border-zinc-200 bg-white p-4 shadow-sm shadow-zinc-900/[0.02]"
        >
          <div class="flex items-center justify-between gap-2 text-xs text-zinc-500">
            <span>{{ card.label }}</span>
            <span class="grid size-8 place-items-center rounded-lg" :class="card.tone">
              <q-icon :name="card.icon" size="18px" />
            </span>
          </div>
          <strong
            class="mt-3 text-[26px] font-semibold leading-none tracking-tight text-zinc-900"
            >{{ card.value }}</strong
          >
          <span class="mt-2 text-[11px] text-zinc-400">{{ card.caption }}</span>
        </article>
      </div>

      <section
        class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm shadow-zinc-900/[0.02]"
      >
        <div
          class="flex items-center justify-between gap-4 border-b border-zinc-100 px-4 py-5 sm:px-5"
        >
          <div>
            <h2 class="m-0 text-sm font-semibold tracking-tight">Your workspace</h2>
            <p class="mt-1 text-[11px] text-zinc-500">
              Make a little progress, one task at a time.
            </p>
          </div>
          <router-link
            to="/tasks"
            class="inline-flex items-center gap-1 text-[11px] font-medium text-zinc-600 hover:text-zinc-900"
          >
            Open task list <q-icon name="arrow_forward" size="16px" />
          </router-link>
        </div>
        <div class="flex flex-wrap items-center gap-4 p-4 sm:p-5">
          <div
            class="grid size-[42px] shrink-0 place-items-center rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-600"
          >
            <q-icon name="checklist" size="24px" />
          </div>
          <div class="min-w-0 flex-1">
            <h3 class="m-0 text-[13px] font-semibold">
              {{ summary.total_tasks ? 'Keep your momentum going' : 'Your workspace is ready' }}
            </h3>
            <p class="mt-1 text-[11px] leading-relaxed text-zinc-500">
              {{
                summary.total_tasks
                  ? 'Your tasks and progress are right here whenever you need them.'
                  : 'Create your first task to turn your plans into progress.'
              }}
            </p>
          </div>
          <router-link
            to="/tasks"
            class="ml-[58px] inline-flex min-h-9 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50 sm:ml-0"
            >Go to tasks</router-link
          >
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
  if (!summary.value) {
    return [];
  }

  return [
    {
      label: 'Total tasks',
      value: summary.value.total_tasks,
      caption: 'In your workspace',
      icon: 'checklist',
      tone: 'bg-zinc-100 text-zinc-600',
    },
    {
      label: 'In progress',
      value: summary.value.in_progress_tasks,
      caption: `${summary.value.pending_tasks} waiting to start`,
      icon: 'autorenew',
      tone: 'bg-blue-50 text-blue-700',
    },
    {
      label: 'Completed',
      value: summary.value.completed_tasks,
      caption: 'Tasks you’ve finished',
      icon: 'task_alt',
      tone: 'bg-green-50 text-green-700',
    },
    {
      label: 'Overdue',
      value: summary.value.overdue_tasks,
      caption: `${summary.value.tasks_due_today} due today`,
      icon: 'schedule',
      tone: 'bg-amber-50 text-amber-700',
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

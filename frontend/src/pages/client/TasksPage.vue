<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex items-start justify-between gap-5">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Tasks</p>
        <h1 class="heading-page m-0 text-zinc-900">Tasks</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Keep your work organized and make progress at your own pace.
        </p>
      </div>
    </div>

    <div
      class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm shadow-zinc-900/[0.02]"
    >
      <div
        class="flex items-center justify-between gap-4 border-b border-zinc-100 px-4 py-5 sm:px-5"
      >
        <div>
          <h2 class="m-0 text-sm font-semibold tracking-tight">Task list</h2>
          <p class="mt-1 text-[11px] text-zinc-500">
            {{ tasks.length }} {{ tasks.length === 1 ? 'task' : 'tasks' }}
          </p>
        </div>
        <q-icon name="tune" size="19px" class="text-zinc-500" />
      </div>

      <nav
        class="flex gap-1 overflow-x-auto border-b border-zinc-100 px-3 pt-3 sm:px-5"
        aria-label="Filter tasks"
      >
        <button
          v-for="filter in filters"
          :key="filter.label"
          type="button"
          :class="[
            'shrink-0 rounded-t-md border-b-2 px-3 pb-3 text-[11px] transition-colors hover:text-zinc-900',
            activeFilter === filter.label
              ? 'border-zinc-800 font-semibold text-zinc-900'
              : 'border-transparent text-zinc-500',
          ]"
          :aria-pressed="activeFilter === filter.label"
          @click="selectFilter(filter)"
        >
          {{ filter.label }}
        </button>
      </nav>

      <p
        v-if="error"
        class="m-4 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-xs text-red-700"
        role="alert"
      >
        {{ error }}
      </p>
      <div
        v-else-if="isLoading"
        class="grid min-h-56 place-content-center text-center text-xs text-zinc-500"
        role="status"
      >
        Loading tasks…
      </div>
      <div
        v-else-if="tasks.length === 0"
        class="grid min-h-56 content-center justify-items-center gap-2 px-5 py-8 text-center text-[11px] text-zinc-500"
      >
        <span
          class="mb-1 grid size-11 place-items-center rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-600"
          ><q-icon name="checklist" size="22px"
        /></span>
        <strong class="text-[13px] font-semibold text-zinc-800"
          >No
          {{ activeFilter === 'All Tasks' ? '' : activeFilter.toLowerCase() + ' ' }}tasks</strong
        >
        <span>
          {{
            activeFilter === 'All Tasks'
              ? 'Your tasks will appear here when they’re ready.'
              : 'Try another filter to see more of your tasks.'
          }}
        </span>
      </div>
      <ul v-else class="m-0 list-none p-0">
        <li
          v-for="task in tasks"
          :key="task.id"
          class="flex min-h-[69px] items-center gap-2.5 border-b border-zinc-100 px-3 py-3 last:border-0 sm:gap-3 sm:px-5"
        >
          <span
            class="size-[9px] shrink-0 rounded-full"
            :class="
              task.status === 'completed'
                ? 'bg-green-600'
                : task.status === 'in_progress'
                  ? 'bg-blue-500'
                  : 'bg-zinc-300'
            "
          />
          <span class="grid min-w-0 flex-1 gap-1">
            <strong class="truncate text-xs font-medium">{{ task.title }}</strong>
            <span class="truncate text-[11px] text-zinc-400">{{
              task.category || task.description || 'No additional details'
            }}</span>
          </span>
          <span
            class="hidden rounded-full px-2 py-1 text-[10px] capitalize sm:inline"
            :class="
              task.priority === 'high'
                ? 'bg-red-50 text-red-700'
                : task.priority === 'medium'
                  ? 'bg-amber-50 text-amber-700'
                  : 'bg-zinc-100 text-zinc-600'
            "
          >
            {{ task.priority }}
          </span>
          <span
            class="w-[70px] shrink-0 text-right text-[10px] text-zinc-500 sm:w-[84px] sm:text-[11px]"
            >{{ formatDueDate(task.due_date) }}</span
          >
        </li>
      </ul>
    </div>
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { getApiErrorMessage } from '@/services/auth';
import { getTasks, type Task, type TaskStatus } from '@/services/tasks';

defineOptions({ name: 'TasksPage' });

type TaskFilter = {
  label: string;
  status?: TaskStatus;
  deadline?: 'overdue';
};

const filters: TaskFilter[] = [
  { label: 'All Tasks' },
  { label: 'Pending', status: 'pending' },
  { label: 'In Progress', status: 'in_progress' },
  { label: 'Completed', status: 'completed' },
  { label: 'Overdue', deadline: 'overdue' },
];

const activeFilter = ref('All Tasks');
const tasks = ref<Task[]>([]);
const isLoading = ref(true);
const error = ref('');

onMounted(() => loadTasks());

async function loadTasks(filter?: TaskFilter) {
  isLoading.value = true;
  error.value = '';

  try {
    const params = {
      ...(filter?.status ? { status: filter.status } : {}),
      ...(filter?.deadline ? { deadline: filter.deadline } : {}),
    };
    tasks.value = await getTasks(params);
  } catch (requestError: unknown) {
    error.value = getApiErrorMessage(requestError);
  } finally {
    isLoading.value = false;
  }
}

function selectFilter(filter: TaskFilter) {
  activeFilter.value = filter.label;
  void loadTasks(filter);
}

function formatDueDate(date: string | null): string {
  if (!date) {
    return 'No due date';
  }

  return new Intl.DateTimeFormat(undefined, { month: 'short', day: 'numeric' }).format(
    new Date(`${date}T00:00:00`),
  );
}
</script>

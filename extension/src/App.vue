<script setup lang="ts">
import { computed, onMounted, reactive } from 'vue';
import { useTaskTrackerStore, type TaskStatus, type TrackerTask } from './stores/taskTracker';

const store = useTaskTrackerStore();
const loginForm = reactive({ email: '', password: '' });
const createForm = reactive({
  title: '',
  category: '',
  priority: 'medium' as 'low' | 'medium' | 'high',
  due_date: '',
});
const filters: Array<{ label: string; value: TaskStatus | 'all' }> = [
  { label: 'All', value: 'all' },
  { label: 'To do', value: 'pending' },
  { label: 'In progress', value: 'in_progress' },
  { label: 'Done', value: 'completed' },
];

const appUrl = import.meta.env.VITE_APP_URL || 'http://localhost:5173';
const visibleTasks = computed(() =>
  store.activeFilter === 'all'
    ? store.tasks
    : store.tasks.filter((task) => task.status === store.activeFilter),
);

onMounted(() => {
  void store.restoreSession();
});

async function submitLogin() {
  await store.login(loginForm.email.trim(), loginForm.password);
  if (store.user) loginForm.password = '';
}

async function submitTask() {
  const created = await store.createTask({
    title: createForm.title.trim(),
    category: createForm.category.trim() || null,
    priority: createForm.priority,
    due_date: createForm.due_date || null,
    description: null,
    notes: null,
    status: 'pending',
  });

  if (created) {
    createForm.title = '';
    createForm.category = '';
    createForm.priority = 'medium';
    createForm.due_date = '';
  }
}

function formatDueDate(date: string | null): string {
  if (!date) return 'No due date';
  return new Intl.DateTimeFormat(undefined, { month: 'short', day: 'numeric' }).format(
    new Date(`${date}T00:00:00`),
  );
}

function confirmDelete(task: TrackerTask) {
  if (window.confirm(`Delete "${task.title}"?`)) {
    void store.deleteTask(task);
  }
}
</script>

<template>
  <main class="mx-auto min-h-screen w-full max-w-[560px] bg-zinc-50 text-zinc-900">
    <div
      v-if="store.isInitializing"
      class="grid min-h-screen place-items-center text-sm text-zinc-500"
      role="status"
    >
      Connecting to Task Tracker…
    </div>

    <section v-else-if="!store.user" class="flex min-h-screen flex-col px-5 py-6 sm:px-7">
      <header class="flex items-center gap-3">
        <span class="grid size-10 place-items-center rounded-xl bg-zinc-900 text-white">
          <svg viewBox="0 0 24 24" class="size-5 fill-none stroke-current" aria-hidden="true">
            <path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
          </svg>
        </span>
        <div>
          <p class="text-sm font-semibold">Task Tracker</p>
          <p class="text-xs text-zinc-500">Your tasks, right in Chrome</p>
        </div>
      </header>

      <div class="my-auto py-10">
        <p class="text-xs font-medium text-zinc-500">Welcome back</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight">Connect your account</h1>
        <p class="mt-2 text-sm leading-relaxed text-zinc-500">
          Sign in with your Task Tracker account to view and manage your tasks.
        </p>

        <form class="mt-7 grid gap-4" @submit.prevent="submitLogin">
          <label class="grid gap-1.5 text-xs font-medium text-zinc-700" for="login-email">
            Email address
            <input
              id="login-email"
              v-model="loginForm.email"
              class="h-10 rounded-lg border border-zinc-200 bg-white px-3 text-sm font-normal outline-none transition focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
              type="email"
              autocomplete="email"
              required
              placeholder="you@example.com"
            />
          </label>
          <label class="grid gap-1.5 text-xs font-medium text-zinc-700" for="login-password">
            Password
            <input
              id="login-password"
              v-model="loginForm.password"
              class="h-10 rounded-lg border border-zinc-200 bg-white px-3 text-sm font-normal outline-none transition focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
              type="password"
              autocomplete="current-password"
              required
              placeholder="Enter your password"
            />
          </label>
          <p
            v-if="store.error"
            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-xs leading-relaxed text-red-700"
            role="alert"
          >
            {{ store.error }}
          </p>
          <button
            class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-sm font-medium text-white transition hover:bg-zinc-700 disabled:cursor-wait disabled:opacity-60"
            type="submit"
            :disabled="store.isBusy"
          >
            {{ store.isBusy ? 'Connecting…' : 'Connect account' }}
          </button>
        </form>

        <a
          class="mt-5 inline-flex text-xs font-medium text-zinc-600 underline underline-offset-4 hover:text-zinc-900"
          :href="appUrl"
          target="_blank"
          rel="noreferrer"
        >
          Create an account on Task Tracker
        </a>
      </div>
      <p class="text-center text-[11px] leading-relaxed text-zinc-400">
        Your sign-in token stays in this extension's Chrome storage.
      </p>
    </section>

    <section v-else class="min-h-screen">
      <header
        class="sticky top-0 z-10 flex items-center justify-between border-b border-zinc-200 bg-white/95 px-4 py-3 backdrop-blur sm:px-6"
      >
        <div class="flex min-w-0 items-center gap-2.5">
          <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-zinc-900 text-white">
            <svg viewBox="0 0 24 24" class="size-5 fill-none stroke-current" aria-hidden="true">
              <path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
            </svg>
          </span>
          <div class="min-w-0">
            <p class="truncate text-sm font-semibold">Task Tracker</p>
            <p class="truncate text-[11px] text-zinc-500">{{ store.user.name }}</p>
          </div>
        </div>
        <div class="flex shrink-0 items-center gap-1">
          <button
            class="grid size-9 place-items-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900 disabled:opacity-50"
            type="button"
            aria-label="Refresh tasks"
            :disabled="store.isBusy"
            @click="store.loadTasks"
          >
            <svg viewBox="0 0 24 24" class="size-[18px] fill-none stroke-current" aria-hidden="true">
              <path d="M20 7v5h-5M4 17v-5h5m-4.1-3a7 7 0 0 1 11.6-2.6L20 12M4 12l3.5 5.6A7 7 0 0 0 19.1 15" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" />
            </svg>
          </button>
          <button
            class="rounded-lg px-2.5 py-2 text-xs font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 disabled:opacity-50"
            type="button"
            :disabled="store.isBusy"
            @click="store.logout"
          >
            Log out
          </button>
        </div>
      </header>

      <div class="space-y-5 px-4 py-5 sm:px-6">
        <div class="flex items-end justify-between gap-3">
          <div>
            <p class="text-xs font-medium text-zinc-500">Your workspace</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight">Tasks</h1>
          </div>
          <span class="pb-1 text-xs text-zinc-500">{{ store.tasks.length }} total</span>
        </div>

        <p
          v-if="store.error"
          class="rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-xs leading-relaxed text-red-700"
          role="alert"
        >
          {{ store.error }}
        </p>

        <form
          class="grid gap-3 rounded-xl border border-zinc-200 bg-white p-3.5 shadow-sm shadow-zinc-900/[0.02]"
          @submit.prevent="submitTask"
        >
          <div class="flex items-center gap-2">
            <span class="grid size-7 place-items-center rounded-lg bg-zinc-100 text-zinc-600">
              <svg viewBox="0 0 24 24" class="size-4 fill-none stroke-current" aria-hidden="true">
                <path d="M12 5v14m-7-7h14" stroke-linecap="round" stroke-width="2" />
              </svg>
            </span>
            <h2 class="text-sm font-semibold">Add a task</h2>
          </div>
          <input
            v-model="createForm.title"
            class="h-10 rounded-lg border border-zinc-200 px-3 text-sm outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
            type="text"
            maxlength="255"
            required
            aria-label="Task title"
            placeholder="What needs to get done?"
          />
          <div class="grid grid-cols-2 gap-2">
            <input
              v-model="createForm.category"
              class="h-9 min-w-0 rounded-lg border border-zinc-200 px-2.5 text-xs outline-none transition placeholder:text-zinc-400 focus:border-zinc-400"
              type="text"
              maxlength="255"
              aria-label="Category"
              placeholder="Category (optional)"
            />
            <select
              v-model="createForm.priority"
              class="h-9 min-w-0 rounded-lg border border-zinc-200 bg-white px-2.5 text-xs text-zinc-700 outline-none focus:border-zinc-400"
              aria-label="Priority"
            >
              <option value="low">Low priority</option>
              <option value="medium">Medium priority</option>
              <option value="high">High priority</option>
            </select>
          </div>
          <div class="flex flex-wrap items-center justify-between gap-2">
            <label class="flex items-center gap-2 text-[11px] text-zinc-500">
              Due date
              <input
                v-model="createForm.due_date"
                class="h-8 rounded-md border border-zinc-200 px-2 text-xs text-zinc-700"
                type="date"
              />
            </label>
            <button
              class="inline-flex h-9 items-center justify-center rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-wait disabled:opacity-60"
              type="submit"
              :disabled="store.isBusy"
            >
              Create task
            </button>
          </div>
        </form>

        <div class="flex gap-1 overflow-x-auto border-b border-zinc-200" aria-label="Task filters">
          <button
            v-for="filter in filters"
            :key="filter.value"
            class="shrink-0 border-b-2 px-3 py-2 text-xs font-medium transition"
            :class="
              store.activeFilter === filter.value
                ? 'border-zinc-900 text-zinc-900'
                : 'border-transparent text-zinc-500 hover:text-zinc-900'
            "
            type="button"
            :aria-pressed="store.activeFilter === filter.value"
            @click="store.activeFilter = filter.value"
          >
            {{ filter.label }}
          </button>
        </div>

        <div v-if="store.isLoading" class="grid min-h-32 place-items-center text-xs text-zinc-500" role="status">
          Loading tasks…
        </div>
        <div
          v-else-if="visibleTasks.length === 0"
          class="grid min-h-36 content-center justify-items-center gap-2 rounded-xl border border-dashed border-zinc-200 bg-white px-4 text-center"
        >
          <span class="grid size-10 place-items-center rounded-xl bg-zinc-100 text-zinc-500">
            <svg viewBox="0 0 24 24" class="size-5 fill-none stroke-current" aria-hidden="true">
              <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-linecap="round" stroke-width="2" />
            </svg>
          </span>
          <p class="text-sm font-medium">{{ store.tasks.length ? 'No tasks in this view' : 'Nothing on your list yet' }}</p>
          <p class="text-xs text-zinc-500">
            {{ store.tasks.length ? 'Choose another filter to see more tasks.' : 'Add a task above to get started.' }}
          </p>
        </div>
        <ul v-else class="divide-y divide-zinc-100 overflow-hidden rounded-xl border border-zinc-200 bg-white">
          <li v-for="task in visibleTasks" :key="task.id" class="flex items-start gap-3 p-3.5">
            <button
              class="mt-0.5 grid size-[18px] shrink-0 place-items-center rounded-full border transition"
              :class="
                task.status === 'completed'
                  ? 'border-emerald-600 bg-emerald-600 text-white'
                  : 'border-zinc-300 text-transparent hover:border-zinc-500'
              "
              type="button"
              :aria-label="task.status === 'completed' ? `Reopen ${task.title}` : `Complete ${task.title}`"
              :disabled="store.isBusy"
              @click="store.updateTaskStatus(task, task.status === 'completed' ? 'pending' : 'completed')"
            >
              <svg viewBox="0 0 16 16" class="size-3 fill-none stroke-current" aria-hidden="true">
                <path d="m3 8 3 3 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
              </svg>
            </button>
            <div class="min-w-0 flex-1">
              <p
                class="break-words text-sm font-medium"
                :class="task.status === 'completed' ? 'text-zinc-400 line-through' : 'text-zinc-800'"
              >
                {{ task.title }}
              </p>
              <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] text-zinc-500">
                <span v-if="task.category">{{ task.category }}</span>
                <span class="capitalize">{{ task.priority }} priority</span>
                <span>{{ formatDueDate(task.due_date) }}</span>
              </div>
              <label class="mt-2 inline-flex items-center gap-1.5 text-[10px] text-zinc-500">
                Status
                <select
                  class="h-7 rounded-md border border-zinc-200 bg-white px-1.5 text-[10px] text-zinc-700"
                  :value="task.status"
                  :aria-label="`Status for ${task.title}`"
                  :disabled="store.isBusy"
                  @change="store.updateTaskStatus(task, ($event.target as HTMLSelectElement).value as TaskStatus)"
                >
                  <option value="pending">To do</option>
                  <option value="in_progress">In progress</option>
                  <option value="completed">Done</option>
                </select>
              </label>
            </div>
            <button
              class="grid size-8 shrink-0 place-items-center rounded-lg text-zinc-400 transition hover:bg-red-50 hover:text-red-700 disabled:opacity-50"
              type="button"
              :aria-label="`Delete ${task.title}`"
              :disabled="store.isBusy"
              @click="confirmDelete(task)"
            >
              <svg viewBox="0 0 24 24" class="size-[17px] fill-none stroke-current" aria-hidden="true">
                <path d="M4 7h16m-10 4v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" />
              </svg>
            </button>
          </li>
        </ul>

        <a
          class="flex items-center justify-center gap-2 rounded-lg py-2 text-xs font-medium text-zinc-500 transition hover:text-zinc-900"
          :href="`${appUrl}/tasks`"
          target="_blank"
          rel="noreferrer"
        >
          Open Task Tracker
          <svg viewBox="0 0 20 20" class="size-3.5 fill-none stroke-current" aria-hidden="true">
            <path d="M11 3h6v6m-1-5-8 8m5-5v8H3V5h8" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
          </svg>
        </a>
      </div>
    </section>
  </main>
</template>

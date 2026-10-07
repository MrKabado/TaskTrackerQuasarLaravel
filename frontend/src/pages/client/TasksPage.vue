<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex flex-wrap items-start justify-between gap-4">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Tasks</p>
        <h1 class="heading-page m-0 text-zinc-900">Tasks</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Keep your work organized and make progress at your own pace.
        </p>
      </div>
      <button
        class="inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
        type="button"
        @click="openCreate"
      >
        <q-icon name="add" size="17px" />
        New task
      </button>
    </div>

    <p
      v-if="feedback"
      class="mb-4 rounded-lg border border-green-200 bg-green-50 px-3.5 py-3 text-xs text-green-800"
      role="status"
    >
      {{ feedback }}
    </p>
    <p
      v-if="error"
      class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-xs text-red-700"
      role="alert"
    >
      {{ error }}
    </p>

    <div
      class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm shadow-zinc-900/[0.02]"
    >
      <div
        class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-100 px-4 py-5 sm:px-5"
      >
        <div>
          <h2 class="m-0 text-sm font-semibold tracking-tight">Task list</h2>
          <p class="mt-1 text-[11px] text-zinc-500">
            {{ tasks.length }} {{ tasks.length === 1 ? 'task' : 'tasks' }}
          </p>
        </div>
        <form class="flex w-full gap-2 sm:w-auto" @submit.prevent="searchTasks">
          <label class="sr-only" for="task-search">Search tasks</label>
          <input
            id="task-search"
            v-model="searchInput"
            class="h-[38px] min-w-0 flex-1 rounded-lg border border-zinc-200 bg-white px-3 text-xs text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5 sm:w-[230px] sm:flex-none"
            type="search"
            placeholder="Search tasks"
          />
          <button
            class="grid size-[38px] shrink-0 place-items-center rounded-lg border border-zinc-200 text-zinc-600 transition hover:bg-zinc-50"
            type="submit"
            aria-label="Search tasks"
          >
            <q-icon name="search" size="18px" />
          </button>
        </form>
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

      <div
        v-if="isLoading"
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
        <strong class="text-[13px] font-semibold text-zinc-800">
          {{ currentSearch ? 'No matching tasks' : emptyTitle }}
        </strong>
        <span>
          {{
            currentSearch
              ? 'Try a different search.'
              : activeFilter === 'All Tasks'
                ? 'Create your first task to get started.'
                : 'Try another filter to see more of your tasks.'
          }}
        </span>
        <button
          v-if="activeFilter === 'All Tasks' && !currentSearch"
          class="mt-2 text-xs font-medium text-zinc-800 underline underline-offset-4"
          type="button"
          @click="openCreate"
        >
          Create a task
        </button>
      </div>
      <ul v-else class="m-0 list-none p-0">
        <li
          v-for="task in tasks"
          :key="task.id"
          class="flex min-h-[69px] items-center gap-2.5 border-b border-zinc-100 px-3 py-3 last:border-0 sm:gap-3 sm:px-5"
        >
          <span class="grid min-w-0 flex-1 gap-1">
            <strong class="truncate text-xs font-medium">{{ task.title }}</strong>
            <span class="truncate text-[11px] text-zinc-400">{{
              task.category || task.description || 'No additional details'
            }}</span>
          </span>
          <span
            class="inline-flex shrink-0 rounded-full px-2 py-1 text-[9px] font-medium sm:px-2.5 sm:text-[10px]"
            :class="taskStatusClass(task)"
          >
            {{ taskStatusLabel(task) }}
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
            class="hidden w-[85px] shrink-0 text-right text-[10px] text-zinc-500 sm:block sm:text-[11px]"
            >{{ formatDueDate(task.due_date) }}</span
          >
          <button
            class="grid size-8 shrink-0 place-items-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900"
            type="button"
            :aria-label="`Edit ${task.title}`"
            @click="openEdit(task)"
          >
            <q-icon name="edit" size="17px" />
          </button>
          <button
            class="grid size-8 shrink-0 place-items-center rounded-lg text-zinc-400 transition hover:bg-red-50 hover:text-red-700"
            type="button"
            :aria-label="`Delete ${task.title}`"
            @click="confirmDelete(task)"
          >
            <q-icon name="delete_outline" size="18px" />
          </button>
        </li>
      </ul>
    </div>

    <q-dialog v-model="isFormOpen">
      <q-card class="w-full max-w-xl rounded-xl">
        <q-form @submit.prevent="saveTask">
          <q-card-section class="flex items-start justify-between gap-4 border-b border-zinc-100">
            <div>
              <h2 class="text-base font-semibold text-zinc-900">
                {{ editingTaskId === null ? 'Create task' : 'Edit task' }}
              </h2>
              <p class="mt-1 text-xs text-zinc-500">Add the details you need to stay on track.</p>
            </div>
            <button
              class="grid size-8 shrink-0 place-items-center rounded-lg text-zinc-500 hover:bg-zinc-100"
              type="button"
              aria-label="Close task form"
              @click="closeForm"
            >
              <q-icon name="close" size="18px" />
            </button>
          </q-card-section>

          <q-card-section class="grid max-h-[65vh] gap-4 overflow-y-auto">
            <div class="grid min-w-0 gap-2">
              <label for="task-title" class="text-xs font-medium text-zinc-700">Title</label>
              <input
                id="task-title"
                v-model="form.title"
                class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                type="text"
                maxlength="255"
                required
                autofocus
              />
            </div>

            <div class="grid min-w-0 gap-2">
              <label for="task-description" class="text-xs font-medium text-zinc-700"
                >Description</label
              >
              <textarea
                id="task-description"
                v-model="form.description"
                class="min-h-20 w-full resize-y rounded-lg border border-zinc-200 bg-white px-3 py-2.5 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                rows="3"
              />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="grid min-w-0 gap-2">
                <label for="task-status" class="text-xs font-medium text-zinc-700">Status</label>
                <select
                  id="task-status"
                  v-model="form.status"
                  class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                >
                  <option value="pending">Pending</option>
                  <option value="in_progress">In progress</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
              <div class="grid min-w-0 gap-2">
                <label for="task-priority" class="text-xs font-medium text-zinc-700"
                  >Priority</label
                >
                <select
                  id="task-priority"
                  v-model="form.priority"
                  class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                >
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="grid min-w-0 gap-2">
                <label for="task-category" class="text-xs font-medium text-zinc-700"
                  >Category</label
                >
                <input
                  id="task-category"
                  v-model="form.category"
                  class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                  type="text"
                  maxlength="255"
                  placeholder="Work, personal, school…"
                />
              </div>
              <div class="grid min-w-0 gap-2">
                <label for="task-due-date" class="text-xs font-medium text-zinc-700"
                  >Due date</label
                >
                <input
                  id="task-due-date"
                  v-model="form.due_date"
                  class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                  type="date"
                />
              </div>
            </div>

            <div class="grid min-w-0 gap-2">
              <label for="task-notes" class="text-xs font-medium text-zinc-700">Notes</label>
              <textarea
                id="task-notes"
                v-model="form.notes"
                class="min-h-20 w-full resize-y rounded-lg border border-zinc-200 bg-white px-3 py-2.5 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                rows="3"
              />
            </div>

            <p
              v-if="formError"
              class="m-0 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-xs text-red-700"
              role="alert"
            >
              {{ formError }}
            </p>
          </q-card-section>

          <q-card-actions align="right" class="border-t border-zinc-100 px-5 py-4">
            <button
              class="inline-flex min-h-[38px] items-center justify-center rounded-lg px-3.5 text-xs font-medium text-zinc-600 transition hover:bg-zinc-100 disabled:opacity-60"
              type="button"
              :disabled="isSaving"
              @click="closeForm"
            >
              Cancel
            </button>
            <button
              class="inline-flex min-h-[38px] items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
              type="submit"
              :disabled="isSaving"
            >
              {{ isSaving ? 'Saving…' : editingTaskId === null ? 'Create task' : 'Save changes' }}
            </button>
          </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>

    <q-dialog v-model="isDeleteDialogOpen">
      <q-card class="w-full max-w-sm rounded-xl">
        <q-card-section>
          <h2 class="text-base font-semibold text-zinc-900">Delete task?</h2>
          <p class="mt-2 text-sm leading-relaxed text-zinc-500">
            “{{ taskToDelete?.title }}” will be moved to trash.
          </p>
          <p
            v-if="deleteError"
            class="mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-xs text-red-700"
            role="alert"
          >
            {{ deleteError }}
          </p>
        </q-card-section>
        <q-card-actions align="right" class="px-5 pb-4">
          <button
            class="inline-flex min-h-[38px] items-center justify-center rounded-lg px-3.5 text-xs font-medium text-zinc-600 transition hover:bg-zinc-100"
            type="button"
            :disabled="isDeleting"
            @click="isDeleteDialogOpen = false"
          >
            Cancel
          </button>
          <button
            class="inline-flex min-h-[38px] items-center justify-center gap-2 rounded-lg bg-red-600 px-3.5 text-xs font-medium text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
            type="button"
            :disabled="isDeleting"
            @click="deleteSelectedTask"
          >
            {{ isDeleting ? 'Deleting…' : 'Delete task' }}
          </button>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { getApiErrorMessage } from '@/services/auth';
import {
  createTask,
  deleteTask,
  getTasks,
  updateTask,
  type Task,
  type TaskFilters,
  type TaskInput,
  type TaskStatus,
} from '@/services/tasks';

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
const isSaving = ref(false);
const isDeleting = ref(false);
const isFormOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const editingTaskId = ref<number | null>(null);
const taskToDelete = ref<Task | null>(null);
const searchInput = ref('');
const currentSearch = ref('');
const error = ref('');
const formError = ref('');
const deleteError = ref('');
const feedback = ref('');
const form = ref<TaskInput>(emptyTask());

const emptyTitle = computed(() => {
  const selected = filters.find((filter) => filter.label === activeFilter.value);
  return selected?.label === 'All Tasks'
    ? 'No tasks yet'
    : `No ${selected?.label.toLowerCase()} tasks`;
});

onMounted(() => loadTasks());

function emptyTask(): TaskInput {
  return {
    title: '',
    description: '',
    status: 'pending',
    priority: 'medium',
    category: '',
    notes: '',
    due_date: '',
  };
}

async function loadTasks() {
  isLoading.value = true;
  error.value = '';
  feedback.value = '';

  try {
    const selectedFilter = filters.find((filter) => filter.label === activeFilter.value);
    const params: TaskFilters = {
      ...(selectedFilter?.status ? { status: selectedFilter.status } : {}),
      ...(selectedFilter?.deadline ? { deadline: selectedFilter.deadline } : {}),
      ...(currentSearch.value ? { search: currentSearch.value } : {}),
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
  void loadTasks();
}

function searchTasks() {
  currentSearch.value = searchInput.value.trim();
  void loadTasks();
}

function openCreate() {
  editingTaskId.value = null;
  form.value = emptyTask();
  formError.value = '';
  isFormOpen.value = true;
}

function openEdit(task: Task) {
  editingTaskId.value = task.id;
  form.value = {
    title: task.title,
    description: task.description ?? '',
    status: task.status,
    priority: task.priority,
    category: task.category ?? '',
    notes: task.notes ?? '',
    due_date: task.due_date ?? '',
  };
  formError.value = '';
  isFormOpen.value = true;
}

function closeForm() {
  if (isSaving.value) return;
  isFormOpen.value = false;
}

function normalizedForm(): TaskInput {
  return {
    ...form.value,
    title: form.value.title.trim(),
    description: form.value.description?.trim() || null,
    category: form.value.category?.trim() || null,
    notes: form.value.notes?.trim() || null,
    due_date: form.value.due_date || null,
  };
}

async function saveTask() {
  isSaving.value = true;
  formError.value = '';
  feedback.value = '';

  try {
    const input = normalizedForm();
    const successMessage =
      editingTaskId.value === null ? 'Task created successfully.' : 'Task updated successfully.';
    if (editingTaskId.value === null) {
      await createTask(input);
    } else {
      await updateTask(editingTaskId.value, input);
    }
    isFormOpen.value = false;
    await loadTasks();
    feedback.value = successMessage;
  } catch (requestError: unknown) {
    formError.value = getApiErrorMessage(requestError);
  } finally {
    isSaving.value = false;
  }
}

function confirmDelete(task: Task) {
  taskToDelete.value = task;
  deleteError.value = '';
  isDeleteDialogOpen.value = true;
}

async function deleteSelectedTask() {
  if (!taskToDelete.value) return;

  isDeleting.value = true;
  deleteError.value = '';
  feedback.value = '';

  try {
    await deleteTask(taskToDelete.value.id);
    isDeleteDialogOpen.value = false;
    taskToDelete.value = null;
    await loadTasks();
    feedback.value = 'Task moved to trash.';
  } catch (requestError: unknown) {
    deleteError.value = getApiErrorMessage(requestError);
  } finally {
    isDeleting.value = false;
  }
}

function formatDueDate(date: string | null): string {
  if (!date) return 'No due date';

  return new Intl.DateTimeFormat(undefined, { month: 'short', day: 'numeric' }).format(
    new Date(`${date}T00:00:00`),
  );
}

function isOverdue(task: Task): boolean {
  const today = new Date();
  today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
  const localDate = today.toISOString().slice(0, 10);

  return task.status !== 'completed' && task.due_date !== null && task.due_date < localDate;
}

function taskStatusLabel(task: Task): string {
  if (isOverdue(task)) return 'Overdue';
  if (task.status === 'in_progress') return 'In progress';
  return task.status === 'completed' ? 'Completed' : 'Pending';
}

function taskStatusClass(task: Task): string {
  if (isOverdue(task)) return 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200/70';
  if (task.status === 'in_progress')
    return 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200/70';
  if (task.status === 'completed')
    return 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200/70';
  return 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200/70';
}
</script>

import axios from 'axios';
import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export type TaskStatus = 'pending' | 'in_progress' | 'completed';
type TaskPriority = 'low' | 'medium' | 'high';

export interface TrackerUser {
  id: number;
  name: string;
  email: string;
}

export interface TrackerTask {
  id: number;
  title: string;
  description: string | null;
  status: TaskStatus;
  priority: TaskPriority;
  category: string | null;
  notes: string | null;
  due_date: string | null;
}

export interface NewTask {
  title: string;
  description: string | null;
  status: TaskStatus;
  priority: TaskPriority;
  category: string | null;
  notes: string | null;
  due_date: string | null;
}

interface LoginResponse {
  user: TrackerUser;
  token: string;
}

const TOKEN_KEY = 'task_tracker_extension_token';
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use(async (config) => {
  const { [TOKEN_KEY]: token } = await chrome.storage.local.get(TOKEN_KEY);
  if (typeof token === 'string' && token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

function getErrorMessage(error: unknown): string {
  if (axios.isAxiosError(error)) {
    const data: unknown = error.response?.data;
    if (data && typeof data === 'object') {
      const payload = data as { message?: unknown; errors?: Record<string, unknown> };
      const validationMessage = payload.errors
        ? Object.values(payload.errors)
            .flatMap((value) => (Array.isArray(value) ? value : [value]))
            .find((value): value is string => typeof value === 'string')
        : undefined;
      if (validationMessage) return validationMessage;
      if (typeof payload.message === 'string') return payload.message;
    }
    if (!error.response) {
      return 'Unable to reach Task Tracker. Check that the server is running and try again.';
    }
  }
  return error instanceof Error ? error.message : 'Something went wrong. Please try again.';
}

function hasExtensionStorage(): boolean {
  return typeof chrome !== 'undefined' && Boolean(chrome.storage?.local);
}

export const useTaskTrackerStore = defineStore('taskTracker', () => {
  const user = ref<TrackerUser | null>(null);
  const tasks = ref<TrackerTask[]>([]);
  const isInitializing = ref(true);
  const isLoading = ref(false);
  const isBusy = ref(false);
  const error = ref('');
  const activeFilter = ref<TaskStatus | 'all'>('all');

  const completedCount = computed(
    () => tasks.value.filter((task) => task.status === 'completed').length,
  );

  function clearSession() {
    user.value = null;
    tasks.value = [];
    activeFilter.value = 'all';
  }

  async function loadTasks() {
    isLoading.value = true;
    error.value = '';
    try {
      const { data } = await api.get<TrackerTask[]>('/tasks', {
        params: { sort_by: 'newest' },
      });
      tasks.value = data;
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
      if (axios.isAxiosError(requestError) && requestError.response?.status === 401) {
        await chrome.storage.local.remove(TOKEN_KEY);
        clearSession();
      }
    } finally {
      isLoading.value = false;
    }
  }

  async function restoreSession() {
    isInitializing.value = true;
    error.value = '';
    if (!hasExtensionStorage()) {
      error.value = 'Open this page from the Task Tracker Chrome extension to connect your account.';
      isInitializing.value = false;
      return;
    }

    try {
      const { [TOKEN_KEY]: token } = await chrome.storage.local.get(TOKEN_KEY);
      if (typeof token !== 'string' || !token) return;

      const { data } = await api.get<TrackerUser>('/auth/user');
      user.value = data;
      await loadTasks();
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
      if (axios.isAxiosError(requestError) && requestError.response?.status === 401) {
        await chrome.storage.local.remove(TOKEN_KEY);
      }
      clearSession();
    } finally {
      isInitializing.value = false;
    }
  }

  async function login(email: string, password: string) {
    if (!hasExtensionStorage()) {
      error.value = 'Open this page from the Task Tracker Chrome extension to connect your account.';
      return;
    }

    isBusy.value = true;
    error.value = '';
    try {
      const { data } = await api.post<LoginResponse>('/auth/login', { email, password });
      await chrome.storage.local.set({ [TOKEN_KEY]: data.token });
      user.value = data.user;
      await loadTasks();
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
    } finally {
      isBusy.value = false;
    }
  }

  async function logout() {
    isBusy.value = true;
    error.value = '';
    try {
      await api.post('/auth/logout');
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
    } finally {
      await chrome.storage.local.remove(TOKEN_KEY);
      clearSession();
      isBusy.value = false;
    }
  }

  async function createTask(input: NewTask): Promise<TrackerTask | null> {
    isBusy.value = true;
    error.value = '';
    try {
      const { data } = await api.post<TrackerTask>('/tasks', input);
      tasks.value = [data, ...tasks.value];
      return data;
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
      return null;
    } finally {
      isBusy.value = false;
    }
  }

  async function updateTaskStatus(task: TrackerTask, status: TaskStatus) {
    if (task.status === status) return;
    isBusy.value = true;
    error.value = '';
    try {
      const { data } = await api.patch<TrackerTask>(`/tasks/${task.id}`, { status });
      const taskIndex = tasks.value.findIndex((currentTask) => currentTask.id === task.id);
      if (taskIndex !== -1) tasks.value[taskIndex] = data;
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
    } finally {
      isBusy.value = false;
    }
  }

  async function deleteTask(task: TrackerTask) {
    isBusy.value = true;
    error.value = '';
    try {
      await api.delete(`/tasks/${task.id}`);
      tasks.value = tasks.value.filter((currentTask) => currentTask.id !== task.id);
    } catch (requestError: unknown) {
      error.value = getErrorMessage(requestError);
    } finally {
      isBusy.value = false;
    }
  }

  return {
    user,
    tasks,
    isInitializing,
    isLoading,
    isBusy,
    error,
    activeFilter,
    completedCount,
    restoreSession,
    login,
    logout,
    loadTasks,
    createTask,
    updateTaskStatus,
    deleteTask,
  };
});

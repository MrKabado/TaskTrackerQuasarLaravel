import axios from 'axios';
import { getAuthToken } from '@/services/auth';

export type TaskStatus = 'pending' | 'in_progress' | 'completed';

export interface Task {
  id: number;
  title: string;
  description: string | null;
  status: TaskStatus;
  priority: 'high' | 'medium' | 'low';
  category: string | null;
  notes: string | null;
  due_date: string | null;
}

export interface TaskInput {
  title: string;
  description: string | null;
  status: TaskStatus;
  priority: Task['priority'];
  category: string | null;
  notes: string | null;
  due_date: string | null;
}

export interface TaskFilters {
  status?: TaskStatus;
  priority?: Task['priority'];
  category?: string;
  deadline?: 'today' | 'upcoming' | 'overdue' | 'none';
  due_date?: string;
  due_date_from?: string;
  due_date_to?: string;
  search?: string;
  sort_by?: 'newest' | 'oldest' | 'deadline' | 'priority' | 'status';
}

export interface DashboardSummary {
  total_tasks: number;
  pending_tasks: number;
  in_progress_tasks: number;
  completed_tasks: number;
  overdue_tasks: number;
  tasks_due_today: number;
}

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
  const token = getAuthToken();

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

export async function getDashboardSummary(): Promise<DashboardSummary> {
  const response = await api.get<DashboardSummary>('/dashboard');
  return response.data;
}

export async function getTasks(params: TaskFilters = {}): Promise<Task[]> {
  const response = await api.get<Task[]>('/tasks', { params });
  return response.data;
}

export async function createTask(input: TaskInput): Promise<Task> {
  const response = await api.post<Task>('/tasks', input);
  return response.data;
}

export async function updateTask(id: number, input: TaskInput): Promise<Task> {
  const response = await api.patch<Task>(`/tasks/${id}`, input);
  return response.data;
}

export async function deleteTask(id: number): Promise<void> {
  await api.delete(`/tasks/${id}`);
}

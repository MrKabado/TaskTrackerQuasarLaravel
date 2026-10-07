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
  due_date: string | null;
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

export async function getTasks(params: {
  status?: TaskStatus;
  deadline?: 'overdue';
} = {}): Promise<Task[]> {
  const response = await api.get<Task[]>('/tasks', { params });
  return response.data;
}

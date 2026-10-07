import axios from 'axios';

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  account_status: string;
}

interface AuthResponse {
  user: AuthUser;
  token: string;
}

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  headers: {
    Accept: 'application/json',
  },
});

api.interceptors.request.use((config) => {
  const token = getAuthToken();

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

export function getAuthToken(): string | null {
  if (typeof window === 'undefined') {
    return null;
  }

  return localStorage.getItem('taskflow_token') || sessionStorage.getItem('taskflow_token');
}

export function storeAuthToken(token: string, remember: boolean): void {
  if (remember) {
    localStorage.setItem('taskflow_token', token);
    sessionStorage.removeItem('taskflow_token');
    return;
  }

  sessionStorage.setItem('taskflow_token', token);
  localStorage.removeItem('taskflow_token');
}

export function clearAuthToken(): void {
  localStorage.removeItem('taskflow_token');
  sessionStorage.removeItem('taskflow_token');
}

export async function validateAuthToken(): Promise<boolean> {
  try {
    await api.get<AuthUser>('/auth/user');
    return true;
  } catch (error: unknown) {
    if (axios.isAxiosError(error) && error.response?.status === 401) {
      clearAuthToken();
      return false;
    }

    throw error;
  }
}

export async function getCurrentUser(): Promise<AuthUser> {
  const response = await api.get<AuthUser>('/auth/user');
  return response.data;
}

export async function logout(): Promise<void> {
  await api.post('/auth/logout');
  clearAuthToken();
}

export async function updateProfile(input: { name: string; email: string }): Promise<AuthUser> {
  const response = await api.patch<AuthUser>('/auth/profile', input);
  return response.data;
}

export async function changePassword(input: {
  current_password: string;
  password: string;
  password_confirmation: string;
}): Promise<string> {
  const response = await api.post<{ message: string }>('/auth/change-password', input);
  return response.data.message;
}

export function getApiErrorMessage(error: unknown): string {
  if (!axios.isAxiosError(error)) {
    return 'Something went wrong. Please try again.';
  }

  const data: unknown = error.response?.data;

  if (data && typeof data === 'object') {
    const response = data as { message?: unknown; errors?: Record<string, unknown> };
    const validationMessages = response.errors
      ? Object.values(response.errors).flatMap((value) => (Array.isArray(value) ? value : [value]))
      : [];
    const firstValidationMessage = validationMessages.find(
      (value): value is string => typeof value === 'string',
    );

    if (firstValidationMessage) {
      return firstValidationMessage;
    }

    if (typeof response.message === 'string') {
      return response.message;
    }
  }

  if (!error.response) {
    return 'Unable to reach the server. Check your connection and try again.';
  }

  return 'The request could not be completed. Please try again.';
}

export async function login(email: string, password: string): Promise<AuthResponse> {
  const response = await api.post<AuthResponse>('/auth/login', { email, password });
  return response.data;
}

export async function requestRegisterOtp(email: string): Promise<string> {
  const response = await api.post<{ message: string }>('/auth/send-register-otp', { email });
  return response.data.message;
}

export async function register(input: {
  name: string;
  email: string;
  otp: string;
  password: string;
  password_confirmation: string;
}): Promise<AuthResponse> {
  const response = await api.post<AuthResponse>('/auth/register', input);
  return response.data;
}

export async function requestPasswordResetOtp(email: string): Promise<string> {
  const response = await api.post<{ message: string }>('/auth/send-forgot-password-otp', { email });
  return response.data.message;
}

export async function resetPassword(input: {
  email: string;
  otp: string;
  password: string;
  password_confirmation: string;
}): Promise<string> {
  const response = await api.post<{ message: string }>('/auth/reset-password', input);
  return response.data.message;
}

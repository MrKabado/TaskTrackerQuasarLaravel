import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('@/pages/IndexPage.vue'),
  },
  {
    path: '/second',
    component: () => import('@/layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('@/pages/SecondPage.vue') }],
  },
  { path: '/login', component: () => import('@/pages/auth/Login.vue') },
  { path: '/register', component: () => import('@/pages/auth/Register.vue') },
  { path: '/forgot-password', component: () => import('@/pages/auth/ForgotPassword.vue') },
  {
    path: '/dashboard',
    component: () => import('@/layouts/DashboardLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: '', component: () => import('@/pages/client/HomePage.vue') },
      { path: '/tasks', component: () => import('@/pages/client/TasksPage.vue') },
      { path: '/settings', component: () => import('@/pages/client/SettingsPage.vue') },
      { path: '/settings/profile', component: () => import('@/pages/client/ProfilePage.vue') },
      { path: '/settings/password', component: () => import('@/pages/client/ChangePasswordPage.vue') },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('@/pages/ErrorNotFound.vue'),
  },
];

export default routes;

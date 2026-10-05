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
    component: () => import('@/pages/client/HomePage.vue'),
    meta: { requiresAuth: true },
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('@/pages/ErrorNotFound.vue'),
  },
];

export default routes;

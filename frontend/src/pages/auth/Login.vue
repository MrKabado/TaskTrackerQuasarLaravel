<template>
  <AuthShell
    kicker="Welcome back"
    title="Log in to Taskflow"
    description="Enter your details to access your workspace."
    footer-text="New to Taskflow?"
    footer-link="/register"
    footer-link-text="Create an account"
  >
    <form class="grid gap-[18px]" @submit.prevent="submitLogin">
      <div class="grid min-w-0 gap-2">
        <label for="login-email" class="text-[13px] font-medium text-zinc-700">Email address</label>
        <input
          id="login-email"
          v-model="email"
          class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
          type="email"
          autocomplete="email"
          required
          placeholder="you@example.com"
        />
      </div>

      <div class="grid min-w-0 gap-2">
        <div class="flex items-center justify-between gap-2.5">
          <label for="login-password" class="text-[13px] font-medium text-zinc-700">Password</label>
          <router-link
            to="/forgot-password"
            class="text-xs font-medium text-zinc-700 hover:text-zinc-500"
          >
            Forgot password?
          </router-link>
        </div>
        <input
          id="login-password"
          v-model="password"
          class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
          type="password"
          autocomplete="current-password"
          required
          placeholder="Enter your password"
        />
      </div>

      <label class="flex items-center gap-2 text-[13px] text-zinc-600">
        <input v-model="remember" class="size-3.5 accent-zinc-900" type="checkbox" />
        Remember me
      </label>

      <button
        type="submit"
        class="inline-flex min-h-[38px] w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-[13px] font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="isSubmitting"
      >
        {{ isSubmitting ? 'Logging in…' : 'Log in' }}
      </button>

      <p
        v-if="message"
        :role="isError ? 'alert' : 'status'"
        :class="[
          'rounded-lg border px-3 py-2.5 text-[13px] leading-relaxed',
          isError
            ? 'border-red-200 bg-red-50 text-red-700'
            : 'border-green-200 bg-green-50 text-green-800',
        ]"
      >
        {{ message }}
      </p>
    </form>
  </AuthShell>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getApiErrorMessage, login, storeAuthToken } from '@/services/auth';
import AuthShell from '@/components/AuthShell.vue';

defineOptions({ name: 'LoginPage' });

const email = ref('');
const password = ref('');
const remember = ref(false);
const message = ref('');
const isError = ref(false);
const isSubmitting = ref(false);
const route = useRoute();
const router = useRouter();

async function submitLogin() {
  isSubmitting.value = true;
  message.value = '';
  isError.value = false;

  try {
    const response = await login(email.value.trim(), password.value);
    storeAuthToken(response.token, remember.value);

    const redirect = route.query.redirect;
    const destination =
      typeof redirect === 'string' && redirect.startsWith('/') && !redirect.startsWith('//')
        ? redirect
        : '/dashboard';

    await router.push(destination);
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isSubmitting.value = false;
  }
}
</script>

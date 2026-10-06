<template>
  <AuthShell
    kicker="Welcome back"
    title="Log in to Taskflow"
    description="Enter your details to access your workspace."
    footer-text="New to Taskflow?"
    footer-link="/register"
    footer-link-text="Create an account"
  >
    <form class="auth-form" @submit.prevent="submitLogin">
      <div class="form-field">
        <label for="login-email" class="form-label">Email address</label>
        <input
          id="login-email"
          v-model="email"
          class="form-input"
          type="email"
          autocomplete="email"
          required
          placeholder="you@example.com"
        />
      </div>

      <div class="form-field">
        <div class="auth-inline">
          <label for="login-password" class="form-label">Password</label>
          <router-link to="/forgot-password" class="auth-inline-link">
            Forgot password?
          </router-link>
        </div>
        <input
          id="login-password"
          v-model="password"
          class="form-input"
          type="password"
          autocomplete="current-password"
          required
          placeholder="Enter your password"
        />
      </div>

      <label class="auth-checkbox">
        <input v-model="remember" type="checkbox" />
        Remember me
      </label>

      <button type="submit" class="button-primary" :disabled="isSubmitting">
        {{ isSubmitting ? 'Logging in…' : 'Log in' }}
      </button>

      <p
        v-if="message"
        :role="isError ? 'alert' : 'status'"
        :class="['form-notice', { 'is-error': isError, 'is-success': !isError }]"
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

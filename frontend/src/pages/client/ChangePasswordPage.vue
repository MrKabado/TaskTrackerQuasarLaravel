<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex items-start justify-between gap-5">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Settings / Password</p>
        <h1 class="m-0 text-3xl font-semibold tracking-tight text-zinc-900">Change password</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Choose a new password to keep your account secure.
        </p>
      </div>
      <router-link
        to="/settings"
        class="inline-flex min-h-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50"
        >Back to settings</router-link
      >
    </div>

    <div
      class="max-w-[520px] rounded-xl border border-zinc-200 bg-white p-5 shadow-sm shadow-zinc-900/[0.02] sm:p-6"
    >
      <form class="grid gap-4" @submit.prevent="savePassword">
        <div class="grid min-w-0 gap-2">
          <label for="current-password" class="text-xs font-medium text-zinc-700"
            >Current password</label
          >
          <input
            id="current-password"
            v-model="currentPassword"
            class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
            type="password"
            autocomplete="current-password"
            required
          />
        </div>
        <div class="grid min-w-0 gap-2">
          <label for="new-password" class="text-xs font-medium text-zinc-700">New password</label>
          <input
            id="new-password"
            v-model="password"
            class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
            type="password"
            autocomplete="new-password"
            minlength="8"
            required
          />
        </div>
        <div class="grid min-w-0 gap-2">
          <label for="confirm-password" class="text-xs font-medium text-zinc-700"
            >Confirm new password</label
          >
          <input
            id="confirm-password"
            v-model="passwordConfirmation"
            class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
            type="password"
            autocomplete="new-password"
            minlength="8"
            required
          />
        </div>
        <button
          class="inline-flex min-h-[38px] w-fit items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
          type="submit"
          :disabled="isSaving"
        >
          {{ isSaving ? 'Updating…' : 'Update password' }}
        </button>
        <p
          v-if="message"
          :class="[
            'rounded-lg border px-3 py-2.5 text-xs leading-relaxed',
            isError
              ? 'border-red-200 bg-red-50 text-red-700'
              : 'border-green-200 bg-green-50 text-green-800',
          ]"
        >
          {{ message }}
        </p>
      </form>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { changePassword, getApiErrorMessage } from '@/services/auth';

defineOptions({ name: 'ChangePasswordPage' });

const currentPassword = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const isSaving = ref(false);
const isError = ref(false);
const message = ref('');

async function savePassword() {
  isSaving.value = true;
  isError.value = false;
  message.value = '';

  try {
    message.value = await changePassword({
      current_password: currentPassword.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });
    currentPassword.value = '';
    password.value = '';
    passwordConfirmation.value = '';
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isSaving.value = false;
  }
}
</script>

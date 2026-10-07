<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex items-start justify-between gap-5">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Settings / Profile</p>
        <h1 class="m-0 text-3xl font-semibold tracking-tight text-zinc-900">Profile</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Update the name and email address associated with your account.
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
      <p v-if="isLoading" class="px-5 py-16 text-center text-sm text-zinc-500" role="status">
        Loading profile…
      </p>
      <form v-else class="grid gap-4" @submit.prevent="saveProfile">
        <div class="grid min-w-0 gap-2">
          <label for="profile-name" class="text-xs font-medium text-zinc-700">Name</label>
          <input
            id="profile-name"
            v-model="name"
            class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
            autocomplete="name"
            required
          />
        </div>
        <div class="grid min-w-0 gap-2">
          <label for="profile-email" class="text-xs font-medium text-zinc-700">Email address</label>
          <input
            id="profile-email"
            v-model="email"
            class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
            type="email"
            autocomplete="email"
            required
          />
        </div>
        <button
          class="inline-flex min-h-[38px] w-fit items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
          type="submit"
          :disabled="isSaving"
        >
          {{ isSaving ? 'Saving…' : 'Save changes' }}
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
import { onMounted, ref } from 'vue';
import { getApiErrorMessage, getCurrentUser, updateProfile } from '@/services/auth';

defineOptions({ name: 'ProfilePage' });

const name = ref('');
const email = ref('');
const isLoading = ref(true);
const isSaving = ref(false);
const isError = ref(false);
const message = ref('');

onMounted(async () => {
  try {
    const user = await getCurrentUser();
    name.value = user.name;
    email.value = user.email;
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isLoading.value = false;
  }
});

async function saveProfile() {
  isSaving.value = true;
  isError.value = false;
  message.value = '';

  try {
    const user = await updateProfile({ name: name.value.trim(), email: email.value.trim() });
    name.value = user.name;
    email.value = user.email;
    message.value = 'Your profile has been updated.';
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isSaving.value = false;
  }
}
</script>

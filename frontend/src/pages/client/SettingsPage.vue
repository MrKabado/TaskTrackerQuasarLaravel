<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex items-start justify-between gap-5">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Settings</p>
        <h1 class="m-0 text-3xl font-semibold tracking-tight text-zinc-900">Settings</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Manage your account and keep your workspace up to date.
        </p>
      </div>
    </div>

    <div
      class="max-w-[720px] overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm shadow-zinc-900/[0.02]"
    >
      <div class="flex items-center gap-3 p-5">
        <span
          class="grid size-[38px] place-items-center rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-600"
          ><q-icon name="person_outline" size="20px"
        /></span>
        <div>
          <h2 class="m-0 text-sm font-semibold">Account</h2>
          <p class="mt-1 text-[11px] text-zinc-500">Manage your profile and sign-in details.</p>
        </div>
      </div>
      <router-link
        to="/settings/profile"
        class="flex min-h-[70px] items-center justify-between gap-3 border-t border-zinc-100 px-5 py-3.5 text-zinc-500 transition hover:bg-zinc-50"
      >
        <span class="grid gap-1">
          <strong class="text-xs font-medium text-zinc-800">Profile information</strong>
          <small class="text-[11px] text-zinc-500">Update your name and email address.</small>
        </span>
        <q-icon name="chevron_right" size="20px" />
      </router-link>
      <router-link
        to="/settings/password"
        class="flex min-h-[70px] items-center justify-between gap-3 border-t border-zinc-100 px-5 py-3.5 text-zinc-500 transition hover:bg-zinc-50"
      >
        <span class="grid gap-1">
          <strong class="text-xs font-medium text-zinc-800">Password</strong>
          <small class="text-[11px] text-zinc-500">Change the password you use to sign in.</small>
        </span>
        <q-icon name="chevron_right" size="20px" />
      </router-link>
    </div>
    <p class="mt-3 max-w-[720px] text-[11px] text-zinc-500">
      Keep your account details up to date and use a strong, unique password.
    </p>
  </section>
</template>

<script setup lang="ts">
defineOptions({ name: 'SettingsPage' });
</script>

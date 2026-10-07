<template>
  <div class="flex min-h-screen flex-col p-5 font-sans text-zinc-900 sm:p-6">
    <header class="mx-auto flex min-h-[38px] w-full max-w-6xl items-center justify-between">
      <router-link
        to="/"
        class="inline-flex items-center gap-2 text-[15px] font-semibold tracking-tight"
        aria-label="Task Tracker home"
      >
        <span class="grid size-7 place-items-center rounded-lg bg-zinc-900 text-white"
          ><q-icon name="check" size="17px"
        /></span>
        Task Tracker
      </router-link>
      <router-link :to="backTo" class="text-[13px] text-zinc-500 transition hover:text-zinc-900">{{
        backLabel
      }}</router-link>
    </header>

    <main class="my-auto w-full self-center py-12 sm:max-w-[380px] sm:py-[54px]">
      <div class="mb-7">
        <p class="mb-2.5 text-xs text-zinc-500">{{ kicker }}</p>
        <h1 class="m-0 text-2xl font-semibold leading-tight tracking-tight">{{ title }}</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">{{ description }}</p>
      </div>
      <slot />
    </main>

    <footer
      class="mx-auto flex w-full max-w-6xl items-center justify-between gap-3 text-[13px] text-zinc-500"
    >
      <span>{{ footerText }}</span>
      <router-link class="font-medium text-zinc-800 hover:text-zinc-500" :to="footerLink">{{
        footerLinkText
      }}</router-link>
    </footer>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    kicker: string;
    title: string;
    description: string;
    backTo?: string;
    backLabel?: string;
    footerText: string;
    footerLink: string;
    footerLinkText: string;
  }>(),
  {
    backTo: '/',
    backLabel: 'Back to home',
  },
);
</script>

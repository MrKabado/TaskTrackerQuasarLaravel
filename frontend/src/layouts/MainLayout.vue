<template>
  <div class="min-h-screen bg-white font-sans text-zinc-900">
    <header
      class="mx-auto flex h-[60px] max-w-6xl items-center justify-between border-b border-zinc-200 px-5 sm:px-6"
    >
      <router-link
        to="/"
        class="inline-flex items-center gap-2 text-[15px] font-semibold tracking-tight"
      >
        <span class="grid size-7 place-items-center rounded-lg bg-zinc-900 text-white"
          ><q-icon name="check" size="17px"
        /></span>
        Task Tracker
      </router-link>
      <router-link class="text-[13px] text-zinc-500 hover:text-zinc-900" to="/"
        >Back to home</router-link
      >
    </header>
    <main class="mx-auto max-w-6xl px-5 py-8 sm:px-6">
      <router-view />
    </main>
  </div>
</template>

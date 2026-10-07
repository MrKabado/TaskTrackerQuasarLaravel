<template>
  <main class="grid min-h-screen place-items-center bg-white px-6 py-10 font-sans text-zinc-900">
    <div class="w-full max-w-sm">
      <span class="mb-4 block font-mono text-xs tracking-widest text-zinc-400"
        >404 / PAGE NOT FOUND</span
      >
      <h1 class="m-0 text-2xl font-semibold tracking-tight">That page doesn’t exist.</h1>
      <p class="my-3 mb-6 text-[13px] leading-relaxed text-zinc-500">
        The link may be outdated, or the page may have moved.
      </p>
      <router-link
        to="/"
        class="inline-flex min-h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-xs font-medium text-white transition hover:bg-zinc-700"
        >Back to home</router-link
      >
    </div>
  </main>
</template>

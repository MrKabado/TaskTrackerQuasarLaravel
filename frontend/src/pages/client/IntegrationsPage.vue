<script setup lang="ts">
const extensionUrl = import.meta.env.VITE_CHROME_EXTENSION_URL;
const isStoreListingAvailable = Boolean(extensionUrl);

const features = [
  'View your tasks without opening the website.',
  'Create and manage tasks.',
  'Update task statuses.',
  'Stay connected to your Task Tracker account.',
];
</script>

<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 sm:py-10">
    <header class="mb-7">
      <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Integrations</p>
      <h1 class="heading-page m-0 text-zinc-900">Integrations</h1>
      <h2 class="mt-2 text-sm font-medium text-zinc-700">Available integrations</h2>
      <p class="mt-1 text-[13px] leading-relaxed text-zinc-500">
        Connect external tools to access and manage your tasks more conveniently.
      </p>
    </header>

    <article
      class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm shadow-zinc-900/[0.02]"
    >
      <div class="flex flex-col gap-5 border-b border-zinc-100 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-7">
        <div class="flex min-w-0 items-start gap-4">
          <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-zinc-900 text-white">
            <svg viewBox="0 0 24 24" class="size-6 fill-none stroke-current" aria-hidden="true">
              <path
                d="M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4l-3 3-3-3H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"
                stroke-linejoin="round"
                stroke-width="1.7"
              />
              <path d="m8 11 2.5 2.5L16 8" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" />
            </svg>
          </span>
          <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2.5">
              <h2 class="text-base font-semibold tracking-tight text-zinc-900">
                Task Tracker for Chrome
              </h2>
              <span
                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-medium text-emerald-700"
              >
                <span class="size-1.5 rounded-full bg-emerald-500" />
                Available
              </span>
            </div>
            <p class="mt-2 max-w-2xl text-[13px] leading-relaxed text-zinc-500">
              Access your tasks directly from your Chrome browser using a convenient Side Panel.
            </p>
          </div>
        </div>
        <a
          :href="isStoreListingAvailable ? extensionUrl : '#local-installation'"
          :target="isStoreListingAvailable ? '_blank' : undefined"
          :rel="isStoreListingAvailable ? 'noreferrer' : undefined"
          class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 text-xs font-medium text-white transition hover:bg-zinc-700"
        >
          Get Chrome Extension
          <svg viewBox="0 0 20 20" class="size-4 fill-none stroke-current" aria-hidden="true">
            <path
              d="M11 3h6v6m-1-5-8 8m5-5v8H3V5h8"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
            />
          </svg>
        </a>
      </div>

      <div class="grid gap-8 p-5 sm:p-7 md:grid-cols-[minmax(0,1fr)_minmax(240px,0.65fr)]">
        <section>
          <h3 class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Features</h3>
          <ul class="mt-4 grid gap-3">
            <li v-for="feature in features" :key="feature" class="flex items-start gap-2.5 text-[13px] text-zinc-700">
              <span
                class="mt-0.5 grid size-4 shrink-0 place-items-center rounded-full bg-emerald-50 text-emerald-700"
              >
                <svg viewBox="0 0 16 16" class="size-3 fill-none stroke-current" aria-hidden="true">
                  <path
                    d="m3 8 3 3 7-7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                  />
                </svg>
              </span>
              {{ feature }}
            </li>
          </ul>
        </section>

        <aside class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
          <h3 class="text-xs font-semibold text-zinc-900">Connect your account</h3>
          <p class="mt-2 text-xs leading-relaxed text-zinc-500">
            After installation, open the extension and connect your account.
          </p>
        </aside>
      </div>
    </article>

    <section
      v-if="!isStoreListingAvailable"
      id="local-installation"
      class="mt-6 rounded-xl border border-zinc-200 bg-white p-5 sm:p-6"
    >
      <h2 class="text-sm font-semibold text-zinc-900">Install the development build</h2>
      <p class="mt-1 text-xs leading-relaxed text-zinc-500">
        The Chrome Web Store listing URL has not been configured yet. To install this local build:
      </p>
      <ol class="mt-4 grid gap-2.5 text-xs leading-relaxed text-zinc-600">
        <li><span class="font-medium text-zinc-800">1.</span> Build the extension with <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono">npm run build</code> from the extension folder.</li>
        <li><span class="font-medium text-zinc-800">2.</span> Open <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono">chrome://extensions</code> and enable Developer mode.</li>
        <li><span class="font-medium text-zinc-800">3.</span> Choose “Load unpacked” and select the extension’s <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono">dist</code> folder.</li>
      </ol>
    </section>
  </section>
</template>

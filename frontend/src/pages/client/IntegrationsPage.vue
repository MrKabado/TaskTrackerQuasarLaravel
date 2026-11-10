<script setup lang="ts">
const features = [
  'View your tasks without opening the website.',
  'Create and manage tasks.',
  'Update task statuses.',
  'Stay connected to your TaskFlow account.',
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
          <img
            class="size-12 shrink-0 rounded-xl object-cover"
            src="/favicon.ico"
            alt=""
          />
          <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2.5">
              <h2 class="text-base font-semibold tracking-tight text-zinc-900">
                TaskFlow for Chrome
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
          href="/downloads/taskflow-extension.zip"
          download="taskflow-extension.zip"
          class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 text-xs font-medium text-white transition hover:bg-zinc-700"
        >
          Get Chrome Extension
          <svg viewBox="0 0 20 20" class="size-4 fill-none stroke-current" aria-hidden="true">
            <path
              d="M10 3v10m-4-4 4 4 4-4M4 13v4h12v-4"
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
      id="local-installation"
      class="mt-6 rounded-xl border border-zinc-200 bg-white p-5 sm:p-6"
    >
      <h2 class="text-sm font-semibold text-zinc-900">Install the extension</h2>
      <p class="mt-1 text-xs leading-relaxed text-zinc-500">
        The download contains the built extension files. Extract the ZIP before loading it in Chrome.
      </p>
      <ol class="mt-4 grid gap-2.5 text-xs leading-relaxed text-zinc-600">
        <li><span class="font-medium text-zinc-800">1.</span> Click <span class="font-medium text-zinc-800">Get Chrome Extension</span> and extract the downloaded ZIP.</li>
        <li><span class="font-medium text-zinc-800">2.</span> Open <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono">chrome://extensions</code> and enable Developer mode.</li>
        <li><span class="font-medium text-zinc-800">3.</span> Click “Load unpacked” and select the extracted folder containing <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono">manifest.json</code>.</li>
        <li><span class="font-medium text-zinc-800">4.</span> Open the extension and connect your TaskFlow account.</li>
      </ol>
    </section>
  </section>
</template>

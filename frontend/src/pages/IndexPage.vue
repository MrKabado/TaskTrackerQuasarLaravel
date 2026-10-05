<template>
  <main class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <header class="mx-auto flex h-20 max-w-6xl items-center justify-between px-6 sm:px-8">
      <router-link
        to="/"
        class="flex items-center gap-2.5 text-base font-semibold tracking-tight text-stone-900"
      >
        <span class="grid size-8 place-items-center rounded-full bg-stone-900 text-white">
          <q-icon name="check" size="18px" />
        </span>
        taskflow
      </router-link>

      <nav class="hidden items-center gap-10 text-sm text-stone-500 md:flex">
        <a href="#features" class="transition hover:text-stone-900">Features</a>
        <a href="#how-it-works" class="transition hover:text-stone-900">How it works</a>
      </nav>

      <div class="flex items-center gap-2 sm:gap-4">
        <router-link
          to="/login"
          class="px-3 py-2 text-sm text-stone-600 transition hover:text-stone-900"
        >
          Log in
        </router-link>
        <router-link
          to="/register"
          class="rounded-full bg-stone-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-stone-700"
        >
          Get started
        </router-link>
      </div>
    </header>

    <section
      class="mx-auto grid max-w-6xl items-center gap-16 px-6 pb-24 pt-16 sm:px-8 md:grid-cols-2 md:pb-32 md:pt-24"
    >
      <div class="max-w-md">
        <p class="mb-6 text-sm text-stone-500">A clearer way to get things done</p>
        <h1 class="text-3xl font-semibold leading-[1.2] tracking-tight text-stone-900 sm:text-4xl">
          Your day,<br />
          <span class="text-stone-400">back in focus.</span>
        </h1>
        <p class="mt-6 text-sm leading-6 text-stone-500">
          Plan what matters, keep your tasks moving, and make room for the work you care about.
          Simple tools for a calmer, more focused day.
        </p>
        <div class="mt-10 flex flex-wrap items-center gap-6">
          <router-link
            to="/register"
            class="inline-flex min-h-12 items-center gap-2 rounded-full bg-stone-900 px-6 text-sm font-medium text-white transition hover:bg-stone-700"
          >
            Start organizing for free
            <q-icon name="arrow_forward" size="18px" />
          </router-link>
          <a href="#features" class="text-sm text-stone-500 transition hover:text-stone-900">
            Explore features
          </a>
        </div>
        <p class="mt-12 text-sm text-stone-400">Make progress at your own pace</p>
      </div>

      <div class="mx-auto w-full max-w-md">
        <div class="rounded-3xl border border-stone-200 bg-white p-7 sm:p-8">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-stone-400">Monday, October 5</p>
              <h2 class="mt-1 text-base font-semibold tracking-tight text-stone-900">
                Today’s focus
              </h2>
            </div>
            <span
              class="grid size-9 place-items-center rounded-full bg-stone-100 text-sm font-medium text-stone-600"
              >A</span
            >
          </div>

          <div class="my-7">
            <div class="flex items-center justify-between text-sm">
              <span class="text-stone-500">Daily progress</span>
              <span class="text-stone-900">3 of 5 complete</span>
            </div>
            <div class="mt-3 h-1 overflow-hidden rounded-full bg-stone-100">
              <div class="h-full w-3/5 rounded-full bg-blue-600"></div>
            </div>
            <p class="mt-3 text-xs text-stone-400">You’re building good momentum.</p>
          </div>

          <ul class="divide-y divide-stone-100 border-t border-stone-100">
            <li class="flex items-center gap-3 py-3.5">
              <span class="grid size-5 place-items-center rounded-full bg-blue-600 text-white"
                ><q-icon name="check" size="13px"
              /></span>
              <span class="flex-1 text-sm text-stone-400 line-through">Review project brief</span>
              <span class="text-xs text-stone-400">Work</span>
              <span class="w-10 text-right text-xs tabular-nums text-stone-400">9:00</span>
            </li>
            <li class="flex items-center gap-3 py-3.5">
              <span class="grid size-5 place-items-center rounded-full bg-blue-600 text-white"
                ><q-icon name="check" size="13px"
              /></span>
              <span class="flex-1 text-sm text-stone-400 line-through">Send weekly update</span>
              <span class="text-xs text-stone-400">Team</span>
              <span class="w-10 text-right text-xs tabular-nums text-stone-400">10:30</span>
            </li>
            <li class="flex items-center gap-3 py-3.5">
              <span class="size-5 rounded-full border border-stone-300"></span>
              <span class="flex-1 text-sm text-stone-800">Prepare design feedback</span>
              <span class="text-xs text-stone-400">Focus</span>
              <span class="w-10 text-right text-xs tabular-nums text-stone-400">11:00</span>
            </li>
            <li class="flex items-center gap-3 py-3.5">
              <span class="size-5 rounded-full border border-stone-300"></span>
              <span class="flex-1 text-sm text-stone-800">Take a proper lunch break</span>
              <span class="text-xs text-stone-400">Personal</span>
              <span class="w-10 text-right text-xs tabular-nums text-stone-400">12:30</span>
            </li>
          </ul>
          <p class="mt-4 border-t border-stone-100 pt-5 text-xs text-stone-400">
            One thing at a time. You’ve got this.
          </p>
        </div>
      </div>
    </section>

    <section id="features" class="border-t border-stone-200 px-6 py-24 sm:px-8 md:py-32">
      <div class="mx-auto max-w-6xl">
        <div class="max-w-xl">
          <p class="text-sm text-stone-500">A simpler way to stay on track</p>
          <h2 class="mt-4 text-2xl font-semibold tracking-tight text-stone-900 sm:text-3xl">
            Less juggling. More meaningful progress.
          </h2>
          <p class="mt-4 text-sm leading-6 text-stone-500">
            Keep your plans clear and your next step easy to find.
          </p>
        </div>
        <div class="mt-16 grid gap-12 md:grid-cols-3 md:gap-10">
          <article>
            <q-icon name="checklist" size="24px" class="text-stone-900" />
            <h3 class="mt-5 text-sm font-semibold text-stone-900">A plan you can follow</h3>
            <p class="mt-2 text-sm leading-6 text-stone-500">
              Turn your to-dos into a clear daily list and know exactly where to begin.
            </p>
          </article>
          <article>
            <q-icon name="flag" size="24px" class="text-stone-900" />
            <h3 class="mt-5 text-sm font-semibold text-stone-900">Space for what matters</h3>
            <p class="mt-2 text-sm leading-6 text-stone-500">
              Bring your priorities to the surface and protect time for focused work.
            </p>
          </article>
          <article>
            <q-icon name="trending_up" size="24px" class="text-stone-900" />
            <h3 class="mt-5 text-sm font-semibold text-stone-900">Progress you can see</h3>
            <p class="mt-2 text-sm leading-6 text-stone-500">
              Celebrate completed tasks and build momentum one step at a time.
            </p>
          </article>
        </div>
      </div>
    </section>

    <section id="how-it-works" class="border-t border-stone-200 px-6 py-24 sm:px-8 md:py-32">
      <div
        class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-8 md:flex-row md:items-center"
      >
        <div>
          <p class="text-sm text-stone-500">A fresh start is one click away</p>
          <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-900">
            Ready to find your focus?
          </h2>
          <p class="mt-3 text-sm text-stone-500">
            Make space for the work and life you care about.
          </p>
        </div>
        <router-link
          to="/register"
          class="inline-flex min-h-12 items-center rounded-full bg-stone-900 px-6 text-sm font-medium text-white transition hover:bg-stone-700"
        >
          Create your free account
        </router-link>
      </div>
    </section>

    <footer class="border-t border-stone-200 px-6 py-8 sm:px-8">
      <div
        class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 text-sm text-stone-400 sm:flex-row"
      >
        <router-link to="/" class="flex items-center gap-2 font-semibold text-stone-900">
          <span class="grid size-6 place-items-center rounded-full bg-stone-900 text-white"
            ><q-icon name="check" size="14px"
          /></span>
          taskflow
        </router-link>
        <span>Less overwhelm. More done.</span>
        <div class="flex gap-6">
          <router-link to="/login" class="transition hover:text-stone-900">Log in</router-link>
          <router-link to="/register" class="transition hover:text-stone-900">Sign up</router-link>
        </div>
      </div>
    </footer>
  </main>
</template>
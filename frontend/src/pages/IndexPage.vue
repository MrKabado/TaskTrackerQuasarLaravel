<template>
  <main class="min-h-screen bg-white font-sans text-zinc-900">
    <header class="mx-auto flex h-[62px] max-w-6xl items-center justify-between px-5 sm:px-6">
      <router-link
        to="/"
        class="inline-flex items-center gap-2 text-[15px] font-semibold tracking-tight"
      >
        <span class="grid size-7 place-items-center rounded-lg bg-zinc-900 text-white"
          ><q-icon name="check" size="17px"
        /></span>
        Task Tracker
      </router-link>

      <nav
        class="hidden items-center gap-7 text-xs text-zinc-500 md:flex"
        aria-label="Main navigation"
      >
        <a class="transition hover:text-zinc-900" href="#features">Features</a>
        <a class="transition hover:text-zinc-900" href="#how-it-works">How it works</a>
      </nav>

      <div class="flex items-center gap-4">
        <router-link to="/login" class="text-xs font-medium text-zinc-600 hover:text-zinc-900"
          >Log in</router-link
        >
        <router-link
          to="/register"
          class="inline-flex min-h-9 items-center justify-center rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700"
          >Get started</router-link
        >
      </div>
    </header>

    <section
      class="mx-auto grid max-w-6xl items-center gap-12 px-5 py-16 sm:px-6 md:grid-cols-[1fr_0.9fr] md:gap-16 md:py-24"
    >
      <div class="max-w-[520px]">
        <p class="mb-4 text-xs font-medium text-zinc-500">A clearer way to get things done</p>
        <h1
          class="m-0 text-[clamp(46px,7vw,68px)] font-semibold leading-[1.03] tracking-[-0.065em]"
        >
          Your day,<br />back in focus.
        </h1>
        <p class="mt-5 max-w-[480px] text-sm leading-7 text-zinc-500">
          Plan what matters, keep your tasks moving, and make room for the work you care about.
          Simple tools for a calmer, more focused day.
        </p>
        <div class="mt-7 flex flex-wrap items-center gap-5">
          <router-link
            to="/register"
            class="inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 text-xs font-medium text-white transition hover:bg-zinc-700"
          >
            Start for free
            <q-icon name="arrow_forward" size="16px" />
          </router-link>
          <a href="#features" class="text-xs font-medium text-zinc-600 hover:text-zinc-900"
            >Explore features</a
          >
        </div>
      </div>

      <div
        class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-[0_24px_80px_-40px_rgba(24,24,27,0.28)]"
        aria-label="Preview of a daily task list"
      >
        <div class="flex h-11 items-center justify-between border-b border-zinc-100 px-4">
          <div class="flex gap-1.5" aria-hidden="true">
            <span class="size-2 rounded-full bg-zinc-200"></span
            ><span class="size-2 rounded-full bg-zinc-200"></span
            ><span class="size-2 rounded-full bg-zinc-200"></span>
          </div>
          <span class="font-mono text-[9px] tracking-widest text-zinc-400">MONDAY, OCTOBER 5</span>
          <span></span>
        </div>
        <div class="p-5 sm:p-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="m-0 text-sm font-semibold">Today’s focus</h2>
              <p class="mt-1 text-[11px] text-zinc-400">One step at a time.</p>
            </div>
            <span
              class="grid size-8 place-items-center rounded-full bg-blue-50 text-xs font-semibold text-blue-700"
              >A</span
            >
          </div>

          <div class="my-5">
            <div class="flex items-center justify-between text-[10px]">
              <span class="text-zinc-500">Daily progress</span>
              <strong class="font-medium text-zinc-700">3 of 5 complete</strong>
            </div>
            <div class="mt-2 h-1 overflow-hidden rounded-full bg-zinc-100">
              <span class="block h-full w-3/5 rounded-full bg-zinc-800"></span>
            </div>
          </div>

          <div class="border-t border-zinc-100">
            <div
              class="flex min-h-[47px] items-center gap-2.5 border-b border-zinc-100 text-[11px] text-zinc-400"
            >
              <span
                class="grid size-4 place-items-center rounded-full border border-zinc-800 bg-zinc-800 text-white"
                ><q-icon name="check" size="10px"
              /></span>
              <span class="flex-1 line-through">Review project brief</span
              ><span class="text-[10px]">Work</span><span class="text-[10px]">9:00</span>
            </div>
            <div
              class="flex min-h-[47px] items-center gap-2.5 border-b border-zinc-100 text-[11px] text-zinc-400"
            >
              <span
                class="grid size-4 place-items-center rounded-full border border-zinc-800 bg-zinc-800 text-white"
                ><q-icon name="check" size="10px"
              /></span>
              <span class="flex-1 line-through">Send weekly update</span
              ><span class="text-[10px]">Team</span><span class="text-[10px]">10:30</span>
            </div>
            <div
              class="flex min-h-[47px] items-center gap-2.5 border-b border-zinc-100 text-[11px] text-zinc-700"
            >
              <span class="size-4 rounded-full border border-zinc-300"></span>
              <span class="flex-1">Prepare design feedback</span
              ><span class="text-[10px] text-zinc-400">Focus</span
              ><span class="text-[10px] text-zinc-400">11:00</span>
            </div>
            <div class="flex min-h-[47px] items-center gap-2.5 text-[11px] text-zinc-700">
              <span class="size-4 rounded-full border border-zinc-300"></span>
              <span class="flex-1">Take a proper lunch break</span
              ><span class="text-[10px] text-zinc-400">Personal</span
              ><span class="text-[10px] text-zinc-400">12:30</span>
            </div>
          </div>
          <p class="mt-3 text-[10px] text-zinc-400">A little progress adds up.</p>
        </div>
      </div>
    </section>

    <section id="features" class="border-t border-zinc-200 px-5 py-16 sm:px-6 md:py-20">
      <div class="mx-auto max-w-6xl">
        <p class="mb-3 text-xs text-zinc-500">A simpler way to stay on track</p>
        <h2
          class="m-0 max-w-[620px] text-[clamp(24px,3vw,32px)] font-semibold leading-tight tracking-tight"
        >
          Less juggling. More meaningful progress.
        </h2>
        <p class="mt-3 text-sm leading-relaxed text-zinc-500">
          Keep your plans clear and your next step easy to find.
        </p>

        <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-3 md:gap-9">
          <article>
            <span class="grid size-[34px] place-items-center rounded-lg border border-zinc-200"
              ><q-icon name="checklist" size="18px"
            /></span>
            <h3 class="mt-4 text-sm font-semibold">A plan you can follow</h3>
            <p class="mt-2 max-w-[310px] text-[13px] leading-relaxed text-zinc-500">
              Turn your to-dos into a clear daily list and know exactly where to begin.
            </p>
          </article>
          <article>
            <span class="grid size-[34px] place-items-center rounded-lg border border-zinc-200"
              ><q-icon name="flag" size="18px"
            /></span>
            <h3 class="mt-4 text-sm font-semibold">Space for what matters</h3>
            <p class="mt-2 max-w-[310px] text-[13px] leading-relaxed text-zinc-500">
              Bring your priorities to the surface and protect time for focused work.
            </p>
          </article>
          <article>
            <span class="grid size-[34px] place-items-center rounded-lg border border-zinc-200"
              ><q-icon name="trending_up" size="18px"
            /></span>
            <h3 class="mt-4 text-sm font-semibold">Progress you can see</h3>
            <p class="mt-2 max-w-[310px] text-[13px] leading-relaxed text-zinc-500">
              Celebrate completed tasks and build momentum one step at a time.
            </p>
          </article>
        </div>
      </div>
    </section>

    <section id="how-it-works" class="border-t border-zinc-200 px-5 py-16 sm:px-6 md:py-20">
      <div
        class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 md:flex-row md:items-center"
      >
        <div>
          <p class="mb-3 text-xs text-zinc-500">A fresh start is one click away</p>
          <h2
            class="m-0 max-w-[620px] text-[clamp(24px,3vw,32px)] font-semibold leading-tight tracking-tight"
          >
            Ready to find your focus?
          </h2>
          <p class="mt-3 text-sm leading-relaxed text-zinc-500">
            Make space for the work and life you care about.
          </p>
        </div>
        <router-link
          to="/register"
          class="inline-flex min-h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-xs font-medium text-white transition hover:bg-zinc-700"
          >Create your free account</router-link
        >
      </div>
    </section>

    <footer
      class="flex min-h-[68px] flex-col items-start justify-center gap-3 border-t border-zinc-200 px-5 text-xs text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-6"
    >
      <router-link
        to="/"
        class="inline-flex items-center gap-2 text-[15px] font-semibold tracking-tight text-zinc-900"
      >
        <span class="grid size-7 place-items-center rounded-lg bg-zinc-900 text-white"
          ><q-icon name="check" size="15px"
        /></span>
        Task Tracker
      </router-link>
      <span>Less overwhelm. More done.</span>
      <div class="flex gap-4">
        <router-link class="hover:text-zinc-900" to="/login">Log in</router-link>
        <router-link class="hover:text-zinc-900" to="/register">Sign up</router-link>
      </div>
    </footer>
  </main>
</template>

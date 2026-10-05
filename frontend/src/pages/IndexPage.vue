<template>
  <main class="min-h-screen bg-white text-slate-900">
    <header class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8">
      <router-link
        to="/"
        class="flex items-center gap-2.5 text-xl font-bold tracking-tight text-slate-900"
      >
        <span class="grid size-9 place-items-center rounded-xl bg-blue-600 text-white">
          <q-icon name="check_circle" size="21px" />
        </span>
        taskflow
      </router-link>

      <nav class="hidden items-center gap-9 text-sm font-medium text-slate-500 md:flex">
        <a href="#features" class="transition hover:text-blue-600">Features</a>
        <a href="#how-it-works" class="transition hover:text-blue-600">How it works</a>
      </nav>

      <div class="flex items-center gap-2 sm:gap-4">
        <router-link
          to="/login"
          class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:text-blue-600"
        >
          Log in
        </router-link>
        <router-link
          to="/register"
          class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
          Get started
        </router-link>
      </div>
    </header>

    <section
      class="mx-auto grid max-w-7xl items-center gap-12 px-5 pb-20 pt-12 sm:px-8 md:grid-cols-2 md:gap-8 md:pb-28 md:pt-20"
    >
      <div class="max-w-xl">
        <p
          class="mb-5 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3.5 py-2 text-xs font-semibold uppercase tracking-wider text-blue-700"
        >
          <span class="size-2 rounded-full bg-blue-600"></span>
          A clearer way to get things done
        </p>
        <h1 class="text-5xl font-bold leading-[1.08] tracking-tight text-slate-950 sm:text-6xl">
          Your day,<br />
          <span class="text-blue-600">back in focus.</span>
        </h1>
        <p class="mt-6 max-w-lg text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
          Plan what matters, keep your tasks moving, and make room for the work you care about.
          Simple tools for a calmer, more focused day.
        </p>
        <div class="mt-8 flex flex-wrap items-center gap-4">
          <router-link
            to="/register"
            class="inline-flex min-h-12 items-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
          >
            Start organizing for free
            <q-icon name="arrow_forward" size="18px" />
          </router-link>
          <a
            href="#features"
            class="rounded-xl px-3 py-3 text-sm font-semibold text-slate-600 transition hover:text-blue-600"
          >
            Explore features
          </a>
        </div>
        <div class="mt-10 flex items-center gap-3 text-sm text-slate-500">
          <div class="flex -space-x-2" aria-hidden="true">
            <span
              class="grid size-8 place-items-center rounded-full border-2 border-white bg-blue-100 text-xs font-bold text-blue-700"
              >J</span
            >
            <span
              class="grid size-8 place-items-center rounded-full border-2 border-white bg-sky-100 text-xs font-bold text-sky-700"
              >M</span
            >
            <span
              class="grid size-8 place-items-center rounded-full border-2 border-white bg-indigo-100 text-xs font-bold text-indigo-700"
              >A</span
            >
            <span
              class="grid size-8 place-items-center rounded-full border-2 border-white bg-cyan-100 text-xs font-bold text-cyan-700"
              >K</span
            >
          </div>
          <span>Make progress at your own pace</span>
        </div>
      </div>

      <div class="relative mx-auto w-full max-w-xl py-4">
        <div class="absolute -inset-5 rounded-[2rem] bg-blue-50 sm:-inset-8"></div>
        <div
          class="relative rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-blue-950/5 sm:p-7"
        >
          <div class="flex items-center justify-between border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3">
              <span class="grid size-10 place-items-center rounded-xl bg-blue-50 text-blue-600"
                ><q-icon name="view_kanban" size="21px"
              /></span>
              <div>
                <p class="text-xs font-medium text-slate-400">MONDAY, OCTOBER 5</p>
                <h2 class="mt-1 text-lg font-bold tracking-tight text-slate-900">Today’s focus</h2>
              </div>
            </div>
            <span
              class="grid size-9 place-items-center rounded-full bg-sky-100 text-sm font-bold text-sky-700"
              >A</span
            >
          </div>

          <div class="my-5 rounded-xl bg-blue-50/70 p-4">
            <div class="flex items-center justify-between text-sm">
              <span class="font-medium text-slate-700">Daily progress</span>
              <span class="font-semibold text-blue-700">3 of 5 complete</span>
            </div>
            <div class="mt-3 h-2 overflow-hidden rounded-full bg-blue-100">
              <div class="h-full w-3/5 rounded-full bg-blue-600"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">You’re building good momentum.</p>
          </div>

          <div class="space-y-1">
            <div class="flex items-center gap-3 rounded-lg px-2 py-3">
              <span class="grid size-5 place-items-center rounded-md bg-blue-600 text-white"
                ><q-icon name="check" size="14px"
              /></span>
              <span class="flex-1 text-sm text-slate-400 line-through">Review project brief</span>
              <span class="rounded-md bg-blue-50 px-2 py-1 text-[10px] font-semibold text-blue-700"
                >WORK</span
              >
              <span class="text-xs text-slate-400">9:00</span>
            </div>
            <div class="flex items-center gap-3 rounded-lg px-2 py-3">
              <span class="grid size-5 place-items-center rounded-md bg-blue-600 text-white"
                ><q-icon name="check" size="14px"
              /></span>
              <span class="flex-1 text-sm text-slate-400 line-through">Send weekly update</span>
              <span class="rounded-md bg-sky-50 px-2 py-1 text-[10px] font-semibold text-sky-700"
                >TEAM</span
              >
              <span class="text-xs text-slate-400">10:30</span>
            </div>
            <div class="flex items-center gap-3 rounded-lg px-2 py-3">
              <span class="size-5 rounded-md border border-slate-300"></span>
              <span class="flex-1 text-sm font-medium text-slate-700">Prepare design feedback</span>
              <span
                class="rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-semibold text-indigo-700"
                >FOCUS</span
              >
              <span class="text-xs text-slate-400">11:00</span>
            </div>
            <div class="flex items-center gap-3 rounded-lg px-2 py-3">
              <span class="size-5 rounded-md border border-slate-300"></span>
              <span class="flex-1 text-sm font-medium text-slate-700"
                >Take a proper lunch break</span
              >
              <span class="rounded-md bg-cyan-50 px-2 py-1 text-[10px] font-semibold text-cyan-700"
                >PERSONAL</span
              >
              <span class="text-xs text-slate-400">12:30</span>
            </div>
          </div>
          <div
            class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-4 text-xs text-slate-500"
          >
            <q-icon name="lightbulb" class="text-blue-600" size="17px" />
            One thing at a time. You’ve got this.
          </div>
        </div>
      </div>
    </section>

    <section
      id="features"
      class="border-y border-slate-100 bg-slate-50/70 px-5 py-20 sm:px-8 md:py-24"
    >
      <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-700">
            A simpler way to stay on track
          </p>
          <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
            Less juggling. More meaningful progress.
          </h2>
          <p class="mt-4 text-base leading-7 text-slate-600">
            Keep your plans clear and your next step easy to find.
          </p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-3">
          <article class="rounded-2xl border border-slate-200 bg-white p-6">
            <span class="grid size-11 place-items-center rounded-xl bg-blue-50 text-blue-700"
              ><q-icon name="checklist" size="22px"
            /></span>
            <h3 class="mt-5 text-base font-semibold text-slate-900">A plan you can follow</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">
              Turn your to-dos into a clear daily list and know exactly where to begin.
            </p>
          </article>
          <article class="rounded-2xl border border-slate-200 bg-white p-6">
            <span class="grid size-11 place-items-center rounded-xl bg-sky-50 text-sky-700"
              ><q-icon name="flag" size="22px"
            /></span>
            <h3 class="mt-5 text-base font-semibold text-slate-900">Space for what matters</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">
              Bring your priorities to the surface and protect time for focused work.
            </p>
          </article>
          <article class="rounded-2xl border border-slate-200 bg-white p-6">
            <span class="grid size-11 place-items-center rounded-xl bg-indigo-50 text-indigo-700"
              ><q-icon name="trending_up" size="22px"
            /></span>
            <h3 class="mt-5 text-base font-semibold text-slate-900">Progress you can see</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">
              Celebrate completed tasks and build momentum one step at a time.
            </p>
          </article>
        </div>
      </div>
    </section>

    <section
      id="how-it-works"
      class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-5 py-16 sm:px-8 md:flex-row md:items-center md:py-20"
    >
      <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-700">
          A fresh start is one click away
        </p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
          Ready to find your focus?
        </h2>
        <p class="mt-2 text-sm text-slate-600">Make space for the work and life you care about.</p>
      </div>
      <router-link
        to="/register"
        class="inline-flex min-h-12 items-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
      >
        Create your free account
      </router-link>
    </section>

    <footer class="border-t border-slate-100 px-5 py-6 sm:px-8">
      <div
        class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 text-sm text-slate-500 sm:flex-row"
      >
        <router-link to="/" class="flex items-center gap-2 font-bold text-slate-800">
          <span class="grid size-7 place-items-center rounded-lg bg-blue-600 text-white"
            ><q-icon name="check_circle" size="17px"
          /></span>
          taskflow
        </router-link>
        <span>Less overwhelm. More done.</span>
        <div class="flex gap-5">
          <router-link to="/login" class="transition hover:text-blue-600">Log in</router-link>
          <router-link to="/register" class="transition hover:text-blue-600">Sign up</router-link>
        </div>
      </div>
    </footer>
  </main>
</template>

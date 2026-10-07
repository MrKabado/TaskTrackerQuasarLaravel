<template>
  <main class="min-h-screen bg-white font-sans text-zinc-900">
    <!-- HEADER -->
    <header class="border-b border-zinc-200 px-5 sm:px-6 max-[520px]:h-[58px]">
      <div
        class="mx-auto flex h-16 max-w-[1120px] items-center justify-between max-[520px]:h-[58px]"
      >
        <router-link
          to="/"
          class="inline-flex items-center gap-[9px] text-[15px] font-semibold tracking-[-0.04em]"
        >
          <span class="grid size-7 place-items-center rounded-[7px] bg-zinc-900 text-white"
            ><q-icon name="check" size="17px"
          /></span>
          Task Tracker
        </router-link>

        <nav
          class="hidden items-center gap-6 text-[13px] text-zinc-500 md:flex"
          aria-label="Main navigation"
        >
          <a class="transition hover:text-zinc-900" href="#features">Features</a>
          <a class="transition hover:text-zinc-900" href="#how-it-works">How it works</a>
        </nav>

        <div class="flex items-center gap-4 text-[13px]">
          <router-link to="/login" class="text-zinc-600 transition hover:text-zinc-900"
            >Log in</router-link
          >
          <router-link
            to="/register"
            class="inline-flex min-h-[38px] items-center justify-center rounded-lg bg-zinc-900 px-3.5 text-[13px] font-medium text-white transition hover:bg-zinc-700 max-[520px]:min-h-[34px] max-[520px]:px-3"
            >Get started</router-link
          >
        </div>
      </div>
    </header>

    <section
      class="mx-auto grid max-w-[1120px] items-center gap-11 px-5 pt-[72px] pb-[76px] sm:px-6 min-[761px]:grid-cols-[minmax(0,0.9fr)_minmax(420px,1.1fr)] min-[761px]:gap-16 min-[761px]:pt-[100px] min-[761px]:pb-[112px] max-[520px]:px-5 max-[520px]:pt-[58px] max-[520px]:pb-16"
    >
      <div class="max-w-[440px]">
        <p
          class="mb-[22px] inline-flex items-center gap-2 text-[13px] text-zinc-600 before:size-[7px] before:shrink-0 before:rounded-full before:bg-zinc-900 before:content-['']"
        >
          A clearer way to get things done
        </p>
        <h1 class="heading-display">
          Your day,<br />back in focus.
        </h1>
        <p
          class="mt-[22px] max-w-[400px] text-[15px] leading-[1.75] text-zinc-500 max-[520px]:text-sm"
        >
          Plan what matters, keep your tasks moving, and make room for the work you care about.
          Simple tools for a calmer, more focused day.
        </p>
        <div class="mt-[30px] flex flex-wrap items-center gap-[18px]">
          <router-link
            to="/register"
            class="inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-[13px] font-medium text-white transition hover:bg-zinc-700"
          >
            Start for free
            <q-icon name="arrow_forward" size="16px" />
          </router-link>
          <a href="#features" class="text-[13px] text-zinc-600 hover:text-zinc-900"
            >Explore features</a
          >
        </div>
      </div>

      <div
        class="overflow-hidden rounded-[10px] border border-zinc-200 bg-white shadow-[0_18px_48px_rgba(0,0,0,0.05)]"
        aria-label="Preview of a daily task list"
      >
        <div class="flex h-12 items-center justify-between border-b border-zinc-100 px-[18px]">
          <div class="flex gap-[5px]" aria-hidden="true">
            <span class="size-[7px] rounded-full bg-zinc-200"></span
            ><span class="size-[7px] rounded-full bg-zinc-200"></span
            ><span class="size-[7px] rounded-full bg-zinc-200"></span>
          </div>
          <span class="text-[11px] text-zinc-400">MONDAY, OCTOBER 5</span>
          <span></span>
        </div>
        <div class="p-[22px] max-[520px]:px-[14px]">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="m-0 text-base font-semibold tracking-tight">Today’s focus</h2>
              <p class="mt-1 text-xs text-zinc-500">One step at a time.</p>
            </div>
            <span
              class="grid size-[30px] place-items-center rounded-full bg-zinc-100 text-[11px] font-medium text-zinc-600"
              >A</span
            >
          </div>

          <div class="my-[22px] mb-[14px]">
            <div class="mt-1 flex justify-between text-xs text-zinc-500">
              <span class="text-zinc-500">Daily progress</span>
              <strong class="font-medium text-zinc-700">3 of 5 complete</strong>
            </div>
            <div class="mt-[9px] h-1 overflow-hidden rounded-full bg-zinc-100">
              <span class="block h-full w-3/5 rounded-full bg-zinc-900"></span>
            </div>
          </div>

          <div class="border-t border-zinc-100">
            <div
              class="flex min-h-[47px] items-center gap-2.5 border-b border-zinc-100 text-xs text-zinc-400 last:border-0"
            >
              <span
                class="grid size-4 shrink-0 place-items-center rounded-full border border-zinc-900 bg-zinc-900 text-white"
                ><q-icon name="check" size="10px"
              /></span>
              <span class="flex-1 line-through">Review project brief</span
              ><span class="text-[11px]">Work</span><span class="text-[11px]">9:00</span>
            </div>
            <div
              class="flex min-h-[47px] items-center gap-2.5 border-b border-zinc-100 text-xs text-zinc-400"
            >
              <span
                class="grid size-4 shrink-0 place-items-center rounded-full border border-zinc-900 bg-zinc-900 text-white"
                ><q-icon name="check" size="10px"
              /></span>
              <span class="flex-1 line-through">Send weekly update</span
              ><span class="text-[11px]">Team</span><span class="text-[11px]">10:30</span>
            </div>
            <div
              class="flex min-h-[47px] items-center gap-2.5 border-b border-zinc-100 text-xs text-zinc-700"
            >
              <span class="size-4 rounded-full border border-zinc-300"></span>
              <span class="flex-1">Prepare design feedback</span
              ><span class="text-[11px] text-zinc-400">Focus</span
              ><span class="text-[11px] text-zinc-400">11:00</span>
            </div>
            <div class="flex min-h-[47px] items-center gap-2.5 text-xs text-zinc-700">
              <span class="size-4 rounded-full border border-zinc-300"></span>
              <span class="flex-1">Take a proper lunch break</span
              ><span class="text-[11px] text-zinc-400">Personal</span
              ><span class="text-[11px] text-zinc-400">12:30</span>
            </div>
          </div>
          <p class="mt-3 text-[11px] text-zinc-400">A little progress adds up.</p>
        </div>
      </div>
    </section>

    <section id="features" class="border-t border-zinc-200 px-6 py-[76px] max-[760px]:py-14">
      <div class="mx-auto max-w-[1120px]">
        <p class="mb-3 text-[13px] text-zinc-500">A simpler way to stay on track</p>
        <h2
          class="heading-section m-0 max-w-[620px]"
        >
          Less juggling. More meaningful progress.
        </h2>
        <p class="mt-3 text-sm leading-relaxed text-zinc-500">
          Keep your plans clear and your next step easy to find.
        </p>

        <div
          class="mt-9 grid grid-cols-1 gap-7 min-[761px]:mt-12 min-[761px]:grid-cols-3 min-[761px]:gap-9"
        >
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

    <section id="how-it-works" class="border-t border-zinc-200 px-6 py-[76px] max-[760px]:py-14">
      <div
        class="mx-auto flex max-w-[1120px] flex-col items-start justify-between gap-6 min-[761px]:flex-row min-[761px]:items-center"
      >
        <div>
          <p class="mb-3 text-[13px] text-zinc-500">A fresh start is one click away</p>
          <h2
            class="heading-section m-0 max-w-[620px]"
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

    <footer class="border-t border-zinc-200 px-5 text-xs text-zinc-500 sm:px-6">
      <div
        class="mx-auto flex min-h-[68px] max-w-[1120px] flex-col items-start justify-center gap-2.5 min-[521px]:flex-row min-[521px]:items-center min-[521px]:justify-between min-[521px]:gap-0"
      >
        <router-link
          to="/"
          class="inline-flex items-center gap-2 text-[15px] font-semibold tracking-tight text-zinc-900"
        >
          <span class="grid size-7 place-items-center rounded-[7px] bg-zinc-900 text-white"
            ><q-icon name="check" size="15px"
          /></span>
          Task Tracker
        </router-link>
        <span>Less overwhelm. More done.</span>
        <div class="flex gap-[18px]">
          <router-link class="hover:text-zinc-900" to="/login">Log in</router-link>
          <router-link class="hover:text-zinc-900" to="/register">Sign up</router-link>
        </div>
      </div>
    </footer>
  </main>
</template>

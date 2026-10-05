<template>
  <main class="grid min-h-screen bg-white lg:grid-cols-2">
    <section
      class="relative flex min-h-64 items-end overflow-hidden bg-blue-950 p-7 sm:min-h-80 sm:p-10 lg:min-h-screen lg:p-14"
    >
      <img
        src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1800&q=85"
        alt="A bright, calm workspace with a desk and office chairs"
        class="absolute inset-0 size-full object-cover"
      />
      <div
        class="absolute inset-0 bg-gradient-to-br from-blue-950/80 via-blue-900/55 to-sky-800/40"
      ></div>
      <div class="relative z-10 max-w-xl text-white">
        <router-link
          to="/"
          class="mb-12 inline-flex items-center gap-2.5 text-xl font-bold tracking-tight text-white lg:mb-0 lg:absolute lg:bottom-[calc(100%+2.5rem)] lg:left-0"
        >
          <span class="grid size-9 place-items-center rounded-xl bg-white/15 ring-1 ring-white/20"
            ><q-icon name="check_circle" size="21px"
          /></span>
          taskflow
        </router-link>
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">WELCOME BACK</p>
        <h1 class="mt-4 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
          A clearer day<br class="hidden sm:block" />
          starts here.
        </h1>
        <p class="mt-4 max-w-md text-sm leading-6 text-blue-50 sm:text-base sm:leading-7">
          Pick up where you left off and keep your important work moving forward.
        </p>
      </div>
    </section>

    <section class="flex items-center justify-center px-5 py-12 sm:px-10 lg:px-14">
      <div class="w-full max-w-md">
        <router-link
          to="/"
          class="mb-9 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600"
        >
          <q-icon name="arrow_back" size="17px" />
          Back to home
        </router-link>
        <div class="mb-8">
          <h2 class="text-3xl font-bold tracking-tight text-slate-950">Log in to Taskflow</h2>
          <p class="mt-2 text-sm text-slate-500">Welcome back. Enter your details below.</p>
        </div>

        <form class="space-y-5" @submit.prevent="showDemoMessage">
          <div>
            <label for="login-email" class="mb-2 block text-sm font-medium text-slate-700"
              >Email address</label
            >
            <input
              id="login-email"
              v-model="email"
              type="email"
              autocomplete="email"
              required
              placeholder="you@example.com"
              class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            />
          </div>
          <div>
            <div class="mb-2 flex items-center justify-between">
              <label for="login-password" class="block text-sm font-medium text-slate-700"
                >Password</label
              >
              <a
                href="#"
                class="text-xs font-semibold text-blue-700 hover:text-blue-800"
                @click.prevent
                >Forgot password?</a
              >
            </div>
            <input
              id="login-password"
              v-model="password"
              type="password"
              autocomplete="current-password"
              required
              placeholder="Enter your password"
              class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            />
          </div>
          <label class="flex items-center gap-2 text-sm text-slate-600">
            <input
              v-model="remember"
              type="checkbox"
              class="size-4 rounded border-slate-300 accent-blue-600 focus:ring-blue-500"
            />
            Remember me
          </label>
          <button
            type="submit"
            class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
          >
            Log in
          </button>
          <p
            v-if="message"
            role="status"
            class="rounded-lg bg-blue-50 px-3 py-2 text-sm text-blue-800"
          >
            {{ message }}
          </p>
        </form>

        <p class="mt-7 text-center text-sm text-slate-500">
          Don’t have an account?
          <router-link to="/register" class="font-semibold text-blue-700 hover:text-blue-800"
            >Create an account</router-link
          >
        </p>
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
import { ref } from 'vue';

defineOptions({ name: 'LoginPage' });

const email = ref('');
const password = ref('');
const remember = ref(false);
const message = ref('');

function showDemoMessage() {
  message.value = 'Login is not connected to an authentication service yet.';
}
</script>

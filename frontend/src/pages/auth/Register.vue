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
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">A FRESH START</p>
        <h1 class="mt-4 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
          Make space for<br class="hidden sm:block" />
          what matters.
        </h1>
        <p class="mt-4 max-w-md text-sm leading-6 text-blue-50 sm:text-base sm:leading-7">
          Organize your tasks, find your focus, and celebrate the small wins along the way.
        </p>
      </div>
    </section>

    <section class="flex items-center justify-center px-5 py-10 sm:px-10 lg:px-14">
      <div class="w-full max-w-md">
        <router-link
          to="/"
          class="mb-7 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600"
        >
          <q-icon name="arrow_back" size="17px" />
          Back to home
        </router-link>
        <div class="mb-6">
          <h2 class="text-3xl font-bold tracking-tight text-slate-950">Create your account</h2>
          <p class="mt-2 text-sm text-slate-500">Start organizing your day with Taskflow.</p>
        </div>

        <form class="space-y-4" @submit.prevent="submitRegistration">
          <div>
            <label for="register-name" class="mb-1.5 block text-sm font-medium text-slate-700"
              >Your name</label
            >
            <input
              id="register-name"
              v-model="name"
              type="text"
              autocomplete="name"
              required
              placeholder="Alex Morgan"
              class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            />
          </div>
          <div>
            <label for="register-email" class="mb-1.5 block text-sm font-medium text-slate-700"
              >Email address</label
            >
            <input
              id="register-email"
              v-model="email"
              type="email"
              autocomplete="email"
              required
              placeholder="you@example.com"
              class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            />
          </div>
          <div>
            <label for="register-otp" class="mb-1.5 block text-sm font-medium text-slate-700"
              >Email verification code</label
            >
            <div class="flex gap-2">
              <input
                id="register-otp"
                v-model="otp"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                pattern="[0-9]{6}"
                maxlength="6"
                required
                placeholder="6-digit code"
                class="block min-w-0 flex-1 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
              />
              <button
                type="button"
                :disabled="isSendingOtp || otpCooldown > 0"
                class="shrink-0 rounded-xl border border-blue-200 bg-blue-50 px-3 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 sm:px-4 sm:text-sm"
                @click="sendOtp"
              >
                {{ otpButtonLabel }}
              </button>
            </div>
          </div>
          <div>
            <label for="register-password" class="mb-1.5 block text-sm font-medium text-slate-700"
              >Password</label
            >
            <input
              id="register-password"
              v-model="password"
              type="password"
              autocomplete="new-password"
              minlength="8"
              required
              placeholder="At least 8 characters"
              class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            />
          </div>
          <div>
            <label
              for="register-password-confirmation"
              class="mb-1.5 block text-sm font-medium text-slate-700"
              >Confirm password</label
            >
            <input
              id="register-password-confirmation"
              v-model="passwordConfirmation"
              type="password"
              autocomplete="new-password"
              minlength="8"
              required
              placeholder="Enter your password again"
              class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            />
          </div>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
          >
            {{ isSubmitting ? 'Creating account…' : 'Create free account' }}
          </button>
          <p
            v-if="message"
            :role="isError ? 'alert' : 'status'"
            :class="isError ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-800'"
            class="rounded-lg px-3 py-2 text-sm"
          >
            {{ message }}
          </p>
        </form>

        <p class="mt-4 text-center text-xs leading-5 text-slate-500">
          By creating an account, you agree to our terms and privacy policy.
        </p>
        <p class="mt-5 text-center text-sm text-slate-500">
          Already have an account?
          <router-link to="/login" class="font-semibold text-blue-700 hover:text-blue-800"
            >Log in</router-link
          >
        </p>
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
import { computed, onUnmounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { getApiErrorMessage, register, requestRegisterOtp, storeAuthToken } from '@/services/auth';

defineOptions({ name: 'RegisterPage' });

const name = ref('');
const email = ref('');
const otp = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const message = ref('');
const isError = ref(false);
const isSendingOtp = ref(false);
const isSubmitting = ref(false);
const otpCooldown = ref(0);
const router = useRouter();
let otpCooldownTimer: ReturnType<typeof setInterval> | undefined;

onUnmounted(() => {
  if (otpCooldownTimer) clearInterval(otpCooldownTimer);
});

const otpButtonLabel = computed(() => {
  if (isSendingOtp.value) return 'Sending…';
  if (otpCooldown.value > 0) return `Wait ${otpCooldown.value}s`;
  return 'Send OTP';
});

async function sendOtp() {
  if (!email.value.trim()) {
    isError.value = true;
    message.value = 'Enter your email address before requesting an OTP.';
    return;
  }

  isSendingOtp.value = true;
  message.value = '';
  isError.value = false;

  try {
    message.value = await requestRegisterOtp(email.value.trim());
    otpCooldown.value = 60;
    otpCooldownTimer = setInterval(() => {
      otpCooldown.value -= 1;
      if (otpCooldown.value <= 0 && otpCooldownTimer) {
        clearInterval(otpCooldownTimer);
        otpCooldownTimer = undefined;
      }
    }, 1000);
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isSendingOtp.value = false;
  }
}

async function submitRegistration() {
  if (password.value !== passwordConfirmation.value) {
    isError.value = true;
    message.value = 'The password confirmation does not match.';
    return;
  }

  isSubmitting.value = true;
  message.value = '';
  isError.value = false;

  try {
    const response = await register({
      name: name.value.trim(),
      email: email.value.trim(),
      otp: otp.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });

    storeAuthToken(response.token, true);
    await router.push('/dashboard');
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isSubmitting.value = false;
  }
}
</script>

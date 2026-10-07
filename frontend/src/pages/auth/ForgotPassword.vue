<template>
  <AuthShell
    kicker="Account recovery"
    title="Reset your password"
    description="We'll email you a one-time code to verify it's you."
    back-to="/login"
    back-label="Back to login"
    footer-text="Remember your password?"
    footer-link="/login"
    footer-link-text="Log in"
  >
    <form class="grid gap-[18px]" @submit.prevent="submitReset">
      <div class="grid min-w-0 gap-2">
        <label for="reset-email" class="text-[13px] font-medium text-zinc-700">Email address</label>
        <input
          id="reset-email"
          v-model="email"
          class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
          type="email"
          autocomplete="email"
          required
          placeholder="you@example.com"
        />
      </div>

      <button
        type="button"
        class="inline-flex min-h-[38px] items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-white px-3.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="isSendingOtp || otpCooldown > 0"
        @click="sendOtp"
      >
        {{ otpButtonLabel }}
      </button>

      <div class="grid min-w-0 gap-2">
        <label for="reset-otp" class="text-[13px] font-medium text-zinc-700"
          >Verification code</label
        >
        <input
          id="reset-otp"
          v-model="otp"
          class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
          type="text"
          inputmode="numeric"
          autocomplete="one-time-code"
          pattern="[0-9]{6}"
          maxlength="6"
          required
          placeholder="Enter the 6-digit code"
        />
      </div>

      <div class="grid min-w-0 gap-2">
        <label for="reset-password" class="text-[13px] font-medium text-zinc-700"
          >New password</label
        >
        <input
          id="reset-password"
          v-model="password"
          class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
          type="password"
          autocomplete="new-password"
          minlength="8"
          required
          placeholder="At least 8 characters"
        />
      </div>

      <div class="grid min-w-0 gap-2">
        <label for="reset-password-confirmation" class="text-[13px] font-medium text-zinc-700"
          >Confirm new password</label
        >
        <input
          id="reset-password-confirmation"
          v-model="passwordConfirmation"
          class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
          type="password"
          autocomplete="new-password"
          minlength="8"
          required
          placeholder="Enter your new password again"
        />
      </div>

      <button
        type="submit"
        class="inline-flex min-h-[38px] w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-[13px] font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="isSubmitting"
      >
        {{ isSubmitting ? 'Resetting password…' : 'Reset password' }}
      </button>

      <p
        v-if="message"
        :role="isError ? 'alert' : 'status'"
        :class="[
          'rounded-lg border px-3 py-2.5 text-[13px] leading-relaxed',
          isError
            ? 'border-red-200 bg-red-50 text-red-700'
            : 'border-green-200 bg-green-50 text-green-800',
        ]"
      >
        {{ message }}
      </p>

      <router-link
        v-if="resetComplete"
        to="/login"
        class="text-xs font-medium text-zinc-700 hover:text-zinc-500"
      >
        Continue to login
      </router-link>
    </form>
  </AuthShell>
</template>

<script setup lang="ts">
import { computed, onUnmounted, ref } from 'vue';
import { getApiErrorMessage, requestPasswordResetOtp, resetPassword } from '@/services/auth';
import AuthShell from '@/components/AuthShell.vue';

defineOptions({ name: 'ForgotPasswordPage' });

const email = ref('');
const otp = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const message = ref('');
const isError = ref(false);
const isSendingOtp = ref(false);
const isSubmitting = ref(false);
const resetComplete = ref(false);
const otpCooldown = ref(0);
let otpCooldownTimer: ReturnType<typeof setInterval> | undefined;

const otpButtonLabel = computed(() => {
  if (isSendingOtp.value) return 'Sending code…';
  if (otpCooldown.value > 0) return `Try again in ${otpCooldown.value}s`;
  return 'Send verification code';
});

onUnmounted(() => {
  if (otpCooldownTimer) clearInterval(otpCooldownTimer);
});

async function sendOtp() {
  if (!email.value.trim()) {
    isError.value = true;
    message.value = 'Enter your email address before requesting a code.';
    return;
  }

  isSendingOtp.value = true;
  message.value = '';
  isError.value = false;

  try {
    message.value = await requestPasswordResetOtp(email.value.trim());
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

async function submitReset() {
  if (password.value !== passwordConfirmation.value) {
    isError.value = true;
    message.value = 'The password confirmation does not match.';
    return;
  }

  isSubmitting.value = true;
  message.value = '';
  isError.value = false;
  resetComplete.value = false;

  try {
    message.value = await resetPassword({
      email: email.value.trim(),
      otp: otp.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });
    resetComplete.value = true;
  } catch (error: unknown) {
    isError.value = true;
    message.value = getApiErrorMessage(error);
  } finally {
    isSubmitting.value = false;
  }
}
</script>

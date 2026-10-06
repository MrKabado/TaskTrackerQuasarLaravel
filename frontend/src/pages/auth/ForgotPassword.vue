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
    <form class="auth-form" @submit.prevent="submitReset">
      <div class="form-field">
        <label for="reset-email" class="form-label">Email address</label>
        <input
          id="reset-email"
          v-model="email"
          class="form-input"
          type="email"
          autocomplete="email"
          required
          placeholder="you@example.com"
        />
      </div>

      <button
        type="button"
        class="button-secondary"
        :disabled="isSendingOtp || otpCooldown > 0"
        @click="sendOtp"
      >
        {{ otpButtonLabel }}
      </button>

      <div class="form-field">
        <label for="reset-otp" class="form-label">Verification code</label>
        <input
          id="reset-otp"
          v-model="otp"
          class="form-input"
          type="text"
          inputmode="numeric"
          autocomplete="one-time-code"
          pattern="[0-9]{6}"
          maxlength="6"
          required
          placeholder="Enter the 6-digit code"
        />
      </div>

      <div class="form-field">
        <label for="reset-password" class="form-label">New password</label>
        <input
          id="reset-password"
          v-model="password"
          class="form-input"
          type="password"
          autocomplete="new-password"
          minlength="8"
          required
          placeholder="At least 8 characters"
        />
      </div>

      <div class="form-field">
        <label for="reset-password-confirmation" class="form-label">Confirm new password</label>
        <input
          id="reset-password-confirmation"
          v-model="passwordConfirmation"
          class="form-input"
          type="password"
          autocomplete="new-password"
          minlength="8"
          required
          placeholder="Enter your new password again"
        />
      </div>

      <button type="submit" class="button-primary" :disabled="isSubmitting">
        {{ isSubmitting ? 'Resetting password…' : 'Reset password' }}
      </button>

      <p
        v-if="message"
        :role="isError ? 'alert' : 'status'"
        :class="['form-notice', { 'is-error': isError, 'is-success': !isError }]"
      >
        {{ message }}
      </p>

      <router-link v-if="resetComplete" to="/login" class="auth-link">
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

<template>
  <AuthShell
    kicker="Get started"
    title="Create an account"
    description="A calmer, clearer way to keep your day moving."
    footer-text="Already have an account?"
    footer-link="/login"
    footer-link-text="Log in"
  >
    <form class="auth-form" @submit.prevent="submitRegistration">
      <div class="form-field">
        <label for="register-name" class="form-label">Your name</label>
        <input
          id="register-name"
          v-model="name"
          class="form-input"
          type="text"
          autocomplete="name"
          required
          placeholder="Alex Morgan"
        />
      </div>

      <div class="form-field">
        <label for="register-email" class="form-label">Email address</label>
        <input
          id="register-email"
          v-model="email"
          class="form-input"
          type="email"
          autocomplete="email"
          required
          placeholder="you@example.com"
        />
      </div>

      <div class="form-field">
        <label for="register-otp" class="form-label">Email verification code</label>
        <div class="otp-row">
          <input
            id="register-otp"
            v-model="otp"
            class="form-input"
            type="text"
            inputmode="numeric"
            autocomplete="one-time-code"
            pattern="[0-9]{6}"
            maxlength="6"
            required
            placeholder="6-digit code"
          />
          <button
            type="button"
            class="button-secondary"
            :disabled="isSendingOtp || otpCooldown > 0"
            @click="sendOtp"
          >
            {{ otpButtonLabel }}
          </button>
        </div>
      </div>

      <div class="form-field">
        <label for="register-password" class="form-label">Password</label>
        <input
          id="register-password"
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
        <label for="register-password-confirmation" class="form-label">Confirm password</label>
        <input
          id="register-password-confirmation"
          v-model="passwordConfirmation"
          class="form-input"
          type="password"
          autocomplete="new-password"
          minlength="8"
          required
          placeholder="Enter your password again"
        />
      </div>

      <button type="submit" class="button-primary" :disabled="isSubmitting">
        {{ isSubmitting ? 'Creating account…' : 'Create account' }}
      </button>

      <p
        v-if="message"
        :role="isError ? 'alert' : 'status'"
        :class="['form-notice', { 'is-error': isError, 'is-success': !isError }]"
      >
        {{ message }}
      </p>
    </form>

    <p class="auth-terms">By creating an account, you agree to our terms and privacy policy.</p>
  </AuthShell>
</template>

<script setup lang="ts">
import { computed, onUnmounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { getApiErrorMessage, register, requestRegisterOtp, storeAuthToken } from '@/services/auth';
import AuthShell from '@/components/AuthShell.vue';

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

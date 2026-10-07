<template>
  <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
    <div class="mb-7 flex items-start justify-between gap-5">
      <div>
        <p class="mb-2.5 text-[11px] font-medium text-zinc-400">Workspace / Profile</p>
        <h1 class="heading-page m-0 text-zinc-900">Profile</h1>
        <p class="mt-2 text-[13px] leading-relaxed text-zinc-500">
          Manage your account details and password.
        </p>
      </div>
    </div>

    <div class="grid max-w-[640px] gap-5">
      <p v-if="isLoading" class="px-5 py-16 text-center text-sm text-zinc-500" role="status">
        Loading profile…
      </p>
      <template v-else>
        <form
          class="grid gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm shadow-zinc-900/[0.02] sm:p-6"
          @submit.prevent="saveProfile"
        >
          <div>
            <h2 class="text-base font-semibold text-zinc-900">Personal information</h2>
            <p class="mt-1 text-xs text-zinc-500">Update the name and email on your account.</p>
          </div>
          <div class="grid min-w-0 gap-2">
            <label for="profile-name" class="text-xs font-medium text-zinc-700">Name</label>
            <input
              id="profile-name"
              v-model="name"
              class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
              autocomplete="name"
              required
            />
          </div>
          <div class="grid min-w-0 gap-2">
            <label for="profile-email" class="text-xs font-medium text-zinc-700"
              >Email address</label
            >
            <div class="grid grid-cols-[minmax(0,1fr)_auto] gap-2">
              <input
                id="profile-email"
                v-model="email"
                class="h-[38px] w-full min-w-0 rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                type="email"
                autocomplete="email"
                required
                @input="onEmailInput"
              />
              <button
                class="inline-flex min-h-[38px] items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-white px-3.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50 disabled:cursor-not-allowed disabled:opacity-60"
                type="button"
                :disabled="!canRequestEmailOtp"
                @click="sendEmailOtp"
              >
                {{ otpButtonLabel }}
              </button>
            </div>
          </div>
          <div v-if="otpSentForEmail === email.trim()" class="grid min-w-0 gap-2">
            <label for="profile-email-otp" class="text-xs font-medium text-zinc-700"
              >Email verification code</label
            >
            <input
              id="profile-email-otp"
              v-model="emailOtp"
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
          <button
            class="inline-flex min-h-[38px] w-fit items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
            type="submit"
            :disabled="isSaving"
          >
            {{ isSaving ? 'Saving…' : 'Save changes' }}
          </button>
          <p
            v-if="profileMessage"
            :class="[
              'rounded-lg border px-3 py-2.5 text-xs leading-relaxed',
              isProfileError
                ? 'border-red-200 bg-red-50 text-red-700'
                : 'border-green-200 bg-green-50 text-green-800',
            ]"
            role="status"
          >
            {{ profileMessage }}
          </p>
        </form>

        <section
          class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm shadow-zinc-900/[0.02] sm:p-6"
        >
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
              <h2 class="text-base font-semibold text-zinc-900">Password</h2>
              <p class="mt-1 text-xs text-zinc-500">
                Keep your account secure with a strong password.
              </p>
            </div>
            <button
              class="inline-flex min-h-[38px] items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-white px-3.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50"
              type="button"
              :aria-expanded="isPasswordFormOpen"
              @click="togglePasswordForm"
            >
              {{ isPasswordFormOpen ? 'Cancel' : 'Change password' }}
            </button>
          </div>

          <form v-if="isPasswordFormOpen" class="mt-5 grid gap-4" @submit.prevent="savePassword">
            <div class="grid min-w-0 gap-2">
              <label for="current-password" class="text-xs font-medium text-zinc-700"
                >Current password</label
              >
              <input
                id="current-password"
                v-model="currentPassword"
                class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                type="password"
                autocomplete="current-password"
                required
              />
            </div>
            <div class="grid min-w-0 gap-2">
              <label for="new-password" class="text-xs font-medium text-zinc-700"
                >New password</label
              >
              <input
                id="new-password"
                v-model="password"
                class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                type="password"
                autocomplete="new-password"
                minlength="8"
                required
              />
            </div>
            <div class="grid min-w-0 gap-2">
              <label for="confirm-password" class="text-xs font-medium text-zinc-700"
                >Confirm new password</label
              >
              <input
                id="confirm-password"
                v-model="passwordConfirmation"
                class="h-[38px] w-full rounded-lg border border-zinc-200 bg-white px-3 text-[13px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-zinc-400 focus:ring-4 focus:ring-zinc-900/5"
                type="password"
                autocomplete="new-password"
                minlength="8"
                required
              />
            </div>
            <button
              class="inline-flex min-h-[38px] w-fit items-center justify-center gap-2 rounded-lg bg-zinc-900 px-3.5 text-xs font-medium text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60"
              type="submit"
              :disabled="isChangingPassword"
            >
              {{ isChangingPassword ? 'Updating…' : 'Update password' }}
            </button>
            <p
              v-if="passwordMessage"
              :class="[
                'rounded-lg border px-3 py-2.5 text-xs leading-relaxed',
                isPasswordError
                  ? 'border-red-200 bg-red-50 text-red-700'
                  : 'border-green-200 bg-green-50 text-green-800',
              ]"
              role="status"
            >
              {{ passwordMessage }}
            </p>
          </form>
        </section>
      </template>
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import {
  changePassword,
  getApiErrorMessage,
  getCurrentUser,
  requestProfileEmailOtp,
  updateProfile,
} from '@/services/auth';

defineOptions({ name: 'ProfilePage' });

const route = useRoute();
const name = ref('');
const email = ref('');
const originalEmail = ref('');
const emailOtp = ref('');
const otpSentForEmail = ref('');
const isSendingOtp = ref(false);
const otpCooldown = ref(0);
const otpCooldownForEmail = ref('');
const isLoading = ref(true);
const isSaving = ref(false);
const isProfileError = ref(false);
const profileMessage = ref('');
const isPasswordFormOpen = ref(false);
const currentPassword = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const isChangingPassword = ref(false);
const isPasswordError = ref(false);
const passwordMessage = ref('');
let otpCooldownTimer: ReturnType<typeof setInterval> | undefined;

const canRequestEmailOtp = computed(
  () =>
    !isLoading.value &&
    !isSendingOtp.value &&
    (otpCooldown.value === 0 || otpCooldownForEmail.value !== email.value.trim()) &&
    email.value.trim() !== '' &&
    email.value.trim() !== originalEmail.value,
);
const otpButtonLabel = computed(() => {
  if (isSendingOtp.value) return 'Sending…';
  if (otpCooldown.value > 0 && otpCooldownForEmail.value === email.value.trim()) {
    return `Wait ${otpCooldown.value}s`;
  }
  return otpSentForEmail.value === email.value.trim() ? 'Resend OTP' : 'Send OTP';
});

onUnmounted(() => {
  if (otpCooldownTimer) clearInterval(otpCooldownTimer);
});

onMounted(async () => {
  isPasswordFormOpen.value = route.query.changePassword === '1';

  try {
    const user = await getCurrentUser();
    name.value = user.name;
    email.value = user.email;
    originalEmail.value = user.email;
  } catch (error: unknown) {
    isProfileError.value = true;
    profileMessage.value = getApiErrorMessage(error);
  } finally {
    isLoading.value = false;
  }
});

async function saveProfile() {
  const newEmail = email.value.trim();

  if (newEmail !== originalEmail.value && otpSentForEmail.value !== newEmail) {
    isProfileError.value = true;
    profileMessage.value = 'Send a verification code to your new email address first.';
    return;
  }

  if (newEmail !== originalEmail.value && !emailOtp.value.trim()) {
    isProfileError.value = true;
    profileMessage.value = 'Enter the verification code sent to your new email address.';
    return;
  }

  isSaving.value = true;
  isProfileError.value = false;
  profileMessage.value = '';

  try {
    const update: { name: string; email?: string; otp?: string } = {
      name: name.value.trim(),
    };
    if (newEmail !== originalEmail.value) {
      update.email = newEmail;
      update.otp = emailOtp.value.trim();
    }
    const user = await updateProfile(update);
    name.value = user.name;
    email.value = user.email;
    originalEmail.value = user.email;
    emailOtp.value = '';
    otpSentForEmail.value = '';
    profileMessage.value = 'Your profile has been updated.';
  } catch (error: unknown) {
    isProfileError.value = true;
    profileMessage.value = getApiErrorMessage(error);
  } finally {
    isSaving.value = false;
  }
}

function onEmailInput() {
  if (email.value.trim() !== otpSentForEmail.value) {
    otpSentForEmail.value = '';
    emailOtp.value = '';
  }
}

async function sendEmailOtp() {
  const requestedEmail = email.value.trim();
  if (!requestedEmail || requestedEmail === originalEmail.value) return;

  isSendingOtp.value = true;
  isProfileError.value = false;
  profileMessage.value = '';

  try {
    profileMessage.value = await requestProfileEmailOtp(requestedEmail);
    otpSentForEmail.value = requestedEmail;
    emailOtp.value = '';
    otpCooldown.value = 60;
    otpCooldownForEmail.value = requestedEmail;
    if (otpCooldownTimer) clearInterval(otpCooldownTimer);
    otpCooldownTimer = setInterval(() => {
      otpCooldown.value -= 1;
      if (otpCooldown.value <= 0 && otpCooldownTimer) {
        clearInterval(otpCooldownTimer);
        otpCooldownTimer = undefined;
      }
    }, 1000);
  } catch (error: unknown) {
    isProfileError.value = true;
    profileMessage.value = getApiErrorMessage(error);
  } finally {
    isSendingOtp.value = false;
  }
}

function togglePasswordForm() {
  isPasswordFormOpen.value = !isPasswordFormOpen.value;
  passwordMessage.value = '';

  if (!isPasswordFormOpen.value) {
    currentPassword.value = '';
    password.value = '';
    passwordConfirmation.value = '';
  }
}

async function savePassword() {
  if (password.value !== passwordConfirmation.value) {
    isPasswordError.value = true;
    passwordMessage.value = 'The new password confirmation does not match.';
    return;
  }

  isChangingPassword.value = true;
  isPasswordError.value = false;
  passwordMessage.value = '';

  try {
    passwordMessage.value = await changePassword({
      current_password: currentPassword.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });
    currentPassword.value = '';
    password.value = '';
    passwordConfirmation.value = '';
  } catch (error: unknown) {
    isPasswordError.value = true;
    passwordMessage.value = getApiErrorMessage(error);
  } finally {
    isChangingPassword.value = false;
  }
}
</script>

<template>
  <header
    class="fixed inset-x-0 top-0 z-40 flex h-[60px] items-center justify-between border-b border-zinc-200 bg-white/90 px-3 backdrop-blur-xl md:h-16 md:px-[26px]"
  >
    <div class="flex items-center gap-2 md:gap-4">
      <button
        type="button"
        class="grid size-9 shrink-0 place-items-center rounded-lg text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 md:hidden"
        aria-label="Open navigation"
        @click="$emit('toggle-sidebar')"
      >
        <q-icon name="menu" size="21px" />
      </button>
      <button
        type="button"
        class="hidden size-9 shrink-0 place-items-center rounded-lg text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 md:grid"
        :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
        @click="$emit('toggle-sidebar')"
      >
        <q-icon name="menu_open" size="21px" />
      </button>
      <router-link
        to="/dashboard"
        class="flex items-center gap-2.5 text-sm font-semibold tracking-tight"
      >
        <span class="grid size-[30px] place-items-center rounded-lg bg-zinc-900 text-white">
          <q-icon name="check" size="16px" />
        </span>
        <span>Task Tracker</span>
      </router-link>
    </div>

    <div class="flex items-center gap-2 md:gap-4">
      <q-btn
        flat
        round
        dense
        class="grid size-9 place-items-center rounded-lg text-zinc-600 hover:bg-zinc-100"
        aria-label="Notifications"
      >
        <q-icon name="notifications_none" size="21px" />
        <q-menu
          anchor="bottom right"
          self="top right"
          class="min-w-[240px] rounded-xl border border-zinc-200 shadow-xl"
        >
          <div class="grid justify-items-center gap-2 px-5 py-6 text-center">
            <span class="grid size-9 place-items-center rounded-full bg-zinc-100 text-zinc-600">
              <q-icon name="notifications_none" size="19px" />
            </span>
            <strong class="text-xs font-semibold">You’re all caught up</strong>
            <span class="text-[11px] text-zinc-500">New task reminders will show up here.</span>
          </div>
        </q-menu>
      </q-btn>

      <q-btn
        flat
        no-caps
        class="flex min-h-[42px] items-center gap-2.5 rounded-lg px-1.5 text-zinc-900 hover:bg-zinc-100"
        aria-label="Open user menu"
      >
        <span
          class="grid size-8 shrink-0 place-items-center rounded-full border border-zinc-200 bg-zinc-100 text-xs font-semibold text-zinc-700"
          >{{ userInitial }}</span
        >
        <span class="hidden min-w-0 flex-col gap-0.5 text-left sm:flex">
          <strong class="max-w-[150px] truncate text-xs font-semibold">{{
            user?.name || 'Your account'
          }}</strong>
          <span class="text-[11px] text-zinc-500">{{ user?.email || 'Account menu' }}</span>
        </span>
        <q-icon name="keyboard_arrow_down" size="18px" class="hidden text-zinc-500 sm:block" />
        <q-menu
          anchor="bottom right"
          self="top right"
          class="min-w-56 rounded-xl border border-zinc-200 p-1.5 shadow-xl"
        >
          <div class="flex items-center gap-2.5 p-2">
            <span
              class="grid size-8 shrink-0 place-items-center rounded-full border border-zinc-200 bg-zinc-100 text-xs font-semibold text-zinc-700"
              >{{ userInitial }}</span
            >
            <span class="flex min-w-0 flex-col gap-1">
              <strong class="truncate text-xs font-semibold">{{
                user?.name || 'Your account'
              }}</strong>
              <small class="truncate text-[10px] text-zinc-500">{{
                user?.email || userError || 'Task Tracker workspace'
              }}</small>
            </span>
          </div>
          <div class="my-1.5 h-px bg-zinc-100" />
          <router-link
            to="/settings/profile"
            class="flex min-h-9 items-center gap-2 rounded-md px-2 text-[11px] text-zinc-700 hover:bg-zinc-100"
          >
            <q-icon name="person_outline" size="18px" />
            <span>Profile</span>
          </router-link>
          <router-link
            to="/settings/password"
            class="flex min-h-9 items-center gap-2 rounded-md px-2 text-[11px] text-zinc-700 hover:bg-zinc-100"
          >
            <q-icon name="lock_reset" size="18px" />
            <span>Change password</span>
          </router-link>
          <div class="my-1.5 h-px bg-zinc-100" />
          <button
            class="flex min-h-9 w-full items-center gap-2 rounded-md px-2 text-left text-[11px] text-red-700 hover:bg-red-50 disabled:cursor-wait disabled:opacity-60"
            type="button"
            :disabled="isSigningOut"
            @click="signOut"
          >
            <q-icon name="logout" size="18px" />
            <span>{{ isSigningOut ? 'Signing out…' : 'Log out' }}</span>
          </button>
          <p
            v-if="logoutError"
            class="m-0.5 rounded-lg border border-red-200 bg-red-50 p-2 text-[10px] text-red-700"
            role="alert"
          >
            {{ logoutError }}
          </p>
        </q-menu>
      </q-btn>
    </div>
  </header>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { getApiErrorMessage, getCurrentUser, logout, type AuthUser } from '@/services/auth';

defineOptions({ name: 'AppHeader' });

defineProps<{
  collapsed: boolean;
}>();

defineEmits<{
  'toggle-sidebar': [];
}>();

const router = useRouter();
const user = ref<AuthUser | null>(null);
const userError = ref('');
const logoutError = ref('');
const isSigningOut = ref(false);
const userInitial = computed(() => user.value?.name.trim().charAt(0).toUpperCase() || 'U');

onMounted(async () => {
  try {
    user.value = await getCurrentUser();
  } catch (error: unknown) {
    userError.value = getApiErrorMessage(error);
  }
});

async function signOut() {
  isSigningOut.value = true;
  logoutError.value = '';

  try {
    await logout();
    await router.push('/login');
  } catch (error: unknown) {
    logoutError.value = getApiErrorMessage(error);
  } finally {
    isSigningOut.value = false;
  }
}
</script>

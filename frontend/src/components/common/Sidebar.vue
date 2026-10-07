<template>
  <div>
    <button
      v-if="mobileOpen"
      class="fixed inset-x-0 bottom-0 top-[60px] z-20 border-0 bg-zinc-950/30 md:hidden"
      type="button"
      aria-label="Close navigation"
      @click="$emit('close')"
    />

    <aside
      class="fixed bottom-0 left-0 top-[60px] z-30 flex w-[260px] -translate-x-full flex-col border-r border-zinc-200 bg-white px-3.5 py-5 transition-all duration-200 md:top-16 md:translate-x-0"
      :class="[
        mobileOpen ? 'translate-x-0' : '',
        collapsed
          ? 'md:w-[76px] md:items-center md:px-2.5'
          : 'md:w-[248px] md:items-stretch md:px-3.5',
      ]"
      aria-label="Workspace navigation"
    >
      <div class="flex min-h-[52px] items-center gap-2.5 rounded-xl border border-zinc-200 p-2">
        <div
          class="grid size-[31px] shrink-0 place-items-center rounded-lg bg-zinc-100 text-zinc-600"
        >
          <q-icon name="workspaces" size="18px" />
        </div>
        <div :class="['min-w-0 flex-col gap-0.5', collapsed ? 'flex md:hidden' : 'flex']">
          <strong class="truncate text-xs font-semibold">My workspace</strong>
          <span class="text-[11px] text-zinc-500">Personal account</span>
        </div>
        <q-tooltip v-if="collapsed" anchor="center right" self="center left" class="md:block">
          My workspace
        </q-tooltip>
      </div>

      <p
        :class="[
          'mb-2 mt-7 ml-2.5 text-[10px] font-semibold uppercase tracking-[0.08em] text-zinc-400',
          collapsed ? 'block md:hidden' : 'block',
        ]"
      >
        Workspace
      </p>
      <nav
        class="mt-5 grid gap-1 md:mt-0"
        :class="collapsed ? 'md:w-full' : ''"
        aria-label="Workspace navigation"
      >
        <router-link
          v-for="item in navigation"
          :key="item.to"
          :to="item.to"
          class="flex min-h-[42px] items-center gap-3 rounded-lg px-3 text-[13px] font-medium text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-900"
          active-class="bg-zinc-100 text-zinc-900 font-semibold"
          :class="collapsed ? 'md:justify-center md:px-0' : ''"
          :aria-label="collapsed ? item.label : undefined"
          @click="$emit('close')"
        >
          <q-icon :name="item.icon" size="19px" class="shrink-0" />
          <span :class="collapsed ? 'inline md:hidden' : ''">{{ item.label }}</span>
          <q-tooltip v-if="collapsed" anchor="center right" self="center left">
            {{ item.label }}
          </q-tooltip>
        </router-link>
      </nav>

      <div :class="['mt-auto w-full border-t border-zinc-100 pt-4']">
        <button
          class="flex min-h-[42px] w-full items-center gap-3 rounded-lg px-3 text-left text-[13px] font-medium text-zinc-500 transition-colors hover:bg-red-50 hover:text-red-700 disabled:cursor-wait disabled:opacity-60"
          :class="collapsed ? 'md:justify-center md:px-0' : ''"
          :aria-label="collapsed ? 'Log out' : undefined"
          type="button"
          :disabled="isSigningOut"
          @click="signOut"
        >
          <q-icon name="logout" size="19px" class="shrink-0" />
          <span :class="collapsed ? 'inline md:hidden' : ''">
            {{ isSigningOut ? 'Signing out…' : 'Log out' }}
          </span>
          <q-tooltip v-if="collapsed" anchor="center right" self="center left"> Log out </q-tooltip>
        </button>
        <p
          v-if="logoutError"
          class="mt-2 rounded-lg border border-red-200 bg-red-50 p-2 text-[11px] text-red-700"
          role="alert"
        >
          {{ logoutError }}
        </p>
      </div>
    </aside>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { getApiErrorMessage, logout } from '@/services/auth';

defineOptions({ name: 'AppSidebar' });

defineProps<{
  collapsed: boolean;
  mobileOpen: boolean;
}>();

defineEmits<{
  close: [];
}>();

const router = useRouter();
const isSigningOut = ref(false);
const logoutError = ref('');

const navigation = [
  { label: 'Dashboard', icon: 'dashboard', to: '/dashboard' },
  { label: 'Tasks', icon: 'checklist', to: '/tasks' },
  { label: 'Profile', icon: 'person_outline', to: '/profile' },
];

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

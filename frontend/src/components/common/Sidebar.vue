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
        aria-label="Main navigation"
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

      <div
        :class="[
          'mt-auto items-center gap-2.5 border-t border-zinc-100 px-0.5 pt-4',
          collapsed ? 'flex md:hidden' : 'flex',
        ]"
      >
        <div
          class="grid size-[30px] shrink-0 place-items-center rounded-lg bg-amber-50 text-amber-800"
        >
          <q-icon name="lightbulb" size="17px" />
        </div>
        <div class="grid gap-0.5">
          <strong class="text-xs font-semibold">Stay on track</strong>
          <span class="text-[11px] text-zinc-500">Small steps add up.</span>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup lang="ts">
defineOptions({ name: 'AppSidebar' });

defineProps<{
  collapsed: boolean;
  mobileOpen: boolean;
}>();

defineEmits<{
  close: [];
}>();

const navigation = [
  { label: 'Dashboard', icon: 'dashboard', to: '/dashboard' },
  { label: 'Tasks', icon: 'checklist', to: '/tasks' },
  { label: 'Settings', icon: 'settings', to: '/settings' },
];
</script>

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
    </div>
  </header>
</template>

<script setup lang="ts">
defineOptions({ name: 'AppHeader' });

defineProps<{
  collapsed: boolean;
}>();

defineEmits<{
  'toggle-sidebar': [];
}>();
</script>

<template>
  <div class="min-h-screen bg-zinc-50 font-sans text-zinc-900">
    <Header :collapsed="sidebarCollapsed" @toggle-sidebar="toggleSidebar" />
    <Sidebar
      :collapsed="sidebarCollapsed"
      :mobile-open="mobileMenuOpen"
      @close="mobileMenuOpen = false"
    />
    <main
      class="min-h-screen pb-8 pt-[60px] transition-[margin] duration-200 md:ml-[248px] md:pt-16"
      :class="sidebarCollapsed ? 'md:ml-[76px]' : ''"
    >
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Header from '@/components/common/Header.vue';
import Sidebar from '@/components/common/Sidebar.vue';

defineOptions({ name: 'DashboardLayout' });

const sidebarCollapsed = ref(false);
const mobileMenuOpen = ref(false);

function toggleSidebar() {
  if (window.matchMedia('(max-width: 760px)').matches) {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    return;
  }

  sidebarCollapsed.value = !sidebarCollapsed.value;
}
</script>

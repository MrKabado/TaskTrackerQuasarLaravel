<template>
  <div class="auth-page">
    <header class="auth-header">
      <router-link to="/" class="brand" aria-label="Taskflow home">
        <span class="brand-mark"><q-icon name="check" size="17px" /></span>
        taskflow
      </router-link>
      <router-link :to="backTo" class="auth-back">{{ backLabel }}</router-link>
    </header>

    <main class="auth-main">
      <div class="auth-heading">
        <p class="auth-kicker">{{ kicker }}</p>
        <h1 class="auth-title">{{ title }}</h1>
        <p class="auth-description">{{ description }}</p>
      </div>
      <slot />
    </main>

    <footer class="auth-footer">
      <span>{{ footerText }}</span>
      <router-link :to="footerLink">{{ footerLinkText }}</router-link>
    </footer>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    kicker: string;
    title: string;
    description: string;
    backTo?: string;
    backLabel?: string;
    footerText: string;
    footerLink: string;
    footerLinkText: string;
  }>(),
  {
    backTo: '/',
    backLabel: 'Back to home',
  },
);
</script>

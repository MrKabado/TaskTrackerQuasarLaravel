<template>
  <div class="simple-layout">
    <header class="simple-layout-header">
      <router-link to="/" class="brand">
        <span class="brand-mark"><q-icon name="check" size="17px" /></span>
        taskflow
      </router-link>
      <router-link to="/">Back to home</router-link>
    </header>
    <main class="simple-layout-content">
      <router-view />
    </main>
  </div>
</template>

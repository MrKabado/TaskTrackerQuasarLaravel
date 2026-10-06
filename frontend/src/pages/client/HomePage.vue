<template>
  <main class="dashboard">
    <header class="dashboard-header">
      <router-link to="/" class="brand">
        <span class="brand-mark"><q-icon name="check" size="17px" /></span>
        taskflow
      </router-link>
      <nav class="dashboard-nav" aria-label="Breadcrumb">
        <span>Workspace</span>
        <span aria-hidden="true">/</span>
        <strong>Overview</strong>
      </nav>
    </header>

    <section class="dashboard-main">
      <div class="dashboard-heading">
        <h1>Overview</h1>
        <p>A clear place to plan your day and keep your priorities moving.</p>
      </div>

      <div class="dashboard-empty">
        <div>
          <span class="dashboard-empty-icon"><q-icon name="checklist" size="21px" /></span>
          <h2>Your workspace is ready</h2>
          <p>Your tasks and progress will appear here, giving you a simple view of what’s next.</p>
        </div>
      </div>
    </section>
  </main>
</template>

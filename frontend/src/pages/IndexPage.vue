<template>
  <main class="landing">
    <header class="landing-header">
      <router-link to="/" class="brand">
        <span class="brand-mark"><q-icon name="check" size="17px" /></span>
        taskflow
      </router-link>

      <nav class="landing-nav" aria-label="Main navigation">
        <a href="#features">Features</a>
        <a href="#how-it-works">How it works</a>
      </nav>

      <div class="landing-actions">
        <router-link to="/login" class="landing-login">Log in</router-link>
        <router-link to="/register" class="button-primary">Get started</router-link>
      </div>
    </header>

    <section class="landing-hero">
      <div class="landing-copy">
        <p class="landing-eyebrow">A clearer way to get things done</p>
        <h1 class="landing-title">Your day,<br />back in focus.</h1>
        <p class="landing-description">
          Plan what matters, keep your tasks moving, and make room for the work you care about.
          Simple tools for a calmer, more focused day.
        </p>
        <div class="landing-cta">
          <router-link to="/register" class="button-primary">
            Start for free
            <q-icon name="arrow_forward" size="16px" />
          </router-link>
          <a href="#features" class="landing-cta-secondary">Explore features</a>
        </div>
      </div>

      <div class="preview" aria-label="Preview of a daily task list">
        <div class="preview-topbar">
          <div class="preview-dots" aria-hidden="true"><span></span><span></span><span></span></div>
          <span class="preview-date">MONDAY, OCTOBER 5</span>
          <span></span>
        </div>
        <div class="preview-content">
          <div class="preview-heading">
            <div>
              <h2>Today’s focus</h2>
              <p>One step at a time.</p>
            </div>
            <span class="preview-avatar">A</span>
          </div>

          <div class="preview-progress">
            <div class="preview-progress-label">
              <span>Daily progress</span>
              <strong>3 of 5 complete</strong>
            </div>
            <div class="preview-progress-track"><span></span></div>
          </div>

          <div class="preview-tasks">
            <div class="preview-task is-complete">
              <span class="preview-task-check"><q-icon name="check" size="12px" /></span>
              <span class="preview-task-name">Review project brief</span>
              <span class="preview-task-tag">Work</span>
              <span class="preview-task-time">9:00</span>
            </div>
            <div class="preview-task is-complete">
              <span class="preview-task-check"><q-icon name="check" size="12px" /></span>
              <span class="preview-task-name">Send weekly update</span>
              <span class="preview-task-tag">Team</span>
              <span class="preview-task-time">10:30</span>
            </div>
            <div class="preview-task">
              <span class="preview-task-check"></span>
              <span class="preview-task-name">Prepare design feedback</span>
              <span class="preview-task-tag">Focus</span>
              <span class="preview-task-time">11:00</span>
            </div>
            <div class="preview-task">
              <span class="preview-task-check"></span>
              <span class="preview-task-name">Take a proper lunch break</span>
              <span class="preview-task-tag">Personal</span>
              <span class="preview-task-time">12:30</span>
            </div>
          </div>
          <p class="preview-footnote">A little progress adds up.</p>
        </div>
      </div>
    </section>

    <section id="features" class="landing-section">
      <div class="landing-section-inner">
        <p class="section-kicker">A simpler way to stay on track</p>
        <h2 class="section-title">Less juggling. More meaningful progress.</h2>
        <p class="section-description">Keep your plans clear and your next step easy to find.</p>

        <div class="feature-grid">
          <article class="feature-card">
            <span class="feature-card-icon"><q-icon name="checklist" size="18px" /></span>
            <h3>A plan you can follow</h3>
            <p>Turn your to-dos into a clear daily list and know exactly where to begin.</p>
          </article>
          <article class="feature-card">
            <span class="feature-card-icon"><q-icon name="flag" size="18px" /></span>
            <h3>Space for what matters</h3>
            <p>Bring your priorities to the surface and protect time for focused work.</p>
          </article>
          <article class="feature-card">
            <span class="feature-card-icon"><q-icon name="trending_up" size="18px" /></span>
            <h3>Progress you can see</h3>
            <p>Celebrate completed tasks and build momentum one step at a time.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="how-it-works" class="landing-section">
      <div class="landing-section-inner landing-cta-section">
        <div>
          <p class="section-kicker">A fresh start is one click away</p>
          <h2 class="section-title">Ready to find your focus?</h2>
          <p class="section-description">Make space for the work and life you care about.</p>
        </div>
        <router-link to="/register" class="button-primary">Create your free account</router-link>
      </div>
    </section>

    <footer class="landing-footer">
      <router-link to="/" class="brand">
        <span class="brand-mark"><q-icon name="check" size="15px" /></span>
        taskflow
      </router-link>
      <span>Less overwhelm. More done.</span>
      <div class="landing-footer-links">
        <router-link to="/login">Log in</router-link>
        <router-link to="/register">Sign up</router-link>
      </div>
    </footer>
  </main>
</template>

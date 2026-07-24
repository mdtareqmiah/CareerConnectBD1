<template>
  <footer class="apps-foot">
    <div class="apps-foot-inner">
      <div class="apps-foot-brand">
        <span v-if="logoUrl" class="apps-foot-logo">
          <img :src="logoUrl" alt="CareerConnectBD logo">
        </span>
        <span v-else class="apps-foot-mark">CC</span>
        <span class="apps-foot-name">CareerConnectBD</span>
      </div>

      <div class="apps-foot-tagline">Track every application. Celebrate every opportunity.</div>

      <nav class="apps-foot-links">
        <a v-if="routes.privacy" :href="routes.privacy" class="apps-foot-link">Privacy Policy</a>
        <a v-if="routes.terms" :href="routes.terms" class="apps-foot-link">Terms &amp; Conditions</a>
        <a v-if="routes.help" :href="routes.help" class="apps-foot-link">Help Center</a>
        <a v-if="routes.support" :href="routes.support" class="apps-foot-link">Contact Support</a>
      </nav>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'ApplicationsFooter',
  props: {
    logoUrl: { type: String, default: '' },
    routes: { type: Object, default: () => ({}) },
  },
};
</script>

<style scoped>
.apps-foot {
  background: #5b6472;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  padding: 0;
  color: #f9fafb;
  margin-top: auto;
}

.apps-foot-inner {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0 clamp(1rem, 3vw, 2.25rem);
  width: 100%;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.25rem;
  flex-wrap: wrap;
}

.apps-foot-brand {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  text-decoration: none;
  color: inherit;
}

.apps-foot-logo img {
  height: 26px;
  width: auto;
  border-radius: 7px;
}

.apps-foot-mark {
  width: 26px;
  height: 26px;
  border-radius: 7px;
  font-size: 0.7rem;
  background: linear-gradient(135deg, #2563eb, #6366f1);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-family: 'Poppins', sans-serif;
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
}

.apps-foot-name {
  font-size: 0.88rem;
  font-weight: 700;
  color: #f9fafb;
  letter-spacing: 0.01em;
}

.apps-foot-tagline {
  font-size: 0.85rem;
  color: rgba(249, 250, 251, 0.85);
  font-weight: 500;
}

.apps-foot-links {
  display: inline-flex;
  align-items: center;
  gap: 1.25rem;
  flex-wrap: wrap;
}

.apps-foot-link {
  font-size: 0.82rem;
  color: rgba(249, 250, 251, 0.9);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
}

.apps-foot-link:hover {
  color: #ffffff;
}

@media (max-width: 991.98px) {
  .apps-foot-inner {
    justify-content: center;
    text-align: center;
    gap: 0.75rem;
    height: auto;
    min-height: 80px;
    padding: 1rem clamp(1rem, 3vw, 2.25rem);
  }
}
</style>

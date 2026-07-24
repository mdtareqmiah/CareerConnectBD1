<template>
  <footer class="notif-foot">
    <div class="notif-foot-inner">
      <div class="notif-foot-brand">
        <span v-if="logoUrl" class="notif-foot-logo">
          <img :src="logoUrl" alt="CareerConnectBD logo">
        </span>
        <span v-else class="notif-foot-mark">CC</span>
        <span class="notif-foot-name">CareerConnectBD</span>
      </div>

      <div class="notif-foot-tagline">Stay informed. Stay connected.</div>

      <nav class="notif-foot-links">
        <a v-if="routes.privacy" :href="routes.privacy" class="notif-foot-link">Privacy Policy</a>
        <a v-if="routes.terms" :href="routes.terms" class="notif-foot-link">Terms &amp; Conditions</a>
        <a v-if="routes.help" :href="routes.help" class="notif-foot-link">Help Center</a>
        <a v-if="routes.support" :href="routes.support" class="notif-foot-link">Support</a>
      </nav>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'NotificationsFooter',
  props: {
    logoUrl: { type: String, default: '' },
    routes: { type: Object, default: () => ({}) },
  },
};
</script>

<style scoped>
.notif-foot {
  background: #6b7280;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  padding: 0;
  color: #f9fafb;
  margin-top: auto;
}

.notif-foot-inner {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0 clamp(1rem, 3vw, 2.25rem);
  width: 100%;
  height: 75px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.25rem;
  flex-wrap: wrap;
}

.notif-foot-brand {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  text-decoration: none;
  color: inherit;
}

.notif-foot-logo img {
  height: 26px;
  width: auto;
  border-radius: 7px;
}

.notif-foot-mark {
  width: 26px;
  height: 26px;
  border-radius: 7px;
  font-size: 0.7rem;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-family: 'Poppins', sans-serif;
  box-shadow: 0 4px 10px rgba(59, 130, 246, 0.25);
}

.notif-foot-name {
  font-size: 0.88rem;
  font-weight: 700;
  color: #f9fafb;
  letter-spacing: 0.01em;
}

.notif-foot-tagline {
  font-size: 0.85rem;
  color: rgba(249, 250, 251, 0.85);
  font-weight: 500;
}

.notif-foot-links {
  display: inline-flex;
  align-items: center;
  gap: 1.25rem;
  flex-wrap: wrap;
}

.notif-foot-link {
  font-size: 0.82rem;
  color: rgba(249, 250, 251, 0.9);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
}

.notif-foot-link:hover {
  color: #ffffff;
}

@media (max-width: 991.98px) {
  .notif-foot-inner {
    justify-content: center;
    text-align: center;
    gap: 0.75rem;
    height: auto;
    min-height: 75px;
    padding: 1rem clamp(1rem, 3vw, 2.25rem);
  }
}
</style>

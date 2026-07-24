<template>
    <footer class="pfoot">
        <div class="pfoot-inner">
            <div class="pfoot-brand">
                <span v-if="logoUrl" class="pfoot-logo"><img :src="logoUrl" alt="CareerConnectBD"></span>
                <span v-else class="pfoot-mark">CC</span>
                <span class="pfoot-name">CareerConnectBD</span>
            </div>

            <div class="pfoot-copy">© {{ year }} CareerConnectBD. All Rights Reserved.</div>

            <nav class="pfoot-links">
                <a :href="routes.privacy">Privacy Policy</a>
                <a :href="routes.terms">Terms &amp; Conditions</a>
                <a :href="routes.help">Help Center</a>
            </nav>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'ProfileFooter',
    props: {
        logoUrl: { type: String, default: '' },
        year: { type: Number, default: () => new Date().getFullYear() },
        routes: { type: Object, default: () => ({}) },
    },
};
</script>

<style scoped>
.pfoot {
    background: #4B5563;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding: 0;
    color: #F9FAFB;
}
.pfoot-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2.25rem);
    width: 100%;
    min-height: 72px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.pfoot-brand { display: inline-flex; align-items: center; gap: 0.6rem; }
.pfoot-logo img { height: 22px; width: auto; border-radius: 6px; }
.pfoot-mark { width: 24px; height: 24px; border-radius: 7px; font-size: 0.7rem; background: linear-gradient(135deg, #2563eb, #6366f1); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-family: 'Poppins', sans-serif; }
.pfoot-name { font-size: 0.88rem; font-weight: 700; color: #F9FAFB; letter-spacing: 0.01em; }
.pfoot-copy { font-size: 0.82rem; color: rgba(249,250,251,0.85); font-weight: 500; }
.pfoot-links { display: inline-flex; align-items: center; gap: 1.1rem; flex-wrap: wrap; }
.pfoot-links a { font-size: 0.82rem; color: rgba(249,250,251,0.9); text-decoration: none; font-weight: 500; transition: color 0.2s; }
.pfoot-links a:hover { color: #fff; }

@media (max-width: 991.98px) {
    .pfoot-inner { justify-content: center; text-align: center; gap: 0.75rem; }
}
</style>

<template>
    <footer class="rb-foot">
        <div class="rb-foot-inner">
            <div class="rb-foot-brand">
                <div class="rb-foot-logo">
                    <img v-if="logoUrl" :src="logoUrl" alt="CareerConnectBD">
                    <span v-else class="rb-foot-mark">CC</span>
                </div>
                <span class="rb-foot-name">CareerConnectBD</span>
            </div>

            <p class="rb-foot-motto">Create your professional resume with confidence.</p>

            <div class="rb-foot-right">
                <nav class="rb-foot-links">
                    <a :href="routes.privacy">Privacy Policy</a>
                    <a :href="routes.terms">Terms</a>
                    <a :href="routes.help">Help Center</a>
                </nav>
                <span class="rb-foot-version">Version 1.0</span>
            </div>
        </div>
    </footer>
</template>

<script>
import { Sparkles } from '@lucide/vue';

export default {
    name: 'ResumeBuilderFooter',
    components: { Sparkles },
    props: {
        logoUrl: { type: String, default: '' },
        year: { type: Number, default: () => new Date().getFullYear() },
        version: { type: String, default: '1.0' },
        routes: { type: Object, default: () => ({}) },
    },
};
</script>

<style scoped>
.rb-foot {
    background: linear-gradient(135deg, #111827 0%, #1F2937 100%);
    border-top: 2px solid transparent;
    border-image: linear-gradient(90deg, #2563eb, #7C3AED, #06B6D4) 1;
    border-radius: 28px 28px 0 0;
    color: #f8fafc;
    margin-top: auto;
    min-height: 90px;
    display: flex;
    align-items: center;
    padding: 1.15rem 0;
}
.rb-foot-inner {
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2.25rem);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.rb-foot-brand {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    flex-shrink: 0;
}
.rb-foot-logo {
    width: 38px; height: 38px; border-radius: 11px; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #2563eb, #7C3AED);
    box-shadow: 0 8px 20px rgba(37,99,235,0.35);
}
.rb-foot-logo img { width: 100%; height: 100%; object-fit: cover; }
.rb-foot-mark { color: #fff; font-weight: 800; font-family: 'Poppins', sans-serif; font-size: 1rem; }
.rb-foot-name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.98rem; color: #fff; letter-spacing: -0.01em; }

.rb-foot-motto {
    margin: 0;
    color: #cbd5e1;
    font-size: 0.84rem;
    font-weight: 500;
    flex: 1;
    text-align: center;
}

.rb-foot-right {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex-shrink: 0;
}
.rb-foot-links {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex-wrap: wrap;
}
.rb-foot-links a {
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.84rem;
    font-weight: 500;
    transition: color 0.2s;
}
.rb-foot-links a:hover { color: #60a5fa; }
.rb-foot-version {
    font-size: 0.72rem; color: #94a3b8; font-weight: 600; letter-spacing: 0.04em;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    padding: 0.3rem 0.65rem; border-radius: 999px; white-space: nowrap;
}

@media (max-width: 991.98px) {
    .rb-foot-inner { justify-content: center; text-align: center; }
    .rb-foot-motto { order: 3; flex-basis: 100%; }
    .rb-foot-brand { order: 1; }
    .rb-foot-right { order: 2; }
}
@media (max-width: 575.98px) {
    .rb-foot-right { flex-direction: column; gap: 0.75rem; }
    .rb-foot-links { justify-content: center; }
}
</style>

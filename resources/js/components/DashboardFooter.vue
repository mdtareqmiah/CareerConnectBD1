<template>
    <footer class="dash-foot">
        <div class="dash-foot-inner">
            <div class="dash-foot-top">
                <div class="dash-foot-brand">
                    <div class="dash-foot-logo">
                        <span v-if="logoUrl"><img :src="logoUrl" alt="CareerConnectBD"></span>
                        <span v-else class="dash-foot-mark">CC</span>
                    </div>
                    <div>
                        <div class="dash-foot-name">CareerConnectBD</div>
                        <div class="dash-foot-tag">Premium AI hiring</div>
                        <p class="dash-foot-desc">A modern recruitment platform built for fast, trusted hiring and career growth.</p>
                    </div>
                </div>

                <div class="dash-foot-cols">
                    <div class="dash-foot-col">
                        <h6 class="dash-foot-col-h">Company</h6>
                        <a :href="routes.privacy">Privacy Policy</a>
                        <a :href="routes.terms">Terms</a>
                        <a :href="routes.help">Help Center</a>
                        <a :href="routes.support">Support</a>
                    </div>
                    <div class="dash-foot-col">
                        <h6 class="dash-foot-col-h">Product</h6>
                        <a :href="routes.dashboard">Dashboard</a>
                        <a :href="routes.jobs">Browse Jobs</a>
                        <a :href="routes.savedJobs">Saved Jobs</a>
                        <a :href="routes.profile">Profile</a>
                    </div>
                    <div class="dash-foot-col">
                        <h6 class="dash-foot-col-h">Connect</h6>
                        <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="dash-foot-bottom">
                <span class="dash-foot-copy">© {{ year }} CareerConnectBD. All rights reserved.</span>
                <span class="dash-foot-version">v{{ version }}</span>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'DashboardFooter',
    props: {
        logoUrl: { type: String, default: '' },
        year: { type: Number, default: () => new Date().getFullYear() },
        version: { type: String, default: '1.0.0' },
        routes: { type: Object, default: () => ({}) },
    },
};
</script>

<style scoped>
.dash-foot {
    background: #0b1120;
    background-image: radial-gradient(120% 140% at 0% 0%, rgba(37,99,235,0.18), transparent 55%),
                      radial-gradient(120% 140% at 100% 0%, rgba(139,92,246,0.16), transparent 55%);
    color: #cbd5e1;
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 1.1rem 0 0.85rem;
}
.dash-foot-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2.25rem);
    width: 100%;
}
.dash-foot-top {
    display: flex;
    justify-content: space-between;
    gap: 2.5rem;
    flex-wrap: wrap;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.dash-foot-brand { display: flex; gap: 0.9rem; max-width: 340px; }
.dash-foot-logo {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    overflow: hidden; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #2563eb, #6366f1);
    box-shadow: 0 8px 20px rgba(37,99,235,0.35);
}
.dash-foot-logo img { width: 100%; height: 100%; object-fit: cover; }
.dash-foot-mark {
    color: #fff; font-weight: 800; font-family: 'Poppins', sans-serif; font-size: 1.1rem;
}
.dash-foot-name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.05rem; color: #fff; }
.dash-foot-tag { font-size: 0.68rem; letter-spacing: 0.14em; text-transform: uppercase; color: #f59e0b; font-weight: 600; }
.dash-foot-desc { font-size: 0.82rem; color: #94a3b8; margin: 0.5rem 0 0; line-height: 1.6; }

.dash-foot-cols { display: flex; gap: 2.5rem; flex-wrap: wrap; }
.dash-foot-col { display: flex; flex-direction: column; gap: 0.55rem; min-width: 110px; }
.dash-foot-col-h { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; color: #e2e8f0; font-weight: 700; margin: 0 0 0.35rem; }
.dash-foot-col a { font-size: 0.85rem; color: #94a3b8; text-decoration: none; transition: color 0.2s; }
.dash-foot-col a:hover { color: #60a5fa; }
.dash-foot-col a i { font-size: 1.05rem; }

.dash-foot-bottom {
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    padding-top: 1.1rem; flex-wrap: wrap;
}
.dash-foot-copy { font-size: 0.8rem; color: #64748b; }
.dash-foot-version {
    font-size: 0.74rem; color: #94a3b8; font-weight: 600; letter-spacing: 0.04em;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    padding: 0.25rem 0.6rem; border-radius: 999px;
}

@media (max-width: 767.98px) {
    .dash-foot-top { flex-direction: column; gap: 1.75rem; }
    .dash-foot-cols { gap: 1.75rem; }
}
@media (max-width: 575.98px) {
    .dash-foot-bottom { justify-content: center; text-align: center; }
}
</style>

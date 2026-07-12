<footer class="border-top bg-white mt-4">
    <div class="container py-5">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <a class="navbar-brand d-flex align-items-center gap-3 fw-semibold text-dark" href="/">
                    <span class="brand-mark d-inline-flex align-items-center justify-content-center rounded-circle text-white">CC</span>
                    <span class="d-flex flex-column">
                        <span class="fw-bold">CareerConnectBD</span>
                        <span class="small text-muted">AI-powered hiring</span>
                    </span>
                </a>
                <p class="text-muted mt-3 mb-0">A refined platform that helps talent and employers connect with clarity, speed, and trust.</p>
            </div>

            <div class="col-sm-6 col-lg-3">
                <h6 class="fw-semibold text-dark mb-3">Explore</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><a class="text-decoration-none text-muted" href="{{ route('jobs.index') }}">Browse jobs</a></li>
                    <li class="mb-2"><a class="text-decoration-none text-muted" href="{{ route('register') }}">Create job seeker account</a></li>
                    <li class="mb-2"><a class="text-decoration-none text-muted" href="{{ route('employer.register') }}">Register as employer</a></li>
                </ul>
            </div>

            <div class="col-sm-6 col-lg-4">
                <h6 class="fw-semibold text-dark mb-3">Why teams choose us</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="bi bi-shield-check me-2 text-primary"></i>Secure candidate workflows</li>
                    <li class="mb-2"><i class="bi bi-stars me-2 text-primary"></i>Premium recruiting experience</li>
                    <li><i class="bi bi-lightning-charge me-2 text-primary"></i>Designed for modern hiring teams</li>
                </ul>
            </div>
        </div>

        <div class="border-top mt-4 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="small text-muted">© {{ date('Y') }} CareerConnectBD. All rights reserved.</div>
            <div class="small text-muted">Built for meaningful hiring experiences.</div>
        </div>
    </div>
</footer>

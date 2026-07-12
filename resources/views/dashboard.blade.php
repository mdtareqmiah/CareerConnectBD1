<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h2 class="h3 fw-semibold mb-1">Dashboard</h2>
                <p class="text-muted mb-0">Use the role-based navigation to continue your work.</p>
            </div>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">CareerConnectBD</span>
        </div>
    </x-slot>

    <div class="py-4 py-lg-5">
        <div class="container">
            <div class="card shadow-soft border-0 overflow-hidden">
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-8">
                            <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                                <i class="bi bi-stars"></i>
                                <span class="fw-semibold">Welcome</span>
                            </div>
                            <h3 class="h4 mb-2">Welcome to CareerConnectBD</h3>
                            <p class="text-muted mb-0">Your workspace is ready. Choose the right path to manage profiles, jobs, and hiring activity.</p>
                        </div>
                        <div class="col-lg-4">
                            <div class="rounded-4 bg-light p-4 text-center">
                                <div class="display-6 text-primary mb-2"><i class="bi bi-speedometer2"></i></div>
                                <p class="fw-semibold mb-1">Role-based workspace</p>
                                <p class="text-muted small mb-0">Built for seekers, employers, and admins.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

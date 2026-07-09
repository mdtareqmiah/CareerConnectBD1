@if (session('success') || session('warning') || session('error') || session('info'))
    <div class="mb-4" aria-live="polite">
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-start gap-2" role="status">
                <span class="fw-semibold">✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
                <span class="fw-semibold">!</span>
                <div>{{ session('warning') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                <span class="fw-semibold">✕</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info d-flex align-items-start gap-2" role="alert">
                <span class="fw-semibold">i</span>
                <div>{{ session('info') }}</div>
            </div>
        @endif
    </div>
@endif

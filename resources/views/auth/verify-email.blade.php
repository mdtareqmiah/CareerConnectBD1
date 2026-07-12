<x-guest-layout>
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 48px; height: 48px;">
            <i class="bi bi-envelope-check-fill"></i>
        </div>
        <h2 class="h4 fw-semibold mb-2">Verify your email</h2>
        <p class="text-muted mb-0">We’ve sent a confirmation link to your inbox. Use it to activate your account and continue.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success rounded-4 border-0 mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>

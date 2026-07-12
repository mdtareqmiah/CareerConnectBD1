<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-2">
                    <i class="bi bi-person-circle"></i>
                    <span class="fw-semibold">Account settings</span>
                </div>
                <h2 class="h3 mb-1">{{ __('Profile') }}</h2>
                <p class="text-muted mb-0">Manage your account details, password, and account removal preferences in one place.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 py-lg-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-soft">
                        <div class="card-body p-4 p-lg-5">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-0 shadow-soft">
                        <div class="card-body p-4 p-lg-5">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-0 shadow-soft">
                        <div class="card-body p-4 p-lg-5">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

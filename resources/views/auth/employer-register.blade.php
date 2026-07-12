<x-guest-layout>
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 48px; height: 48px;">
            <i class="bi bi-building-gear"></i>
        </div>
        <h2 class="h4 fw-semibold mb-2">Employer sign up</h2>
        <p class="text-muted mb-0">Create a secure employer account and start building your hiring pipeline.</p>
    </div>

    <form method="POST" action="{{ route('employer.register') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full form-control" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div>

        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
            <label class="form-check-label text-muted" for="terms">
                {{ __('I accept the terms and conditions') }}
            </label>
            @error('terms')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <a class="text-decoration-none small text-muted" href="{{ route('login') }}">{{ __('Already registered? Login') }}</a>
            <x-primary-button class="w-100 w-sm-auto">
                {{ __('Register as Employer') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

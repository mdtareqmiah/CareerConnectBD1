<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card shadow-sm border-0">
                <div class="card-body py-5">
                    <h3 class="h5 mb-2">Welcome to CareerConnectBD</h3>
                    <p class="text-muted mb-0">Use the role-based navigation to continue your work.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

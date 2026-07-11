<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployerRegistrationRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployerRegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.employer-register');
    }

    public function store(StoreEmployerRegistrationRequest $request): RedirectResponse
    {
        $employerRole = Role::where('slug', 'employer')->first();

        if (! $employerRole) {
            abort(500, 'Employer role not configured.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $employerRole->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended('/employer');
    }
}

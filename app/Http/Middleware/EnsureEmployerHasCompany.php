<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployerHasCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to company.create and company.store routes without company
        if (in_array($request->route()?->getName(), ['company.create', 'company.store'])) {
            return $next($request);
        }

        // Check if user has a company
        if (!auth()->user()->company) {
            return redirect()->route('company.create')
                ->with('warning', 'Please create your company profile to continue.');
        }

        return $next($request);
    }
}

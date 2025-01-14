<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * This middleware checks if the authenticated user is approved. If the user is not approved,
     * they are redirected to the registration form.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = Auth::user();

        // If the user is not approved, redirect them to the registration form
        if (! $user->is_approved) {
            return redirect()->route('registrasi.selectForm');
        }

        return $next($request);
    }
}

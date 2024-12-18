<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * This middleware checks if the authenticated user is verified. If the user is not verified,
     * they are redirected to the registration form.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = Auth::user();

        // If the user is not verified, redirect them to the registration form
        if (! $user->is_verified) {
            return redirect()->route('registrasi.selectForm');
        }

        return $next($request);
    }
}

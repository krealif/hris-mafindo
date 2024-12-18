<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HomeController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventVerifiedUser
{
    /**
     * Middleware to prevent verified users from accessing certain routes (unverified user route).
     *
     * This middleware checks if the authenticated user is verified. If the user is verified,
     * they are redirected to the home page. Otherwise, the request is allowed to proceed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = Auth::user();

        // If the user is verified, redirect them to the dashboard
        if ($user->is_verified) {
            return redirect(HomeController::$HOME);
        }

        return $next($request);
    }
}

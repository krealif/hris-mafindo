<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HomeController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventApprovedUser
{
    /**
     * Middleware to prevent approved users from accessing user registration route.
     *
     * This middleware checks if the authenticated user is approved. If the user is approved,
     * they are redirected to the home page. Otherwise, the request is allowed to proceed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = Auth::user();

        // If the user is approved, redirect them to the dashboard
        if ($user->is_approved) {
            return redirect(HomeController::$HOME);
        }

        return $next($request);
    }
}

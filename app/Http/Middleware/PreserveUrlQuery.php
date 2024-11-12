<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreserveUrlQuery
{
    /**
     * This middleware is implemented as a hack/workaround to preserve the URL query parameters
     * when performing actions such as create, edit, or delete.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $query = $request->query();
        $route = $request->route()->getName();

        if (isset($query['page']) || isset($query['filter'])) {
            session()->put($route, $query);
        } else if (session($route)) {
            session()->forget($route);
        }

        return $next($request);
    }
}

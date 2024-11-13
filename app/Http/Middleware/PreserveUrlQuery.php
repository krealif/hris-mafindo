<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreserveUrlQuery
{
    /**
     * This middleware is implemented as a workaround to preserve the URL query parameters
     * when performing actions such as create, edit, or delete.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $query = $request->query();
        $route = $request->route()->getName();

        $name = explode(".", $route);
        $key = "q.{$name[0]}";

        if (isset($query['page']) || isset($query['filter'])) {
            session()->put($key, $query);
        } else if (session($key)) {
            session()->forget($key);
        }

        return $next($request);
    }
}

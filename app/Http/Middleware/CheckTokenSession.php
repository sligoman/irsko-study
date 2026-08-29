<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment(['testing'])) {
            $routeName = $request->route()?->getName();
            if ($routeName !== 'token') {
                if (!$request->session()->has('security-token')) {
                    return response()->view('errors.token', [], 404);
                }
            }
        }

        return $next($request);
    }
}

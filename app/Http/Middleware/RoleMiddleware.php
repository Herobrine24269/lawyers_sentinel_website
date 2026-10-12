<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized access for your account role.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('login');
        }

        if ($request->user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
} 
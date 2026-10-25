<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login-admin');
        }

        // admin DAN kaprodi boleh masuk
        if (!in_array(auth()->user()->role, ['admin', 'kaprodi'])) {
            abort(403);
        }

        return $next($request);
    }
}
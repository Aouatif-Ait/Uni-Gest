<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware{
    public function handle(Request $request, Closure $next, string ...$roles): Response{
        // Vérifier l'authentification
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier le rôle
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Accès interdit.');
        }

        return $next($request);
    }
}

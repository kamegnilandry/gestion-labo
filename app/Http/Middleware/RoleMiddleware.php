<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Autorise l'accès si l'utilisateur est admin ou possède l'un des rôles indiqués.
     * Usage : ->middleware('role:receptionniste,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin() || $user->hasRole($roles)) {
            return $next($request);
        }

        abort(403, "Vous n'avez pas les droits pour accéder à cette page.");
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Verifica si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si el usuario está inactivo, redirigir a la vista de cuenta inactiva
        if ($user->activo == 0) {
            return redirect()->route('inactive');
        }

        // Verifica si el usuario tiene uno de los roles permitidos
        if (!in_array($user->tipo_usuario, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}




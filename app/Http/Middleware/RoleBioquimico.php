<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBioquimico
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return abort(403, 'Acceso denegado.');
        }

        $role = strtolower(trim((string) Auth::user()->role));

        if (in_array($role, ['administrador', 'bioquimico'])) {
            return $next($request);
        }


        return abort(403, 'Acceso denegado. Área exclusiva para personal de Laboratorio.');
    }
}

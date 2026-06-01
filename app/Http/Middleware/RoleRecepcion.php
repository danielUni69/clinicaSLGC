<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRecepcion
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array(Auth::user()->role, ['administrador', 'recepcionista'])) {
            return $next($request);
        }

        return abort(403, 'Acceso denegado. Solo Recepción y Administración.');
    }
}

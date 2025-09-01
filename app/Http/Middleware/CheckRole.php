<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        Log::info('CheckRole iniciado', [
            'ruta' => $request->path(),
            'usuario_autenticado' => Auth::check(),
            'user_id' => Auth::id(),
            'user_rol' => Auth::user()->rol ?? 'no-autenticado',
            'rol_requerido' => $role
        ]);

        if (!Auth::check()) {
            Log::warning('Acceso denegado: Usuario no autenticado');
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        if (Auth::user()->rol !== $role) {
            Log::warning('Acceso denegado: Rol incorrecto', [
                'rol_actual' => Auth::user()->rol,
                'rol_requerido' => $role
            ]);
            
            // Redirigir según el rol del usuario
            if (Auth::user()->rol === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
            } else if (Auth::user()->rol === 'fundacion') {
                return redirect()->route('fundacion.panel')->with('error', 'No tienes permiso para acceder a esta sección');
            } else {
                return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta sección');
            }
        }

        return $next($request);
    }
}
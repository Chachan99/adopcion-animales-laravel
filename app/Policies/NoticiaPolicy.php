<?php

namespace App\Policies;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoticiaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->tipo === 'fundacion';
    }

    public function view(User $user, Noticia $noticia)
    {
        return $user->id === $noticia->fundacion_id;
    }

    public function create(User $user)
    {
        return $user->tipo === 'fundacion';
    }

    public function update(User $user, Noticia $noticia)
    {
        return $user->id === $noticia->fundacion_id;
    }

    public function delete(User $user, Noticia $noticia)
    {
        return $user->id === $noticia->fundacion_id;
    }

    public function restore(User $user, Noticia $noticia)
    {
        return $user->id === $noticia->fundacion_id;
    }

    public function forceDelete(User $user, Noticia $noticia)
    {
        return $user->id === $noticia->fundacion_id;
    }
    public function viewAny(User $user): bool
    {
        // Solo administradores pueden ver el listado de noticias en el panel
        return $user->rol === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Noticia $noticia): bool
    {
        // Cualquiera puede ver noticias publicadas
        if ($noticia->estado === 'publicada' && $noticia->publicado_en <= now()) {
            return true;
        }
        
        // Usuarios no autenticados no pueden ver borradores o noticias programadas
        if (!$user) {
            return false;
        }
        
        // Solo el autor o un administrador pueden ver borradores o noticias programadas
        return $user->rol === 'admin' || $user->id === $noticia->usuario_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo administradores pueden crear noticias
        return $user->rol === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Noticia $noticia): bool
    {
        // Solo administradores pueden actualizar noticias
        return $user->rol === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Noticia $noticia): bool
    {
        // Solo administradores pueden eliminar noticias
        return $user->rol === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Noticia $noticia): bool
    {
        // Solo administradores pueden restaurar noticias eliminadas
        return $user->rol === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Noticia $noticia): bool
    {
        // Solo administradores pueden eliminar permanentemente noticias
        return $user->rol === 'admin';
    }
}

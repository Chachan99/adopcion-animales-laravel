<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Mostrar el formulario de edición de perfil para usuario público.
     */
    public function editPublico(Request $request): View
    {
        return view('profile.edit-publico', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar la información del perfil de usuario público.
     */
    public function updatePublico(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->nombre = $validated['nombre'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        if ($request->hasFile('imagen')) {
            // Elimina la imagen anterior si existe
            if ($user->imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->imagen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->imagen);
            }
            $file = $request->file('imagen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $rutaImagen = $file->storeAs('usuarios', $filename, 'public');
            $user->imagen = $rutaImagen;
        }
        $user->save();

        return back()->with('status', 'Perfil actualizado correctamente.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

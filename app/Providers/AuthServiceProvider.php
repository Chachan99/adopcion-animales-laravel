<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider
{
    /**
     * The application's policies.
     *
     * @var array<string, class-string|bool>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        \Log::info('AuthServiceProvider booted');
        Gate::define('fundacion', function ($user) {
            return $user->rol === 'fundacion';
        });
        Gate::define('admin', function ($user) {
            \Log::info('Gate admin', [
                'id' => $user->id,
                'email' => $user->email,
                'rol' => $user->rol
            ]);
            return $user->rol === 'admin';
        });
        Gate::define('prueba-gate', function ($user) {
            \Log::info('Gate prueba-gate ejecutado', [
                'tipo' => is_object($user) ? get_class($user) : gettype($user),
                'user' => $user
            ]);
            return true;
        });
    }
} 
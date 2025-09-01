<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {email=admin@example.com} {password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restablece la contraseña del usuario administrador';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = Usuario::where('email', $email)->first();

        if (!$user) {
            $this->error("No se encontró ningún usuario con el correo: {$email}");
            return 1;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("¡Contraseña actualizada con éxito para {$email}!");
        $this->line("Nueva contraseña: {$password}");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignFundacionRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role {email} {role=fundacion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna un rol a un usuario por su correo electrónico';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        $user = Usuario::where('email', $email)->first();

        if (!$user) {
            $this->error("No se encontró ningún usuario con el correo: {$email}");
            return 1;
        }

        $user->rol = $role;
        $user->save();

        $this->info("¡Rol '{$role}' asignado correctamente al usuario {$email}!");

        return 0;
    }
}

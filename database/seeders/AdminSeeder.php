<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Solo crear datos si no existen usuarios (seguridad para producción)
        if (Usuario::count() === 0) {
            // Crear solo un usuario administrador básico
            DB::table('usuarios')->insert([
                'nombre' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✅ Usuario administrador creado\n";
        } else {
            echo "⚠️ Ya existen usuarios, omitiendo creación de administrador\n";
        }

        // Solo crear admin si no existe
        if (DB::table('admins')->count() === 0) {
            // Crear perfil de administrador
            DB::table('admins')->insert([
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✅ Perfil de administrador creado\n";
        } else {
            echo "⚠️ Ya existe perfil de administrador, omitiendo creación\n";
        }
    }
}

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
        // Crear o actualizar usuario administrador
        Usuario::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nombre' => 'Administrador',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        echo "✅ Usuario administrador creado/actualizado (admin@test.com)\n";

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

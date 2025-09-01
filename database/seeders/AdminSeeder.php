<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        $adminId = DB::table('usuarios')->insertGetId([
            'nombre' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'imagen' => 'img/usuarios/admin.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear perfil de administrador
        DB::table('admins')->insert([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear perfil de fundación de ejemplo
        DB::table('perfil_fundaciones')->insert([
            'usuario_id' => $adminId,
            'nombre' => 'Fundación de Ejemplo',
            'descripcion' => 'Esta es una fundación de ejemplo para propósitos de prueba.',
            'direccion' => 'Calle Falsa 123',
            'telefono' => '1234567890',
            'email' => 'contacto@fundacionejemplo.com',
            'sitio_web' => 'https://fundacionejemplo.com',
            'facebook' => 'https://facebook.com/fundacionejemplo',
            'instagram' => 'https://instagram.com/fundacionejemplo',
            'imagen' => 'img/fundaciones/ejemplo.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

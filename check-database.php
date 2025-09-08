<?php

/**
 * Script para verificar la conexión a la base de datos
 * y ejecutar migraciones si es necesario
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE BASE DE DATOS ===\n\n";

try {
    // Probar conexión a la base de datos
    echo "1. Probando conexión a la base de datos...\n";
    $pdo = DB::connection()->getPdo();
    echo "   ✅ Conexión exitosa\n";
    echo "   📊 Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "   🏠 Host: " . config('database.connections.pgsql.host') . "\n";
    echo "   🗄️ Database: " . config('database.connections.pgsql.database') . "\n\n";
    
    // Verificar si existen tablas
    echo "2. Verificando tablas existentes...\n";
    $driver = config('database.default');
    
    if ($driver === 'pgsql') {
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    } else {
        // Para SQLite
        $tables = DB::select("SELECT name as table_name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    }
    
    if (empty($tables)) {
        echo "   ⚠️ No se encontraron tablas. Es necesario ejecutar migraciones.\n\n";
        
        echo "3. Ejecutando migraciones...\n";
        Artisan::call('migrate', ['--force' => true]);
        echo "   " . Artisan::output();
        
        echo "4. Ejecutando seeders...\n";
        // Ejecutar seeders solo si no hay usuarios (condicional para producción)
        if (\App\Models\Usuario::count() === 0) {
            echo "No hay usuarios, ejecutando AdminSeeder...\n";
            Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        } else {
            echo "Ya existen usuarios, omitiendo seeders automáticos\n";
        }
        echo "   " . Artisan::output();
        
    } else {
        echo "   ✅ Tablas encontradas: " . count($tables) . "\n";
        foreach ($tables as $table) {
            echo "      - " . $table->table_name . "\n";
        }
        
        // Verificar si hay migraciones pendientes
        echo "\n3. Verificando migraciones pendientes...\n";
        $pendingMigrations = Artisan::call('migrate:status');
        $output = Artisan::output();
        
        if (strpos($output, 'Pending') !== false) {
            echo "   ⚠️ Hay migraciones pendientes. Ejecutando...\n";
            Artisan::call('migrate', ['--force' => true]);
            echo "   " . Artisan::output();
        } else {
            echo "   ✅ Todas las migraciones están al día\n";
        }
    }
    
    // Verificar datos de prueba
    echo "\n4. Verificando datos de prueba...\n";
    $userCount = DB::table('usuarios')->count();
    $animalCount = DB::table('animales')->count();
    $noticiaCount = DB::table('noticias')->count();
    
    echo "   👥 Usuarios: $userCount\n";
    echo "   🐕 Animales: $animalCount\n";
    echo "   📰 Noticias: $noticiaCount\n";
    
    if ($userCount == 0 || $animalCount == 0) {
        echo "\n   ⚠️ Pocos datos de prueba. Ejecutando seeders...\n";
        // Ejecutar seeders solo si no hay usuarios (condicional para producción)
        if (\App\Models\Usuario::count() === 0) {
            echo "No hay usuarios, ejecutando AdminSeeder...\n";
            Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        } else {
            echo "Ya existen usuarios, omitiendo seeders automáticos\n";
        }
        echo "   " . Artisan::output();
    }
    
    echo "\n=== ✅ VERIFICACIÓN COMPLETADA EXITOSAMENTE ===\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n📋 Detalles del error:\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n";
    echo "\n🔧 Posibles soluciones:\n";
    echo "   1. Verificar variables de entorno DB_*\n";
    echo "   2. Confirmar que la base de datos PostgreSQL esté activa\n";
    echo "   3. Revisar credenciales de conexión\n";
    echo "\n=== ❌ VERIFICACIÓN FALLIDA ===\n";
    exit(1);
}
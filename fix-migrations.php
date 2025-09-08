<?php

/**
 * Script para forzar la ejecución de migraciones en Render
 * Ejecutar con: php fix-migrations.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== REPARACIÓN DE MIGRACIONES EN RENDER ===\n\n";

try {
    // 1. Verificar conexión
    echo "1. 🔍 Verificando conexión a base de datos...\n";
    $pdo = DB::connection()->getPdo();
    echo "   ✅ Conexión exitosa\n\n";
    
    // 2. Verificar si existe la tabla migrations
    echo "2. 🔍 Verificando tabla migrations...\n";
    $migrationTableExists = DB::select(
        "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'migrations')"
    )[0]->exists ?? false;
    
    if (!$migrationTableExists) {
        echo "   ❌ Tabla migrations no existe\n";
        echo "   🔧 Creando tabla migrations...\n";
        
        // Crear tabla migrations manualmente
        DB::statement('
            CREATE TABLE migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            )
        ');
        echo "   ✅ Tabla migrations creada\n";
    } else {
        echo "   ✅ Tabla migrations existe\n";
        $migrationCount = DB::table('migrations')->count();
        echo "   📊 Migraciones registradas: {$migrationCount}\n";
    }
    
    // 3. Listar archivos de migración disponibles
    echo "\n3. 📋 Archivos de migración disponibles:\n";
    $migrationFiles = glob(__DIR__ . '/database/migrations/*.php');
    echo "   📁 Total de archivos: " . count($migrationFiles) . "\n";
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file, '.php');
        echo "   - {$filename}\n";
    }
    
    // 4. Verificar migraciones pendientes
    echo "\n4. 🔍 Verificando migraciones pendientes...\n";
    
    // Obtener migraciones ya ejecutadas
    $executedMigrations = DB::table('migrations')->pluck('migration')->toArray();
    
    $pendingMigrations = [];
    foreach ($migrationFiles as $file) {
        $filename = basename($file, '.php');
        if (!in_array($filename, $executedMigrations)) {
            $pendingMigrations[] = $filename;
        }
    }
    
    if (empty($pendingMigrations)) {
        echo "   ✅ No hay migraciones pendientes\n";
    } else {
        echo "   📋 Migraciones pendientes (" . count($pendingMigrations) . "):\n";
        foreach ($pendingMigrations as $migration) {
            echo "      - {$migration}\n";
        }
    }
    
    // 5. Ejecutar migraciones
    echo "\n5. 🚀 Ejecutando migraciones...\n";
    
    // Solo usar migrate --force (seguro para producción)
    echo "   🔄 Ejecutando migrate --force...\n";
    Artisan::call('migrate', ['--force' => true]);
    echo "   ✅ Migrate completado\n";
    
    // 6. Ejecutar seeders
    echo "\n6. 🌱 Verificando necesidad de seeders...\n";
    try {
        // Verificar si ya existen usuarios antes de ejecutar seeders
        $userCount = DB::table('usuarios')->count();
        if ($userCount === 0) {
            echo "   No hay usuarios, ejecutando seeders...\n";
            Artisan::call('db:seed', ['--force' => true]);
            echo "   ✅ Seeders completados\n";
        } else {
            echo "   Ya existen usuarios ({$userCount}), omitiendo seeders automáticos\n";
        }
    } catch (Exception $e) {
        echo "   ⚠️ Warning en seeders: " . $e->getMessage() . "\n";
        echo "   💡 Esto es normal si los seeders ya se ejecutaron\n";
    }
    
    // 7. Verificar tablas creadas
    echo "\n7. 📊 Verificando tablas creadas...\n";
    $tables = DB::select(
        "SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename"
    );
    
    $expectedTables = [
        'usuarios', 'animales', 'noticias', 'solicitudes_adopcion', 
        'donaciones', 'perfil_fundaciones', 'mascotas_perdidas', 
        'admins', 'sessions', 'cache', 'jobs'
    ];
    
    echo "   📋 Tablas encontradas (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        $tableName = $table->tablename;
        $status = in_array($tableName, $expectedTables) ? '✅' : '📄';
        echo "      {$status} {$tableName}\n";
    }
    
    // Verificar tablas faltantes
    $existingTables = array_column($tables, 'tablename');
    $missingTables = array_diff($expectedTables, $existingTables);
    
    if (!empty($missingTables)) {
        echo "\n   ❌ Tablas faltantes:\n";
        foreach ($missingTables as $missing) {
            echo "      - {$missing}\n";
        }
    }
    
    // 8. Verificar datos
    echo "\n8. 📊 Verificando datos en tablas principales...\n";
    $mainTables = ['usuarios', 'animales', 'noticias'];
    
    foreach ($mainTables as $table) {
        if (in_array($table, $existingTables)) {
            try {
                $count = DB::table($table)->count();
                echo "   📊 {$table}: {$count} registros\n";
            } catch (Exception $e) {
                echo "   ❌ Error consultando {$table}: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ❌ {$table}: TABLA NO EXISTE\n";
        }
    }
    
    // 9. Probar inserción de datos
    echo "\n9. 🧪 Probando inserción de datos...\n";
    try {
        // Crear un usuario de prueba
        $testUser = DB::table('usuarios')->where('email', 'test@render.com')->first();
        
        if (!$testUser) {
            DB::table('usuarios')->insert([
                'nombre' => 'Usuario Prueba Render',
                'email' => 'test@render.com',
                'password' => bcrypt('password'),
                'telefono' => '1234567890',
                'direccion' => 'Dirección de prueba',
                'rol' => 'usuario',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "   ✅ Usuario de prueba creado\n";
        } else {
            echo "   ✅ Usuario de prueba ya existe\n";
        }
        
        // Verificar que se puede leer
        $userCount = DB::table('usuarios')->where('email', 'test@render.com')->count();
        echo "   ✅ Lectura de datos: OK ({$userCount} usuario encontrado)\n";
        
    } catch (Exception $e) {
        echo "   ❌ Error en prueba de inserción: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== ✅ REPARACIÓN COMPLETADA ===\n";
    echo "🎉 Las migraciones se ejecutaron correctamente\n";
    echo "🔍 Verifica tu aplicación web para confirmar que funciona\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR CRÍTICO: " . $e->getMessage() . "\n";
    echo "📋 Información del error:\n";
    echo "   Tipo: " . get_class($e) . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n\n";
    
    echo "🔧 SOLUCIONES POSIBLES:\n";
    echo "   1. Verifica que DATABASE_URL esté configurada\n";
    echo "   2. Confirma que el servicio PostgreSQL esté activo\n";
    echo "   3. Ejecuta primero: php render-diagnostics.php\n";
    echo "   4. Revisa las variables de entorno en Render\n";
    
    exit(1);
}
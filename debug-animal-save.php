<?php
/**
 * Script de debugging para problemas de guardado de animales en Render
 * Ejecutar en Render Shell: php debug-animal-save.php
 * Ejecutar local: php artisan tinker --execute="require 'debug-animal-save.php'"
 */

// Cargar Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DIAGNÓSTICO DE GUARDADO DE ANIMALES\n";
echo "=====================================\n\n";

// 1. Verificar configuración de base de datos
echo "1. 📊 CONFIGURACIÓN DE BASE DE DATOS:\n";
echo "   DB_CONNECTION: " . config('database.default', 'no configurado') . "\n";
echo "   DATABASE_URL: " . (env('DATABASE_URL') ? 'configurado ✅' : 'NO configurado ❌') . "\n";

if (env('DATABASE_URL')) {
    $parsed = parse_url(env('DATABASE_URL'));
    echo "   Host: " . ($parsed['host'] ?? 'no encontrado') . "\n";
    echo "   Database: " . (ltrim($parsed['path'] ?? '', '/')) . "\n";
}

// 2. Probar conexión a base de datos
echo "\n2. 🔌 PRUEBA DE CONEXIÓN:\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "   Conexión: ✅ EXITOSA\n";
    echo "   Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
} catch (Exception $e) {
    echo "   Conexión: ❌ FALLÓ\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

// 3. Verificar tablas necesarias
echo "\n3. 📋 VERIFICAR TABLAS:\n";
try {
    $tables = ['animales', 'fundaciones', 'users'];
    foreach ($tables as $table) {
        $exists = DB::getSchemaBuilder()->hasTable($table);
        echo "   Tabla '$table': " . ($exists ? '✅ existe' : '❌ no existe') . "\n";
    }
} catch (Exception $e) {
    echo "   Error verificando tablas: " . $e->getMessage() . "\n";
}

// 4. Verificar estructura de tabla animales
echo "\n4. 🏗️ ESTRUCTURA TABLA ANIMALES:\n";
try {
    if (DB::getSchemaBuilder()->hasTable('animales')) {
        $columns = DB::getSchemaBuilder()->getColumnListing('animales');
        echo "   Columnas: " . implode(', ', $columns) . "\n";
        
        // Contar registros
        $count = DB::table('animales')->count();
        echo "   Registros actuales: $count\n";
    } else {
        echo "   ❌ Tabla 'animales' no existe\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

// 5. Probar inserción de prueba
echo "\n5. 🧪 PRUEBA DE INSERCIÓN:\n";
try {
    // Verificar si existe una fundación para la prueba
    $fundacion = DB::table('fundaciones')->first();
    
    if (!$fundacion) {
        echo "   ❌ No hay fundaciones en la base de datos\n";
        echo "   Creando fundación de prueba...\n";
        
        // Crear usuario de prueba
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Crear fundación de prueba
        $fundacionId = DB::table('fundaciones')->insertGetId([
            'user_id' => $userId,
            'nombre' => 'Fundación Test',
            'descripcion' => 'Fundación de prueba',
            'telefono' => '123456789',
            'email' => 'test@fundacion.com',
            'direccion' => 'Dirección test',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "   ✅ Fundación de prueba creada (ID: $fundacionId)\n";
        $fundacion = (object)['id' => $fundacionId];
    }
    
    // Intentar insertar animal de prueba
    $animalId = DB::table('animales')->insertGetId([
        'fundacion_id' => $fundacion->id,
        'nombre' => 'Animal Test',
        'especie' => 'perro',
        'raza' => 'Mestizo',
        'edad' => 2,
        'sexo' => 'macho',
        'tamano' => 'mediano',
        'descripcion' => 'Animal de prueba para debugging',
        'estado' => 'disponible',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "   ✅ Animal de prueba insertado (ID: $animalId)\n";
    
    // Verificar que se guardó
    $animal = DB::table('animales')->find($animalId);
    if ($animal) {
        echo "   ✅ Animal recuperado correctamente\n";
        echo "   Nombre: " . $animal->nombre . "\n";
    } else {
        echo "   ❌ No se pudo recuperar el animal insertado\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error en prueba de inserción: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

// 6. Verificar logs recientes
echo "\n6. 📝 LOGS RECIENTES:\n";
try {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        $logs = file_get_contents($logPath);
        $recentLogs = array_slice(explode("\n", $logs), -20);
        foreach ($recentLogs as $log) {
            if (trim($log) && (strpos($log, 'ERROR') !== false || strpos($log, 'animal') !== false)) {
                echo "   " . trim($log) . "\n";
            }
        }
    } else {
        echo "   ❌ Archivo de log no encontrado\n";
    }
} catch (Exception $e) {
    echo "   Error leyendo logs: " . $e->getMessage() . "\n";
}

echo "\n🎯 RECOMENDACIONES:\n";
echo "==================\n";

if (!env('DATABASE_URL')) {
    echo "1. ❌ CRÍTICO: Configurar DATABASE_URL en Render\n";
    echo "   - Ve a tu servicio PostgreSQL en Render\n";
    echo "   - Copia la Internal Database URL\n";
    echo "   - Agrégala como variable DATABASE_URL en tu Web Service\n\n";
}

if (!DB::getSchemaBuilder()->hasTable('animales')) {
    echo "2. ❌ CRÍTICO: Ejecutar migraciones\n";
    echo "   - En Render Shell: php artisan migrate --force\n\n";
}

echo "3. 🔧 Para debugging adicional:\n";
echo "   - Revisa los logs de Render en tiempo real\n";
echo "   - Verifica que las variables de entorno estén configuradas\n";
echo "   - Asegúrate de que el servicio PostgreSQL esté activo\n\n";

echo "✅ Diagnóstico completado.\n";
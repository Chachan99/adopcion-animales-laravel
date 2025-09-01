<?php

/**
 * Script de configuración para Render.com
 * Ejecuta migraciones y seeders en producción
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CONFIGURACIÓN INICIAL PARA RENDER ===\n\n";

try {
    // Verificar conexión
    echo "1. Verificando conexión a PostgreSQL...\n";
    
    try {
        // Usar DATABASE_URL si está disponible (Render lo proporciona automáticamente)
        if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
            echo "📡 Usando DATABASE_URL proporcionada por Render\n";
            $pdo = new PDO($_ENV['DATABASE_URL']);
        } else {
            // Fallback a Laravel DB connection
            $pdo = DB::connection()->getPdo();
        }
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "   ✅ Conexión exitosa\n";
        echo "   📊 Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
        echo "   🏠 Host: " . config('database.connections.pgsql.host') . "\n";
        echo "   🗄️ Database: " . config('database.connections.pgsql.database') . "\n\n";
    } catch (PDOException $e) {
        echo "❌ ERROR de conexión: " . $e->getMessage() . "\n";
        echo "🔧 Verificaciones necesarias:\n";
        echo "    1. DATABASE_URL configurada: " . (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL']) ? "✅" : "❌") . "\n";
        echo "    2. DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'no configurado') . "\n";
        throw $e;
    }
    
    // Ejecutar migraciones (fresh para limpiar)
    echo "2. Ejecutando migraciones...\n";
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo "   ✅ Migraciones completadas\n\n";
    
    // Ejecutar seeders
    echo "3. Ejecutando seeders...\n";
    Artisan::call('db:seed', ['--force' => true]);
    echo "   ✅ Seeders completados\n\n";
    
    // Limpiar y optimizar cache
    echo "4. Optimizando aplicación...\n";
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    echo "   ✅ Cache optimizado\n\n";
    
    // Verificar datos
    echo "5. Verificando datos creados...\n";
    $userCount = DB::table('usuarios')->count();
    $animalCount = DB::table('animales')->count();
    $noticiaCount = DB::table('noticias')->count();
    
    echo "   👥 Usuarios: $userCount\n";
    echo "   🐕 Animales: $animalCount\n";
    echo "   📰 Noticias: $noticiaCount\n\n";
    
    echo "=== ✅ CONFIGURACIÓN COMPLETADA EXITOSAMENTE ===\n";
    echo "🚀 La aplicación está lista para usar en Render\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n📋 Información del error:\n";
    echo "   Tipo: " . get_class($e) . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n\n";
    
    echo "🔧 Verificaciones necesarias:\n";
    echo "   1. APP_KEY configurada: " . (config('app.key') ? '✅' : '❌') . "\n";
    echo "   2. DB_HOST configurado: " . (config('database.connections.pgsql.host') ? '✅' : '❌') . "\n";
    echo "   3. DB_PASSWORD configurado: " . (config('database.connections.pgsql.password') ? '✅' : '❌') . "\n";
    echo "   4. APP_ENV: " . config('app.env') . "\n\n";
    
    echo "=== ❌ CONFIGURACIÓN FALLIDA ===\n";
    exit(1);
}
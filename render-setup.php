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

// Función para parsear DATABASE_URL y configurar variables individuales
function parseDatabaseUrl() {
    $databaseUrl = getenv('DATABASE_URL');
    if ($databaseUrl) {
        echo "📋 Parseando DATABASE_URL...\n";
        $parsed = parse_url($databaseUrl);
        
        if ($parsed) {
            putenv('DB_CONNECTION=pgsql');
            putenv('DB_HOST=' . ($parsed['host'] ?? ''));
            putenv('DB_PORT=' . ($parsed['port'] ?? '5432'));
            putenv('DB_DATABASE=' . ltrim($parsed['path'] ?? '', '/'));
            putenv('DB_USERNAME=' . ($parsed['user'] ?? ''));
            putenv('DB_PASSWORD=' . ($parsed['pass'] ?? ''));
            
            echo "✅ Variables de base de datos configuradas desde DATABASE_URL\n";
            return true;
        }
    }
    return false;
}

// Intentar parsear DATABASE_URL si está disponible
parseDatabaseUrl();

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
        echo "    1. DATABASE_URL configurada: " . (getenv('DATABASE_URL') ? '✅' : '❌') . "\n";
        if (getenv('DATABASE_URL')) {
            echo "    DATABASE_URL: " . substr(getenv('DATABASE_URL'), 0, 50) . "...\n";
        }
        echo "    2. DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'no configurado') . "\n";
        echo "    3. DB_HOST: " . (getenv('DB_HOST') ?: 'no configurado') . "\n";
        echo "    4. DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'no configurado') . "\n";
        echo "    5. DB_USERNAME: " . (getenv('DB_USERNAME') ? '✅' : '❌') . "\n";
        echo "    6. DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '✅' : '❌') . "\n";
        
        if (!getenv('DATABASE_URL')) {
            echo "\n📋 INSTRUCCIONES PARA CONFIGURAR DATABASE_URL:\n";
            echo "    1. Ve a https://dashboard.render.com\n";
            echo "    2. Crea un nuevo servicio PostgreSQL\n";
            echo "    3. Copia la 'Internal Database URL'\n";
            echo "    4. Agrégala como variable de entorno DATABASE_URL\n";
            echo "    5. Redeploy la aplicación\n";
            echo "    \n";
            echo "    Ver RENDER_DATABASE_SETUP.md para instrucciones detalladas\n";
        }
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
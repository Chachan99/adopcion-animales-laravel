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
            // Configurar tanto putenv() como $_ENV para compatibilidad completa
            $dbVars = [
                'DB_CONNECTION' => 'pgsql',
                'DB_HOST' => $parsed['host'] ?? '',
                'DB_PORT' => $parsed['port'] ?? '5432',
                'DB_DATABASE' => ltrim($parsed['path'] ?? '', '/'),
                'DB_USERNAME' => $parsed['user'] ?? '',
                'DB_PASSWORD' => $parsed['pass'] ?? ''
            ];
            
            foreach ($dbVars as $key => $value) {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
            
            echo "✅ Variables de base de datos configuradas desde DATABASE_URL\n";
            echo "🔍 DB_HOST configurado: " . $_ENV['DB_HOST'] . "\n";
            echo "🔍 DB_DATABASE configurado: " . $_ENV['DB_DATABASE'] . "\n";
            echo "🔍 DB_USERNAME configurado: " . ($_ENV['DB_USERNAME'] ? 'CONFIGURADO' : 'NO CONFIGURADO') . "\n";
            echo "🔍 DB_PASSWORD configurado: " . ($_ENV['DB_PASSWORD'] ? 'CONFIGURADO' : 'NO CONFIGURADO') . "\n";
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
        // Verificar que el driver PostgreSQL esté disponible
        if (!in_array('pgsql', PDO::getAvailableDrivers())) {
            throw new Exception('Driver PostgreSQL (pgsql) no está disponible en PDO');
        }
        
        // Usar DATABASE_URL si está disponible (Render lo proporciona automáticamente)
        if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
            echo "📡 Usando DATABASE_URL proporcionada por Render\n";
            echo "🔍 Driver pgsql confirmado disponible\n";
            
            // Diagnóstico detallado de DATABASE_URL
            echo "🔍 DATABASE_URL completa: " . $_ENV['DATABASE_URL'] . "\n";
            
            // Parsear DATABASE_URL manualmente para verificar formato
            $url_parts = parse_url($_ENV['DATABASE_URL']);
            if (!$url_parts || !isset($url_parts['scheme'])) {
                throw new Exception('DATABASE_URL tiene formato inválido');
            }
            
            echo "🔍 Esquema detectado: " . $url_parts['scheme'] . "\n";
            
            // Crear DSN manualmente para PostgreSQL
            $host = $url_parts['host'] ?? 'localhost';
            $port = $url_parts['port'] ?? 5432;
            $dbname = ltrim($url_parts['path'] ?? '', '/');
            $user = $url_parts['user'] ?? '';
            $pass = $url_parts['pass'] ?? '';
            
            // Mostrar valores parseados para diagnóstico
            echo "🔍 Host parseado: '{$host}'\n";
            echo "🔍 Puerto parseado: '{$port}'\n";
            echo "🔍 Base de datos parseada: '{$dbname}'\n";
            echo "🔍 Usuario parseado: '" . (empty($user) ? 'VACÍO' : 'CONFIGURADO') . "'\n";
            echo "🔍 Contraseña parseada: '" . (empty($pass) ? 'VACÍA' : 'CONFIGURADA') . "'\n";
            
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            echo "🔍 DSN construido: {$dsn}\n";
            
            // Opciones específicas para PostgreSQL
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            echo "🔄 Intentando conexión con DSN construido...\n";
            $pdo = new PDO($dsn, $user, $pass, $options);
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
    
    // Ejecutar migraciones (solo migrate para producción)
    echo "2. Ejecutando migraciones...\n";
    Artisan::call('migrate', ['--force' => true]);
    echo "   ✅ Migraciones completadas\n\n";
    
    // Ejecutar seeders solo si no hay usuarios (condicional para producción)
    echo "3. Verificando necesidad de seeders...\n";
    $userCount = \App\Models\Usuario::count();
    if ($userCount === 0) {
        echo "   No hay usuarios, ejecutando AdminSeeder...\n";
        Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        echo "   ✅ AdminSeeder completado\n";
    } else {
        echo "   Ya existen usuarios ($userCount), omitiendo seeders\n";
    }
    echo "\n";
    
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
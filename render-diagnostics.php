<?php

/**
 * Script de diagnóstico para problemas de base de datos en Render.com
 * Ejecutar con: php render-diagnostics.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNÓSTICO DE BASE DE DATOS EN RENDER ===\n\n";

// 1. Verificar variables de entorno críticas
echo "1. 🔍 VERIFICANDO VARIABLES DE ENTORNO:\n";
$criticalVars = [
    'DATABASE_URL' => getenv('DATABASE_URL'),
    'DB_CONNECTION' => getenv('DB_CONNECTION'),
    'DB_HOST' => getenv('DB_HOST'),
    'DB_PORT' => getenv('DB_PORT'),
    'DB_DATABASE' => getenv('DB_DATABASE'),
    'DB_USERNAME' => getenv('DB_USERNAME'),
    'DB_PASSWORD' => getenv('DB_PASSWORD') ? '[CONFIGURADA]' : '[NO CONFIGURADA]',
    'APP_KEY' => getenv('APP_KEY') ? '[CONFIGURADA]' : '[NO CONFIGURADA]',
    'APP_ENV' => getenv('APP_ENV'),
    'RENDER' => getenv('RENDER')
];

foreach ($criticalVars as $var => $value) {
    $status = $value ? '✅' : '❌';
    echo "   {$status} {$var}: " . ($value ?: '[NO CONFIGURADA]') . "\n";
}

// 2. Verificar driver PostgreSQL
echo "\n2. 🔍 VERIFICANDO DRIVER POSTGRESQL:\n";
echo "   📋 Drivers PDO disponibles: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
echo "   ✅ Driver pgsql: " . (in_array('pgsql', PDO::getAvailableDrivers()) ? 'DISPONIBLE' : 'NO DISPONIBLE') . "\n";
echo "   ✅ Extensión pdo_pgsql: " . (extension_loaded('pdo_pgsql') ? 'CARGADA' : 'NO CARGADA') . "\n";

// 3. Parsear DATABASE_URL si existe
if (getenv('DATABASE_URL')) {
    echo "\n3. 🔍 PARSEANDO DATABASE_URL:\n";
    $parsed = parse_url(getenv('DATABASE_URL'));
    if ($parsed) {
        echo "   ✅ Esquema: " . ($parsed['scheme'] ?? 'NO DEFINIDO') . "\n";
        echo "   ✅ Host: " . ($parsed['host'] ?? 'NO DEFINIDO') . "\n";
        echo "   ✅ Puerto: " . ($parsed['port'] ?? 'NO DEFINIDO') . "\n";
        echo "   ✅ Base de datos: " . (ltrim($parsed['path'] ?? '', '/') ?: 'NO DEFINIDA') . "\n";
        echo "   ✅ Usuario: " . ($parsed['user'] ? 'CONFIGURADO' : 'NO CONFIGURADO') . "\n";
        echo "   ✅ Contraseña: " . ($parsed['pass'] ? 'CONFIGURADA' : 'NO CONFIGURADA') . "\n";
    } else {
        echo "   ❌ ERROR: No se pudo parsear DATABASE_URL\n";
    }
} else {
    echo "\n3. ❌ DATABASE_URL NO CONFIGURADA\n";
}

// 4. Probar conexión directa
echo "\n4. 🔍 PROBANDO CONEXIÓN DIRECTA:\n";
try {
    if (getenv('DATABASE_URL')) {
        $parsed = parse_url(getenv('DATABASE_URL'));
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $parsed['host'],
            $parsed['port'] ?? 5432,
            ltrim($parsed['path'], '/')
        );
        
        $pdo = new PDO($dsn, $parsed['user'], $parsed['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "   ✅ Conexión PDO exitosa\n";
        echo "   📊 Versión PostgreSQL: " . $pdo->query('SELECT version()')->fetchColumn() . "\n";
        
        // Verificar si las tablas existen
        $tables = $pdo->query(
            "SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename"
        )->fetchAll(PDO::FETCH_COLUMN);
        
        echo "   📋 Tablas encontradas (" . count($tables) . "): " . implode(', ', $tables) . "\n";
        
        // Verificar datos en tablas principales
        $mainTables = ['usuarios', 'animales', 'noticias', 'solicitudes_adopcion'];
        foreach ($mainTables as $table) {
            if (in_array($table, $tables)) {
                $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
                echo "   📊 Registros en {$table}: {$count}\n";
            } else {
                echo "   ❌ Tabla {$table}: NO EXISTE\n";
            }
        }
        
    } else {
        echo "   ❌ No se puede probar conexión: DATABASE_URL no configurada\n";
    }
} catch (Exception $e) {
    echo "   ❌ ERROR de conexión: " . $e->getMessage() . "\n";
    echo "   📋 Código de error: " . $e->getCode() . "\n";
}

// 5. Probar conexión Laravel
echo "\n5. 🔍 PROBANDO CONEXIÓN LARAVEL:\n";
try {
    $connection = DB::connection();
    $pdo = $connection->getPdo();
    echo "   ✅ Conexión Laravel exitosa\n";
    echo "   📊 Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    
    // Probar una consulta simple
    $result = DB::select('SELECT 1 as test');
    echo "   ✅ Consulta de prueba exitosa\n";
    
} catch (Exception $e) {
    echo "   ❌ ERROR en conexión Laravel: " . $e->getMessage() . "\n";
}

// 6. Verificar estado de migraciones
echo "\n6. 🔍 VERIFICANDO MIGRACIONES:\n";
try {
    // Verificar si la tabla migrations existe
    $migrationTable = DB::select(
        "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'migrations')"
    )[0]->exists ?? false;
    
    if ($migrationTable) {
        echo "   ✅ Tabla migrations existe\n";
        $migrations = DB::table('migrations')->count();
        echo "   📊 Migraciones ejecutadas: {$migrations}\n";
        
        // Mostrar últimas migraciones
        $lastMigrations = DB::table('migrations')
            ->orderBy('batch', 'desc')
            ->limit(5)
            ->pluck('migration');
        
        echo "   📋 Últimas migraciones:\n";
        foreach ($lastMigrations as $migration) {
            echo "      - {$migration}\n";
        }
    } else {
        echo "   ❌ Tabla migrations NO existe - migraciones no ejecutadas\n";
    }
} catch (Exception $e) {
    echo "   ❌ ERROR verificando migraciones: " . $e->getMessage() . "\n";
}

// 7. Verificar permisos de escritura
echo "\n7. 🔍 VERIFICANDO PERMISOS DE ESCRITURA:\n";
try {
    // Intentar crear una tabla temporal
    DB::statement('CREATE TEMPORARY TABLE test_write (id SERIAL PRIMARY KEY, test_data VARCHAR(50))');
    DB::statement('INSERT INTO test_write (test_data) VALUES (?)', ['test']);
    $result = DB::select('SELECT * FROM test_write')[0];
    DB::statement('DROP TABLE test_write');
    
    echo "   ✅ Permisos de escritura: OK\n";
    echo "   ✅ Datos de prueba insertados y leídos correctamente\n";
} catch (Exception $e) {
    echo "   ❌ ERROR en permisos de escritura: " . $e->getMessage() . "\n";
}

// 8. Recomendaciones
echo "\n8. 📋 RECOMENDACIONES:\n";

if (!getenv('DATABASE_URL')) {
    echo "   🔧 CRÍTICO: Configurar DATABASE_URL en Render\n";
    echo "      1. Ve a tu servicio PostgreSQL en Render\n";
    echo "      2. Copia la 'Internal Database URL'\n";
    echo "      3. Agrégala como variable DATABASE_URL en tu Web Service\n";
}

if (!getenv('APP_KEY')) {
    echo "   🔧 CRÍTICO: Configurar APP_KEY\n";
    echo "      Ejecuta: php artisan key:generate --show\n";
    echo "      Y agrega el resultado como variable APP_KEY en Render\n";
}

if (!in_array('pgsql', PDO::getAvailableDrivers())) {
    echo "   🔧 CRÍTICO: Driver PostgreSQL no disponible\n";
    echo "      Verifica que ext-pdo_pgsql esté en composer.json\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
echo "\n💡 PRÓXIMOS PASOS:\n";
echo "   1. Revisa los errores marcados con ❌\n";
echo "   2. Configura las variables faltantes en Render\n";
echo "   3. Ejecuta un nuevo despliegue\n";
echo "   4. Vuelve a ejecutar este diagnóstico\n";
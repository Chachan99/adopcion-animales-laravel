<?php
/**
 * Script de Diagnóstico para Reinicios en Render
 * 
 * Este script ayuda a identificar las causas comunes de reinicios
 * constantes en aplicaciones Laravel desplegadas en Render.com
 */

echo "=== DIAGNÓSTICO DE REINICIOS EN RENDER ===\n\n";

// 1. Verificar memoria disponible
echo "1. VERIFICACIÓN DE MEMORIA:\n";
echo "Memoria actual: " . round(memory_get_usage() / 1024 / 1024, 2) . " MB\n";
echo "Memoria pico: " . round(memory_get_peak_usage() / 1024 / 1024, 2) . " MB\n";
echo "Límite de memoria: " . ini_get('memory_limit') . "\n\n";

// 2. Verificar variables de entorno críticas
echo "2. VARIABLES DE ENTORNO CRÍTICAS:\n";
$critical_vars = [
    'APP_ENV',
    'APP_DEBUG', 
    'APP_KEY',
    'DATABASE_URL',
    'DB_CONNECTION',
    'SESSION_DRIVER',
    'CACHE_DRIVER',
    'QUEUE_CONNECTION'
];

foreach ($critical_vars as $var) {
    $value = getenv($var);
    if ($value === false) {
        echo "❌ $var: NO CONFIGURADA\n";
    } else {
        // Ocultar valores sensibles
        if (in_array($var, ['APP_KEY', 'DATABASE_URL'])) {
            echo "✅ $var: [CONFIGURADA]\n";
        } else {
            echo "✅ $var: $value\n";
        }
    }
}
echo "\n";

// 3. Verificar configuración de Laravel
echo "3. CONFIGURACIÓN DE LARAVEL:\n";
try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        
        if (file_exists(__DIR__ . '/bootstrap/app.php')) {
            $app = require_once __DIR__ . '/bootstrap/app.php';
            
            echo "✅ Laravel bootstrap: OK\n";
            
            // Verificar configuración de cache
            $cacheDriver = env('CACHE_DRIVER', 'file');
            echo "Cache driver: $cacheDriver\n";
            
            // Verificar configuración de sesiones
            $sessionDriver = env('SESSION_DRIVER', 'file');
            echo "Session driver: $sessionDriver\n";
            
            // Verificar configuración de cola
            $queueConnection = env('QUEUE_CONNECTION', 'sync');
            echo "Queue connection: $queueConnection\n";
        }
    } else {
        echo "❌ Vendor autoload no encontrado\n";
    }
} catch (Exception $e) {
    echo "❌ Error en Laravel: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Verificar archivos de log
echo "4. VERIFICACIÓN DE LOGS:\n";
$logPath = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logPath)) {
    $logSize = filesize($logPath);
    echo "✅ Log de Laravel: " . round($logSize / 1024 / 1024, 2) . " MB\n";
    
    if ($logSize > 50 * 1024 * 1024) { // 50MB
        echo "⚠️  ADVERTENCIA: Log muy grande (>50MB) - puede causar problemas de memoria\n";
    }
    
    // Leer últimas líneas del log
    $handle = fopen($logPath, 'r');
    if ($handle) {
        fseek($handle, -2048, SEEK_END); // Últimos 2KB
        $lastLines = fread($handle, 2048);
        fclose($handle);
        
        if (strpos($lastLines, 'FATAL') !== false || strpos($lastLines, 'ERROR') !== false) {
            echo "❌ Se encontraron errores recientes en el log\n";
        } else {
            echo "✅ No se encontraron errores fatales recientes\n";
        }
    }
} else {
    echo "❌ Log de Laravel no encontrado\n";
}
echo "\n";

// 5. Verificar conexión a base de datos
echo "5. VERIFICACIÓN DE BASE DE DATOS:\n";
try {
    $databaseUrl = getenv('DATABASE_URL');
    if ($databaseUrl) {
        $parsed = parse_url($databaseUrl);
        $host = $parsed['host'] ?? 'unknown';
        $dbname = ltrim($parsed['path'] ?? '', '/');
        
        echo "Host: $host\n";
        echo "Base de datos: $dbname\n";
        
        // Intentar conexión
        $pdo = new PDO($databaseUrl);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->query('SELECT 1');
        if ($stmt) {
            echo "✅ Conexión a base de datos: OK\n";
        }
    } else {
        echo "❌ DATABASE_URL no configurada\n";
    }
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Verificar procesos PHP
echo "6. INFORMACIÓN DEL SISTEMA:\n";
echo "Versión PHP: " . PHP_VERSION . "\n";
echo "SAPI: " . PHP_SAPI . "\n";
echo "Max execution time: " . ini_get('max_execution_time') . "s\n";
echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "\n";

// 7. Recomendaciones
echo "=== RECOMENDACIONES PARA EVITAR REINICIOS ===\n\n";

echo "1. OPTIMIZACIÓN DE MEMORIA:\n";
echo "   - Configurar CACHE_DRIVER=redis en lugar de file\n";
echo "   - Configurar SESSION_DRIVER=redis en lugar de file\n";
echo "   - Limpiar logs regularmente\n";
echo "   - Optimizar consultas de base de datos\n\n";

echo "2. CONFIGURACIÓN RECOMENDADA PARA RENDER:\n";
echo "   APP_ENV=production\n";
echo "   APP_DEBUG=false\n";
echo "   CACHE_DRIVER=redis\n";
echo "   SESSION_DRIVER=redis\n";
echo "   QUEUE_CONNECTION=redis\n";
echo "   LOG_CHANNEL=stderr\n\n";

echo "3. COMANDOS DE OPTIMIZACIÓN:\n";
echo "   php artisan config:cache\n";
echo "   php artisan route:cache\n";
echo "   php artisan view:cache\n";
echo "   php artisan optimize\n\n";

echo "4. MONITOREO:\n";
echo "   - Revisar logs en Render Dashboard\n";
echo "   - Configurar health checks\n";
echo "   - Monitorear uso de memoria\n";
echo "   - Verificar tiempo de respuesta\n\n";

echo "5. SI EL PROBLEMA PERSISTE:\n";
echo "   - Considerar upgrade del plan de Render\n";
echo "   - Implementar Redis para cache y sesiones\n";
echo "   - Optimizar código para reducir uso de memoria\n";
echo "   - Configurar worker processes adecuados\n\n";

echo "=== FIN DEL DIAGNÓSTICO ===\n";
?>
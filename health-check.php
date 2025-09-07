<?php
/**
 * Health Check Script para Render
 * 
 * Este script verifica el estado de la aplicación y puede ser usado
 * para monitoreo automático y detección temprana de problemas.
 */

header('Content-Type: application/json');

try {
    // Verificar autoload de Composer
    if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
        throw new Exception('Vendor autoload not found');
    }
    
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Verificar bootstrap de Laravel
    if (!file_exists(__DIR__ . '/bootstrap/app.php')) {
        throw new Exception('Laravel bootstrap not found');
    }
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Información básica del sistema
    $health = [
        'status' => 'ok',
        'timestamp' => date('Y-m-d H:i:s'),
        'environment' => env('APP_ENV', 'unknown'),
        'debug_mode' => env('APP_DEBUG', false),
        'memory' => [
            'current' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
            'limit' => ini_get('memory_limit')
        ],
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ];
    
    // Verificar base de datos
    try {
        $pdo = DB::connection()->getPdo();
        $health['database'] = [
            'status' => 'connected',
            'driver' => DB::connection()->getDriverName()
        ];
        
        // Test simple query
        $result = DB::select('SELECT 1 as test');
        $health['database']['query_test'] = 'ok';
        
    } catch (Exception $e) {
        $health['database'] = [
            'status' => 'error',
            'error' => $e->getMessage()
        ];
        $health['status'] = 'warning';
    }
    
    // Verificar archivos críticos
    $criticalFiles = [
        '.env' => file_exists(__DIR__ . '/.env'),
        'storage/logs' => is_writable(__DIR__ . '/storage/logs'),
        'storage/framework/cache' => is_writable(__DIR__ . '/storage/framework/cache'),
        'storage/framework/sessions' => is_writable(__DIR__ . '/storage/framework/sessions'),
        'storage/framework/views' => is_writable(__DIR__ . '/storage/framework/views')
    ];
    
    $health['filesystem'] = $criticalFiles;
    
    // Verificar variables de entorno críticas
    $criticalEnvVars = [
        'APP_KEY' => !empty(env('APP_KEY')),
        'DB_CONNECTION' => !empty(env('DB_CONNECTION')),
        'DATABASE_URL' => !empty(env('DATABASE_URL'))
    ];
    
    $health['environment_vars'] = $criticalEnvVars;
    
    // Verificar si hay errores en el filesystem
    foreach ($criticalFiles as $file => $status) {
        if (!$status) {
            $health['status'] = 'warning';
            break;
        }
    }
    
    // Verificar variables de entorno
    foreach ($criticalEnvVars as $var => $status) {
        if (!$status) {
            $health['status'] = 'error';
            break;
        }
    }
    
    // Verificar uso de memoria
    $memoryUsage = memory_get_usage(true);
    $memoryLimit = ini_get('memory_limit');
    
    if ($memoryLimit !== '-1') {
        $limitBytes = str_replace(['K', 'M', 'G'], ['*1024', '*1024*1024', '*1024*1024*1024'], $memoryLimit);
        eval("\$limitBytes = $limitBytes;");
        
        $memoryPercentage = ($memoryUsage / $limitBytes) * 100;
        $health['memory']['usage_percentage'] = round($memoryPercentage, 2) . '%';
        
        if ($memoryPercentage > 80) {
            $health['status'] = 'warning';
            $health['warnings'][] = 'High memory usage detected';
        }
    }
    
    // Verificar logs recientes
    $logFile = __DIR__ . '/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logSize = filesize($logFile);
        $health['logs'] = [
            'size' => round($logSize / 1024 / 1024, 2) . ' MB',
            'writable' => is_writable($logFile)
        ];
        
        if ($logSize > 100 * 1024 * 1024) { // 100MB
            $health['status'] = 'warning';
            $health['warnings'][] = 'Log file is very large';
        }
    }
    
    http_response_code(200);
    echo json_encode($health, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'timestamp' => date('Y-m-d H:i:s'),
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
?>
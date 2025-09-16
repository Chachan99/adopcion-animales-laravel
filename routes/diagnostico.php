<?php

use Illuminate\Support\Facades\Route;

/**
 * Ruta de diagnóstico para verificar la configuración de S3 en producción
 * Acceder desde: https://tu-app.onrender.com/diagnostico-s3
 */
Route::get('/diagnostico-s3', function () {
    // Solo permitir en producción para evitar exposición de datos sensibles
    if (config('app.env') !== 'production') {
        return response('Solo disponible en producción', 403);
    }
    
    $diagnostico = [];
    
    // 1. Verificar variables de entorno
    $diagnostico['variables_entorno'] = [
        'FILESYSTEM_DISK' => config('filesystems.default'),
        'AWS_ACCESS_KEY_ID' => config('filesystems.disks.s3.key') ? 'CONFIGURADA' : 'NO CONFIGURADA',
        'AWS_SECRET_ACCESS_KEY' => config('filesystems.disks.s3.secret') ? 'CONFIGURADA' : 'NO CONFIGURADA',
        'AWS_DEFAULT_REGION' => config('filesystems.disks.s3.region'),
        'AWS_BUCKET' => config('filesystems.disks.s3.bucket'),
        'AWS_URL' => config('filesystems.disks.s3.url'),
    ];
    
    // 2. Verificar configuración de filesystems
    $diagnostico['configuracion_filesystems'] = [
        'default_disk' => config('filesystems.default'),
        's3_configurado' => config('filesystems.disks.s3') ? 'SÍ' : 'NO',
        's3_driver' => config('filesystems.disks.s3.driver') ?? 'NO CONFIGURADO',
    ];
    
    // 3. Verificar extensiones PHP
    $diagnostico['php_extensions'] = [
        'curl' => extension_loaded('curl') ? 'DISPONIBLE' : 'NO DISPONIBLE',
        'openssl' => extension_loaded('openssl') ? 'DISPONIBLE' : 'NO DISPONIBLE',
        'php_version' => PHP_VERSION,
    ];
    
    // 4. Verificar dependencias de Composer
    $diagnostico['dependencias'] = [
        'flysystem_s3' => class_exists('League\Flysystem\AwsS3V3\AwsS3V3Adapter') ? 'INSTALADA' : 'NO INSTALADA',
        'aws_sdk' => class_exists('Aws\S3\S3Client') ? 'INSTALADA' : 'NO INSTALADA',
    ];
    
    // 5. Test básico de conexión S3
    $diagnostico['test_s3'] = [];
    try {
        $disk = \Storage::disk('s3');
        
        // Intentar crear un archivo de prueba
        $testContent = 'Test file - ' . now();
        $testPath = 'test/diagnostico-' . time() . '.txt';
        
        $disk->put($testPath, $testContent);
        $diagnostico['test_s3']['escritura'] = 'EXITOSA';
        
        // Intentar leer el archivo
        $readContent = $disk->get($testPath);
        $diagnostico['test_s3']['lectura'] = $readContent === $testContent ? 'EXITOSA' : 'FALLIDA';
        
        // Obtener URL del archivo
        $url = $disk->url($testPath);
        $diagnostico['test_s3']['url_generada'] = $url;
        
        // Limpiar archivo de prueba
        $disk->delete($testPath);
        $diagnostico['test_s3']['limpieza'] = 'EXITOSA';
        
    } catch (\Exception $e) {
        $diagnostico['test_s3']['error'] = $e->getMessage();
        $diagnostico['test_s3']['estado'] = 'FALLIDO';
    }
    
    // 6. Información del entorno
    $diagnostico['entorno'] = [
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug') ? 'ACTIVADO' : 'DESACTIVADO',
        'timestamp' => now()->toDateTimeString(),
    ];
    
    // Generar HTML de respuesta
    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Diagnóstico S3 - Render</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h1 { color: #333; border-bottom: 2px solid #007cba; padding-bottom: 10px; }
            h2 { color: #555; margin-top: 30px; }
            .success { color: #28a745; font-weight: bold; }
            .error { color: #dc3545; font-weight: bold; }
            .warning { color: #ffc107; font-weight: bold; }
            .info { color: #17a2b8; font-weight: bold; }
            pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
            .status-ok { background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px; }
            .status-error { background: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔍 Diagnóstico de S3 en Render</h1>
            <p><strong>Timestamp:</strong> ' . $diagnostico['entorno']['timestamp'] . '</p>';
    
    foreach ($diagnostico as $seccion => $datos) {
        if ($seccion === 'entorno') continue;
        
        $html .= '<h2>' . ucfirst(str_replace('_', ' ', $seccion)) . '</h2>';
        $html .= '<pre>' . json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
    }
    
    $html .= '
            <h2>🎯 Recomendaciones</h2>
            <ul>';
    
    if ($diagnostico['variables_entorno']['FILESYSTEM_DISK'] !== 's3') {
        $html .= '<li class="error">❌ FILESYSTEM_DISK debe ser "s3"</li>';
    } else {
        $html .= '<li class="success">✅ FILESYSTEM_DISK configurado correctamente</li>';
    }
    
    if ($diagnostico['variables_entorno']['AWS_ACCESS_KEY_ID'] === 'NO CONFIGURADA') {
        $html .= '<li class="error">❌ Configurar AWS_ACCESS_KEY_ID en Render</li>';
    } else {
        $html .= '<li class="success">✅ AWS_ACCESS_KEY_ID configurada</li>';
    }
    
    if (isset($diagnostico['test_s3']['error'])) {
        $html .= '<li class="error">❌ Error en test S3: ' . $diagnostico['test_s3']['error'] . '</li>';
    } elseif (isset($diagnostico['test_s3']['escritura']) && $diagnostico['test_s3']['escritura'] === 'EXITOSA') {
        $html .= '<li class="success">✅ S3 funcionando correctamente</li>';
    }
    
    $html .= '
            </ul>
            <p><em>Este diagnóstico se ejecuta automáticamente en producción para verificar la configuración de S3.</em></p>
        </div>
    </body>
    </html>';
    
    return response($html)->header('Content-Type', 'text/html');
});
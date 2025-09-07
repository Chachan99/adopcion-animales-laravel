<?php

/**
 * Script de optimización de memoria para identificar y corregir problemas
 * que pueden causar reinicios en Render
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== ANÁLISIS DE OPTIMIZACIÓN DE MEMORIA ===\n\n";

// 1. Verificar configuración de memoria
echo "1. CONFIGURACIÓN DE MEMORIA:\n";
echo "   - Límite de memoria PHP: " . ini_get('memory_limit') . "\n";
echo "   - Memoria actual: " . round(memory_get_usage(true) / 1024 / 1024, 2) . " MB\n";
echo "   - Memoria pico: " . round(memory_get_peak_usage(true) / 1024 / 1024, 2) . " MB\n\n";

// 2. Verificar problemas comunes en el código
echo "2. ANÁLISIS DE CÓDIGO:\n";

// Verificar uso de Log::info en PublicoController
$publicoController = file_get_contents(__DIR__ . '/app/Http/Controllers/PublicoController.php');
if (strpos($publicoController, '\\Log::info') !== false) {
    echo "   ⚠️  PROBLEMA: Uso excesivo de Log::info en PublicoController\n";
    echo "      - Esto puede causar memory leaks en producción\n";
    echo "      - Recomendación: Remover logs de debug en producción\n\n";
} else {
    echo "   ✅ PublicoController: Sin logs excesivos\n\n";
}

// 3. Verificar consultas N+1
echo "3. CONSULTAS POTENCIALMENTE PROBLEMÁTICAS:\n";

$modelsToCheck = [
    'Animal.php' => 'fundacion',
    'PerfilFundacion.php' => 'usuario, animales, donaciones'
];

foreach ($modelsToCheck as $model => $relations) {
    echo "   - {$model}: Relaciones ({$relations})\n";
}
echo "   ⚠️  Asegurar uso de eager loading (with()) en controladores\n\n";

// 4. Verificar archivos de imagen
echo "4. OPTIMIZACIÓN DE IMÁGENES:\n";
$imageDir = __DIR__ . '/public/img';
if (is_dir($imageDir)) {
    $totalSize = 0;
    $fileCount = 0;
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($imageDir)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $totalSize += $file->getSize();
            $fileCount++;
        }
    }
    
    $totalSizeMB = round($totalSize / 1024 / 1024, 2);
    echo "   - Total de imágenes: {$fileCount} archivos\n";
    echo "   - Tamaño total: {$totalSizeMB} MB\n";
    
    if ($totalSizeMB > 100) {
        echo "   ⚠️  PROBLEMA: Muchas imágenes pueden afectar el rendimiento\n";
        echo "      - Recomendación: Optimizar imágenes o usar CDN\n";
    } else {
        echo "   ✅ Tamaño de imágenes aceptable\n";
    }
} else {
    echo "   ⚠️  Directorio de imágenes no encontrado\n";
}
echo "\n";

// 5. Recomendaciones específicas
echo "5. RECOMENDACIONES DE OPTIMIZACIÓN:\n";
echo "\n";
echo "   A. CONFIGURACIÓN DE PRODUCCIÓN:\n";
echo "      - APP_DEBUG=false\n";
echo "      - LOG_LEVEL=error\n";
echo "      - CACHE_DRIVER=redis (si disponible)\n";
echo "      - SESSION_DRIVER=redis (si disponible)\n";
echo "\n";
echo "   B. OPTIMIZACIONES DE CÓDIGO:\n";
echo "      - Usar eager loading: Animal::with('fundacion')\n";
echo "      - Limitar resultados: ->limit() o ->paginate()\n";
echo "      - Evitar consultas en loops\n";
echo "      - Remover logs de debug en producción\n";
echo "\n";
echo "   C. COMANDOS DE OPTIMIZACIÓN:\n";
echo "      - php artisan config:cache\n";
echo "      - php artisan route:cache\n";
echo "      - php artisan view:cache\n";
echo "      - php artisan optimize\n";
echo "\n";
echo "   D. MONITOREO:\n";
echo "      - Usar middleware de memoria implementado\n";
echo "      - Configurar health checks en Render\n";
echo "      - Revisar logs regularmente\n";
echo "\n";

// 6. Verificar variables de entorno críticas
echo "6. VARIABLES DE ENTORNO CRÍTICAS:\n";
$criticalVars = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'LOG_LEVEL' => 'error',
    'DB_CONNECTION' => 'pgsql'
];

foreach ($criticalVars as $var => $expected) {
    $current = env($var, 'no definida');
    $status = ($current == $expected) ? '✅' : '⚠️';
    echo "   {$status} {$var}: {$current}\n";
}

echo "\n=== FIN DEL ANÁLISIS ===\n";
echo "\nPara aplicar las optimizaciones, ejecutar:\n";
echo "1. Configurar variables de entorno según recomendaciones\n";
echo "2. Ejecutar comandos de optimización de Laravel\n";
echo "3. Desplegar en Render con configuración optimizada\n";
echo "4. Monitorear el health check: /health\n";
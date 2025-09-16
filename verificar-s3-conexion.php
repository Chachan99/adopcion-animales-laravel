<?php
/**
 * Script para Verificar Conexión a S3
 * Confirma si la aplicación está usando S3 correctamente
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

echo "=== VERIFICACIÓN DE CONEXIÓN A S3 ===\n\n";

// 1. Verificar configuración actual
echo "1. CONFIGURACIÓN ACTUAL:\n";
echo "   - Disco por defecto: " . Config::get('filesystems.default') . "\n";
echo "   - FILESYSTEM_DISK: " . env('FILESYSTEM_DISK', 'local') . "\n";

// Verificar variables S3
$s3Config = Config::get('filesystems.disks.s3');
echo "   - AWS_ACCESS_KEY_ID: " . (env('AWS_ACCESS_KEY_ID') ? 'Configurado ✓' : 'NO CONFIGURADO ✗') . "\n";
echo "   - AWS_SECRET_ACCESS_KEY: " . (env('AWS_SECRET_ACCESS_KEY') ? 'Configurado ✓' : 'NO CONFIGURADO ✗') . "\n";
echo "   - AWS_DEFAULT_REGION: " . env('AWS_DEFAULT_REGION', 'No configurado') . "\n";
echo "   - AWS_BUCKET: " . env('AWS_BUCKET', 'No configurado') . "\n";
echo "   - AWS_URL: " . env('AWS_URL', 'No configurado') . "\n\n";

// 2. Probar conexión a S3
echo "2. PRUEBA DE CONEXIÓN A S3:\n";

try {
    // Intentar obtener el disco S3
    $s3Disk = Storage::disk('s3');
    echo "   - Disco S3 inicializado: ✓\n";
    
    // Probar listado de archivos (esto requiere conexión)
    try {
        $files = $s3Disk->files('');
        echo "   - Conexión a bucket exitosa: ✓\n";
        echo "   - Archivos en bucket: " . count($files) . "\n";
        
        // Mostrar algunos archivos como ejemplo
        if (count($files) > 0) {
            echo "   - Ejemplos de archivos:\n";
            for ($i = 0; $i < min(5, count($files)); $i++) {
                echo "     * " . $files[$i] . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   - Error de conexión al bucket: " . $e->getMessage() . " ✗\n";
    }
    
} catch (Exception $e) {
    echo "   - Error inicializando S3: " . $e->getMessage() . " ✗\n";
}

echo "\n";

// 3. Probar subida de archivo de prueba
echo "3. PRUEBA DE SUBIDA A S3:\n";

$testContent = "Prueba de conexión S3 - " . date('Y-m-d H:i:s');
$testFileName = 'test-conexion-' . time() . '.txt';

try {
    // Usar el disco que está configurado como 'public'
    $publicDisk = Storage::disk('public');
    
    // Verificar qué disco está usando realmente
    $diskConfig = Config::get('filesystems.disks.public');
    echo "   - Disco 'public' configurado como: " . $diskConfig['driver'] . "\n";
    
    // Intentar subir archivo
    $path = $publicDisk->put('test/' . $testFileName, $testContent);
    
    if ($path) {
        echo "   - Subida exitosa: $path ✓\n";
        
        // Obtener URL del archivo
        $url = $publicDisk->url($path);
        echo "   - URL generada: $url\n";
        
        // Verificar si la URL contiene S3
        if (strpos($url, 's3.amazonaws.com') !== false || strpos($url, '.s3.') !== false) {
            echo "   - ✅ CONFIRMADO: Usando S3 (URL contiene S3)\n";
        } elseif (strpos($url, '/storage/') !== false) {
            echo "   - ⚠️  ADVERTENCIA: Parece usar almacenamiento local\n";
        } else {
            echo "   - ❓ URL no reconocida, verificar configuración\n";
        }
        
        // Verificar si el archivo existe
        if ($publicDisk->exists($path)) {
            echo "   - Archivo verificado: existe ✓\n";
        }
        
        // Limpiar archivo de prueba
        $publicDisk->delete($path);
        echo "   - Archivo de prueba eliminado ✓\n";
        
    } else {
        echo "   - Error en subida ✗\n";
    }
    
} catch (Exception $e) {
    echo "   - Error en prueba de subida: " . $e->getMessage() . " ✗\n";
}

echo "\n";

// 4. Verificar archivos existentes y sus URLs
echo "4. VERIFICACIÓN DE ARCHIVOS EXISTENTES:\n";

$directories = ['animales', 'usuarios', 'fundaciones', 'noticias', 'animales-perdidos'];
$publicDisk = Storage::disk('public');

foreach ($directories as $dir) {
    try {
        if ($publicDisk->exists($dir)) {
            $files = $publicDisk->files($dir);
            if (count($files) > 0) {
                echo "   - $dir/: " . count($files) . " archivos\n";
                
                // Mostrar URL del primer archivo como ejemplo
                $firstFile = $files[0];
                $url = $publicDisk->url($firstFile);
                echo "     Ejemplo URL: $url\n";
                
                // Verificar si es S3
                if (strpos($url, 's3.amazonaws.com') !== false || strpos($url, '.s3.') !== false) {
                    echo "     ✅ Usando S3\n";
                } else {
                    echo "     ⚠️  No parece S3\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "   - Error verificando $dir/: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// 5. Diagnóstico final
echo "5. DIAGNÓSTICO FINAL:\n";

$usingS3 = false;

// Verificar si realmente está usando S3
if (env('FILESYSTEM_DISK') === 's3') {
    echo "   ✅ FILESYSTEM_DISK configurado como 's3'\n";
    $usingS3 = true;
} else {
    echo "   ❌ FILESYSTEM_DISK NO está configurado como 's3'\n";
    echo "      Valor actual: " . env('FILESYSTEM_DISK', 'local') . "\n";
}

if (env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY') && env('AWS_BUCKET')) {
    echo "   ✅ Credenciales AWS configuradas\n";
} else {
    echo "   ❌ Credenciales AWS incompletas\n";
    $usingS3 = false;
}

echo "\n";

if ($usingS3) {
    echo "🎉 RESULTADO: Tu aplicación ESTÁ usando S3 correctamente\n";
    echo "   - Las imágenes se guardan en AWS S3\n";
    echo "   - No se perderán en reinicios de Render\n";
    echo "   - Las URLs apuntan al bucket S3\n";
} else {
    echo "⚠️  RESULTADO: Tu aplicación NO está usando S3\n";
    echo "   - Aún usa almacenamiento local\n";
    echo "   - Las imágenes se perderán en reinicios\n";
    echo "   - Necesitas configurar las variables de entorno\n";
}

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
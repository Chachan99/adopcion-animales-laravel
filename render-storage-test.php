<?php
/**
 * Script de Diagnóstico para Almacenamiento S3 en Render
 * Verifica la configuración y funcionalidad de AWS S3
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "=== DIAGNÓSTICO DE ALMACENAMIENTO S3 EN RENDER ===\n\n";

// 1. Verificar configuración de entorno
echo "1. CONFIGURACIÓN DE ENTORNO:\n";
echo "   - FILESYSTEM_DISK: " . env('FILESYSTEM_DISK', 'local') . "\n";
echo "   - AWS_ACCESS_KEY_ID: " . (env('AWS_ACCESS_KEY_ID') ? 'Configurado ✓' : 'NO CONFIGURADO ✗') . "\n";
echo "   - AWS_SECRET_ACCESS_KEY: " . (env('AWS_SECRET_ACCESS_KEY') ? 'Configurado ✓' : 'NO CONFIGURADO ✗') . "\n";
echo "   - AWS_DEFAULT_REGION: " . env('AWS_DEFAULT_REGION', 'No configurado') . "\n";
echo "   - AWS_BUCKET: " . env('AWS_BUCKET', 'No configurado') . "\n";
echo "   - AWS_URL: " . env('AWS_URL', 'No configurado') . "\n\n";

// 2. Verificar disco de almacenamiento actual
echo "2. DISCO DE ALMACENAMIENTO ACTUAL:\n";
$defaultDisk = config('filesystems.default');
echo "   - Disco por defecto: $defaultDisk\n";

try {
    $publicDisk = Storage::disk('public');
    echo "   - Disco 'public' disponible: ✓\n";
} catch (Exception $e) {
    echo "   - Error con disco 'public': " . $e->getMessage() . " ✗\n";
}

try {
    if (config('filesystems.disks.s3')) {
        $s3Disk = Storage::disk('s3');
        echo "   - Disco 's3' disponible: ✓\n";
    } else {
        echo "   - Disco 's3' no configurado ✗\n";
    }
} catch (Exception $e) {
    echo "   - Error con disco 's3': " . $e->getMessage() . " ✗\n";
}

echo "\n";

// 3. Probar subida de archivo de prueba
echo "3. PRUEBA DE SUBIDA DE ARCHIVO:\n";

$testContent = "Archivo de prueba - " . date('Y-m-d H:i:s');
$testFileName = 'test-' . time() . '.txt';

// Probar con disco público
try {
    echo "   Probando subida con disco 'public'...\n";
    $path = Storage::disk('public')->put('test/' . $testFileName, $testContent);
    if ($path) {
        echo "   - Subida exitosa: $path ✓\n";
        
        // Verificar si el archivo existe
        if (Storage::disk('public')->exists($path)) {
            echo "   - Archivo verificado: existe ✓\n";
            
            // Obtener URL del archivo
            $url = Storage::disk('public')->url($path);
            echo "   - URL del archivo: $url\n";
            
            // Limpiar archivo de prueba
            Storage::disk('public')->delete($path);
            echo "   - Archivo de prueba eliminado ✓\n";
        } else {
            echo "   - Error: archivo no encontrado después de subida ✗\n";
        }
    } else {
        echo "   - Error en subida ✗\n";
    }
} catch (Exception $e) {
    echo "   - Error en prueba con disco 'public': " . $e->getMessage() . " ✗\n";
}

// Probar con S3 si está configurado
if (env('FILESYSTEM_DISK') === 's3' && env('AWS_ACCESS_KEY_ID')) {
    try {
        echo "\n   Probando subida con disco 's3'...\n";
        $s3Path = Storage::disk('s3')->put('test/' . $testFileName, $testContent);
        if ($s3Path) {
            echo "   - Subida S3 exitosa: $s3Path ✓\n";
            
            // Verificar si el archivo existe en S3
            if (Storage::disk('s3')->exists($s3Path)) {
                echo "   - Archivo S3 verificado: existe ✓\n";
                
                // Obtener URL del archivo S3
                $s3Url = Storage::disk('s3')->url($s3Path);
                echo "   - URL S3 del archivo: $s3Url\n";
                
                // Limpiar archivo de prueba S3
                Storage::disk('s3')->delete($s3Path);
                echo "   - Archivo S3 de prueba eliminado ✓\n";
            } else {
                echo "   - Error: archivo S3 no encontrado después de subida ✗\n";
            }
        } else {
            echo "   - Error en subida S3 ✗\n";
        }
    } catch (Exception $e) {
        echo "   - Error en prueba con S3: " . $e->getMessage() . " ✗\n";
    }
}

echo "\n";

// 4. Verificar directorios existentes
echo "4. DIRECTORIOS DE IMÁGENES EXISTENTES:\n";
$directories = ['animales', 'usuarios', 'fundaciones', 'noticias', 'animales-perdidos'];

foreach ($directories as $dir) {
    try {
        $disk = Storage::disk('public');
        if ($disk->exists($dir)) {
            $files = $disk->files($dir);
            echo "   - $dir/: " . count($files) . " archivos ✓\n";
        } else {
            echo "   - $dir/: directorio no existe\n";
        }
    } catch (Exception $e) {
        echo "   - Error verificando $dir/: " . $e->getMessage() . " ✗\n";
    }
}

echo "\n";

// 5. Recomendaciones
echo "5. RECOMENDACIONES:\n";

if (env('FILESYSTEM_DISK') !== 's3') {
    echo "   ⚠️  FILESYSTEM_DISK no está configurado como 's3'\n";
    echo "      Agregar en variables de entorno de Render: FILESYSTEM_DISK=s3\n\n";
}

if (!env('AWS_ACCESS_KEY_ID') || !env('AWS_SECRET_ACCESS_KEY')) {
    echo "   ⚠️  Credenciales de AWS no configuradas\n";
    echo "      Configurar en Render Dashboard > Environment Variables:\n";
    echo "      - AWS_ACCESS_KEY_ID=tu_access_key\n";
    echo "      - AWS_SECRET_ACCESS_KEY=tu_secret_key\n";
    echo "      - AWS_DEFAULT_REGION=us-east-1\n";
    echo "      - AWS_BUCKET=tu_bucket_name\n";
    echo "      - AWS_URL=https://tu_bucket_name.s3.amazonaws.com\n\n";
}

if (!env('AWS_BUCKET')) {
    echo "   ⚠️  Bucket de S3 no configurado\n";
    echo "      1. Crear bucket en AWS S3\n";
    echo "      2. Configurar AWS_BUCKET en variables de entorno\n\n";
}

echo "6. PASOS PARA SOLUCIONAR:\n";
echo "   1. Verificar que el bucket S3 existe y es accesible\n";
echo "   2. Verificar permisos IAM del usuario AWS\n";
echo "   3. Configurar todas las variables de entorno en Render\n";
echo "   4. Hacer redeploy de la aplicación\n";
echo "   5. Ejecutar este script nuevamente para verificar\n\n";

echo "=== FIN DEL DIAGNÓSTICO ===\n";
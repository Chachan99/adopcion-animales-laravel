<?php
/**
 * Script de diagnóstico para verificar la configuración de almacenamiento
 * Este script debe ejecutarse en producción para diagnosticar problemas con S3
 */

echo "=== DIAGNÓSTICO DE ALMACENAMIENTO EN PRODUCCIÓN ===\n\n";

// 1. Verificar variables de entorno
echo "1. VARIABLES DE ENTORNO:\n";
$envVars = [
    'FILESYSTEM_DISK',
    'AWS_ACCESS_KEY_ID', 
    'AWS_SECRET_ACCESS_KEY',
    'AWS_DEFAULT_REGION',
    'AWS_BUCKET',
    'AWS_USE_PATH_STYLE_ENDPOINT'
];

foreach ($envVars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'NO DEFINIDA';
    if (in_array($var, ['AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY'])) {
        $value = $value !== 'NO DEFINIDA' ? substr($value, 0, 8) . '...' : 'NO DEFINIDA';
    }
    echo "   $var: $value\n";
}

echo "\n2. CONFIGURACIÓN DE PHP:\n";
echo "   PHP Version: " . PHP_VERSION . "\n";
echo "   Extensions loaded: " . (extension_loaded('curl') ? 'cURL ✓' : 'cURL ✗') . "\n";
echo "   OpenSSL: " . (extension_loaded('openssl') ? 'OpenSSL ✓' : 'OpenSSL ✗') . "\n";

// 3. Verificar si existe el archivo de configuración
echo "\n3. ARCHIVOS DE CONFIGURACIÓN:\n";
$configPath = __DIR__ . '/config/filesystems.php';
if (file_exists($configPath)) {
    echo "   filesystems.php: EXISTE ✓\n";
    
    // Leer contenido del archivo
    $content = file_get_contents($configPath);
    if (strpos($content, "'default' => env('FILESYSTEM_DISK', 'local')") !== false) {
        echo "   Configuración default: CORRECTA ✓\n";
    } else {
        echo "   Configuración default: INCORRECTA ✗\n";
    }
    
    if (strpos($content, "'s3' => [") !== false) {
        echo "   Configuración S3: EXISTE ✓\n";
    } else {
        echo "   Configuración S3: NO EXISTE ✗\n";
    }
} else {
    echo "   filesystems.php: NO EXISTE ✗\n";
}

// 4. Verificar directorio storage
echo "\n4. DIRECTORIO STORAGE:\n";
$storagePath = __DIR__ . '/storage/app/public/animales';
if (is_dir($storagePath)) {
    $files = scandir($storagePath);
    $imageFiles = array_filter($files, function($file) {
        return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
    });
    echo "   Directorio storage/app/public/animales: EXISTE\n";
    echo "   Imágenes locales encontradas: " . count($imageFiles) . "\n";
} else {
    echo "   Directorio storage/app/public/animales: NO EXISTE\n";
}

// 5. Test básico de S3 (si las credenciales están disponibles)
echo "\n5. TEST DE CONEXIÓN S3:\n";
$accessKey = getenv('AWS_ACCESS_KEY_ID') ?: $_ENV['AWS_ACCESS_KEY_ID'] ?? null;
$secretKey = getenv('AWS_SECRET_ACCESS_KEY') ?: $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null;
$region = getenv('AWS_DEFAULT_REGION') ?: $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1';
$bucket = getenv('AWS_BUCKET') ?: $_ENV['AWS_BUCKET'] ?? null;

if ($accessKey && $secretKey && $bucket) {
    echo "   Credenciales S3: DISPONIBLES ✓\n";
    
    // Test simple con cURL
    $url = "https://$bucket.s3.$region.amazonaws.com/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 || $httpCode == 403) {
        echo "   Conexión al bucket: EXITOSA ✓ (HTTP $httpCode)\n";
    } else {
        echo "   Conexión al bucket: FALLIDA ✗ (HTTP $httpCode)\n";
    }
} else {
    echo "   Credenciales S3: INCOMPLETAS ✗\n";
}

// 6. Verificar composer y dependencias
echo "\n6. DEPENDENCIAS:\n";
$composerPath = __DIR__ . '/composer.json';
if (file_exists($composerPath)) {
    $composer = json_decode(file_get_contents($composerPath), true);
    if (isset($composer['require']['league/flysystem-aws-s3-v3'])) {
        echo "   league/flysystem-aws-s3-v3: REQUERIDA ✓\n";
    } else {
        echo "   league/flysystem-aws-s3-v3: NO REQUERIDA ✗\n";
    }
} else {
    echo "   composer.json: NO ENCONTRADO ✗\n";
}

$vendorPath = __DIR__ . '/vendor/league/flysystem-aws-s3-v3';
if (is_dir($vendorPath)) {
    echo "   AWS S3 Flysystem: INSTALADO ✓\n";
} else {
    echo "   AWS S3 Flysystem: NO INSTALADO ✗\n";
}

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
echo "\nPARA EJECUTAR EN PRODUCCIÓN:\n";
echo "1. Sube este archivo a tu servidor de producción\n";
echo "2. Ejecuta: php debug-storage-production.php\n";
echo "3. Comparte el resultado completo\n";

echo "\nSI EL PROBLEMA PERSISTE:\n";
echo "- Verifica que las variables de entorno estén configuradas en Render\n";
echo "- Asegúrate de que composer install se ejecutó correctamente\n";
echo "- Revisa los logs de Render para errores durante el despliegue\n";
?>
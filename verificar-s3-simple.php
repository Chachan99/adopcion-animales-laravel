<?php
/**
 * Script Simplificado para Verificar Conexión a S3
 * No requiere bootstrap completo de Laravel
 */

echo "=== VERIFICACIÓN SIMPLE DE S3 ===\n\n";

// 1. Verificar archivo .env
echo "1. VERIFICANDO ARCHIVO .env:\n";

$envFile = __DIR__ . '/.env';
$envContent = '';

if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    echo "   - Archivo .env encontrado ✓\n";
    
    // Buscar configuraciones S3
    $s3Vars = [
        'FILESYSTEM_DISK' => 'Disco del sistema de archivos',
        'AWS_ACCESS_KEY_ID' => 'ID de clave de acceso AWS',
        'AWS_SECRET_ACCESS_KEY' => 'Clave secreta AWS',
        'AWS_DEFAULT_REGION' => 'Región AWS',
        'AWS_BUCKET' => 'Bucket S3',
        'AWS_URL' => 'URL base S3'
    ];
    
    foreach ($s3Vars as $var => $desc) {
        if (preg_match("/^$var=(.+)$/m", $envContent, $matches)) {
            $value = trim($matches[1]);
            if (!empty($value) && $value !== 'null') {
                echo "   - $var: Configurado ✓\n";
                if ($var === 'FILESYSTEM_DISK') {
                    echo "     Valor: $value\n";
                }
            } else {
                echo "   - $var: NO configurado ✗\n";
            }
        } else {
            echo "   - $var: NO encontrado ✗\n";
        }
    }
} else {
    echo "   - Archivo .env NO encontrado ✗\n";
}

echo "\n";

// 2. Verificar archivo .env.render (si existe)
echo "2. VERIFICANDO ARCHIVO .env.render:\n";

$envRenderFile = __DIR__ . '/.env.render';
if (file_exists($envRenderFile)) {
    $envRenderContent = file_get_contents($envRenderFile);
    echo "   - Archivo .env.render encontrado ✓\n";
    
    foreach ($s3Vars as $var => $desc) {
        if (preg_match("/^$var=(.+)$/m", $envRenderContent, $matches)) {
            $value = trim($matches[1]);
            if (!empty($value) && $value !== 'null') {
                echo "   - $var: Configurado ✓\n";
            } else {
                echo "   - $var: NO configurado ✗\n";
            }
        } else {
            echo "   - $var: NO encontrado ✗\n";
        }
    }
} else {
    echo "   - Archivo .env.render NO encontrado (normal si no usas este archivo)\n";
}

echo "\n";

// 3. Verificar configuración de filesystems.php
echo "3. VERIFICANDO CONFIGURACIÓN DE FILESYSTEMS:\n";

$filesystemsConfig = __DIR__ . '/config/filesystems.php';
if (file_exists($filesystemsConfig)) {
    $configContent = file_get_contents($filesystemsConfig);
    echo "   - Archivo config/filesystems.php encontrado ✓\n";
    
    // Verificar configuración S3
    if (strpos($configContent, "'s3' => [") !== false) {
        echo "   - Configuración S3 encontrada ✓\n";
    } else {
        echo "   - Configuración S3 NO encontrada ✗\n";
    }
    
    // Verificar disco público
    if (strpos($configContent, "'public' => [") !== false) {
        echo "   - Configuración disco público encontrada ✓\n";
        
        // Verificar si el disco público usa S3
        if (preg_match("/'public'\s*=>\s*\[\s*'driver'\s*=>\s*'s3'/", $configContent)) {
            echo "   - Disco público configurado para S3 ✓\n";
        } else {
            echo "   - Disco público NO usa S3 (usa local) ⚠️\n";
        }
    }
} else {
    echo "   - Archivo config/filesystems.php NO encontrado ✗\n";
}

echo "\n";

// 4. Verificar directorio storage
echo "4. VERIFICANDO DIRECTORIO STORAGE:\n";

$storageDir = __DIR__ . '/storage/app/public';
if (is_dir($storageDir)) {
    echo "   - Directorio storage/app/public existe ✓\n";
    
    // Contar archivos locales
    $localFiles = 0;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $localFiles++;
        }
    }
    echo "   - Archivos en storage local: $localFiles\n";
    
    if ($localFiles > 0) {
        echo "   - ⚠️  Hay archivos en storage local (deberían estar en S3)\n";
    }
} else {
    echo "   - Directorio storage/app/public NO existe\n";
}

// Verificar enlace simbólico public/storage
$publicStorageLink = __DIR__ . '/public/storage';
if (is_link($publicStorageLink)) {
    echo "   - Enlace simbólico public/storage existe ✓\n";
} elseif (is_dir($publicStorageLink)) {
    echo "   - public/storage existe como directorio ✓\n";
} else {
    echo "   - Enlace simbólico public/storage NO existe ✗\n";
    echo "     (Ejecuta: php artisan storage:link)\n";
}

echo "\n";

// 5. Verificar dependencias AWS
echo "5. VERIFICANDO DEPENDENCIAS AWS:\n";

$composerFile = __DIR__ . '/composer.json';
if (file_exists($composerFile)) {
    $composerContent = file_get_contents($composerFile);
    $composer = json_decode($composerContent, true);
    
    if (isset($composer['require']['aws/aws-sdk-php'])) {
        echo "   - AWS SDK instalado ✓\n";
    } elseif (isset($composer['require']['league/flysystem-aws-s3-v3'])) {
        echo "   - Flysystem S3 instalado ✓\n";
    } else {
        echo "   - AWS SDK NO instalado ✗\n";
        echo "     (Ejecuta: composer require aws/aws-sdk-php)\n";
    }
} else {
    echo "   - composer.json NO encontrado ✗\n";
}

echo "\n";

// 6. Diagnóstico final
echo "6. DIAGNÓSTICO FINAL:\n";

$isConfiguredForS3 = false;

// Verificar variables principales
if (preg_match("/^FILESYSTEM_DISK=s3$/m", $envContent)) {
    echo "   ✅ FILESYSTEM_DISK configurado como 's3'\n";
    $isConfiguredForS3 = true;
} else {
    echo "   ❌ FILESYSTEM_DISK NO está configurado como 's3'\n";
}

if (preg_match("/^AWS_ACCESS_KEY_ID=.+$/m", $envContent) && 
    preg_match("/^AWS_SECRET_ACCESS_KEY=.+$/m", $envContent) && 
    preg_match("/^AWS_BUCKET=.+$/m", $envContent)) {
    echo "   ✅ Credenciales AWS básicas configuradas\n";
} else {
    echo "   ❌ Credenciales AWS incompletas\n";
    $isConfiguredForS3 = false;
}

echo "\n";

if ($isConfiguredForS3) {
    echo "🎉 RESULTADO: Tu aplicación ESTÁ configurada para S3\n";
    echo "   - Las nuevas imágenes se guardarán en AWS S3\n";
    echo "   - No se perderán en reinicios de Render\n";
    echo "\n";
    echo "📋 PRÓXIMOS PASOS:\n";
    echo "   1. Redespliega tu aplicación en Render\n";
    echo "   2. Sube una imagen de prueba\n";
    echo "   3. Verifica que la URL contenga tu bucket S3\n";
    echo "   4. Las imágenes anteriores seguirán en storage local\n";
} else {
    echo "⚠️  RESULTADO: Tu aplicación NO está configurada para S3\n";
    echo "   - Aún usa almacenamiento local\n";
    echo "   - Las imágenes se perderán en reinicios\n";
    echo "\n";
    echo "📋 ACCIONES NECESARIAS:\n";
    echo "   1. Configura FILESYSTEM_DISK=s3 en Render\n";
    echo "   2. Configura todas las variables AWS_*\n";
    echo "   3. Redespliega la aplicación\n";
}

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
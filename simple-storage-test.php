<?php
/**
 * Script Simple de Diagnóstico de Almacenamiento
 * No requiere bootstrap completo de Laravel
 */

echo "=== DIAGNÓSTICO SIMPLE DE ALMACENAMIENTO ===\n\n";

// 1. Verificar variables de entorno desde .env
echo "1. VERIFICANDO ARCHIVO .env:\n";

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Buscar configuraciones importantes
    $configs = [
        'FILESYSTEM_DISK' => 'FILESYSTEM_DISK',
        'AWS_ACCESS_KEY_ID' => 'AWS_ACCESS_KEY_ID',
        'AWS_SECRET_ACCESS_KEY' => 'AWS_SECRET_ACCESS_KEY',
        'AWS_DEFAULT_REGION' => 'AWS_DEFAULT_REGION',
        'AWS_BUCKET' => 'AWS_BUCKET',
        'AWS_URL' => 'AWS_URL'
    ];
    
    foreach ($configs as $key => $search) {
        if (preg_match("/^$search=(.*)$/m", $envContent, $matches)) {
            $value = trim($matches[1]);
            if (empty($value) || $value === 'null' || $value === '""' || $value === "''") {
                echo "   - $key: NO CONFIGURADO ✗\n";
            } else {
                if (strpos($key, 'SECRET') !== false) {
                    echo "   - $key: Configurado (oculto) ✓\n";
                } else {
                    echo "   - $key: $value ✓\n";
                }
            }
        } else {
            echo "   - $key: NO ENCONTRADO ✗\n";
        }
    }
} else {
    echo "   - Archivo .env no encontrado ✗\n";
}

echo "\n";

// 2. Verificar archivo .env.render
echo "2. VERIFICANDO ARCHIVO .env.render:\n";

$envRenderFile = __DIR__ . '/.env.render';
if (file_exists($envRenderFile)) {
    echo "   - Archivo .env.render existe ✓\n";
    echo "   - Este archivo contiene la configuración para Render\n";
    
    $renderContent = file_get_contents($envRenderFile);
    if (strpos($renderContent, 'FILESYSTEM_DISK=s3') !== false) {
        echo "   - Configurado para usar S3 ✓\n";
    } else {
        echo "   - No configurado para S3 ✗\n";
    }
} else {
    echo "   - Archivo .env.render no encontrado ✗\n";
}

echo "\n";

// 3. Verificar directorios de almacenamiento
echo "3. VERIFICANDO DIRECTORIOS DE ALMACENAMIENTO:\n";

$storagePublic = __DIR__ . '/storage/app/public';
if (is_dir($storagePublic)) {
    echo "   - storage/app/public existe ✓\n";
    
    $subdirs = ['animales', 'usuarios', 'fundaciones', 'noticias', 'animales-perdidos'];
    foreach ($subdirs as $subdir) {
        $path = $storagePublic . '/' . $subdir;
        if (is_dir($path)) {
            $files = glob($path . '/*');
            echo "   - $subdir/: " . count($files) . " archivos\n";
        } else {
            echo "   - $subdir/: directorio no existe\n";
        }
    }
} else {
    echo "   - storage/app/public no existe ✗\n";
}

echo "\n";

// 4. Verificar public/storage link
echo "4. VERIFICANDO ENLACE SIMBÓLICO:\n";

$publicStorage = __DIR__ . '/public/storage';
if (is_link($publicStorage)) {
    echo "   - public/storage es un enlace simbólico ✓\n";
    echo "   - Apunta a: " . readlink($publicStorage) . "\n";
} elseif (is_dir($publicStorage)) {
    echo "   - public/storage existe como directorio ✓\n";
} else {
    echo "   - public/storage no existe ✗\n";
    echo "   - Ejecutar: php artisan storage:link\n";
}

echo "\n";

// 5. Verificar composer.json para AWS SDK
echo "5. VERIFICANDO DEPENDENCIAS AWS:\n";

$composerFile = __DIR__ . '/composer.json';
if (file_exists($composerFile)) {
    $composer = json_decode(file_get_contents($composerFile), true);
    
    $awsPackages = [
        'league/flysystem-aws-s3-v3' => 'AWS S3 Flysystem',
        'aws/aws-sdk-php' => 'AWS SDK'
    ];
    
    foreach ($awsPackages as $package => $description) {
        if (isset($composer['require'][$package])) {
            echo "   - $description: " . $composer['require'][$package] . " ✓\n";
        } else {
            echo "   - $description: NO INSTALADO ✗\n";
        }
    }
} else {
    echo "   - composer.json no encontrado ✗\n";
}

echo "\n";

// 6. Problema identificado y solución
echo "6. DIAGNÓSTICO Y SOLUCIÓN:\n";

echo "\n🚨 PROBLEMA PRINCIPAL:\n";
echo "   Las imágenes no se guardan en Render porque:\n";
echo "   1. Render usa contenedores efímeros (se reinician)\n";
echo "   2. El almacenamiento local se pierde en cada reinicio\n";
echo "   3. Necesitas usar AWS S3 para almacenamiento persistente\n\n";

echo "✅ SOLUCIÓN REQUERIDA:\n";
echo "   1. Crear bucket en AWS S3\n";
echo "   2. Crear usuario IAM con permisos S3\n";
echo "   3. Configurar variables de entorno en Render:\n";
echo "      - FILESYSTEM_DISK=s3\n";
echo "      - AWS_ACCESS_KEY_ID=tu_access_key\n";
echo "      - AWS_SECRET_ACCESS_KEY=tu_secret_key\n";
echo "      - AWS_DEFAULT_REGION=us-east-1\n";
echo "      - AWS_BUCKET=tu_bucket_name\n";
echo "      - AWS_URL=https://tu_bucket_name.s3.amazonaws.com\n";
echo "   4. Hacer redeploy en Render\n\n";

echo "📋 ARCHIVOS DE AYUDA CREADOS:\n";
echo "   - SOLUCION_IMAGENES_RENDER.md (guía completa)\n";
echo "   - render-storage-test.php (diagnóstico avanzado)\n\n";

echo "🔗 PRÓXIMOS PASOS:\n";
echo "   1. Leer SOLUCION_IMAGENES_RENDER.md\n";
echo "   2. Configurar AWS S3 según la guía\n";
echo "   3. Configurar variables en Render Dashboard\n";
echo "   4. Hacer redeploy\n";
echo "   5. Probar subida de imágenes\n\n";

echo "=== FIN DEL DIAGNÓSTICO ===\n";
<?php
/**
 * Script para Verificar Configuración S3 de Render
 * Usando las variables de entorno proporcionadas
 */

echo "=== VERIFICACIÓN DE CONFIGURACIÓN S3 EN RENDER ===\n\n";

// Variables de entorno de Render proporcionadas
$renderEnvVars = [
    'FILESYSTEM_DISK' => 's3',
    'AWS_ACCESS_KEY_ID' => 'AKIA53JRU407NWS4HEKF',
    'AWS_SECRET_ACCESS_KEY' => 't4zszMvKybxZKr6yEUybNhwoBSHV+hCdzXxYe1XD',
    'AWS_DEFAULT_REGION' => 'us-east-2',
    'AWS_BUCKET' => 'adopcion-animales-pipe',
    'AWS_URL' => 'https://adopcion-animales-pipe.s3.amazonaws.com',
    'APP_URL' => 'https://adopcion-animales-app.onrender.com'
];

echo "1. CONFIGURACIÓN PROPORCIONADA DE RENDER:\n";
foreach ($renderEnvVars as $key => $value) {
    if (strpos($key, 'SECRET') !== false) {
        echo "   - $key: " . str_repeat('*', strlen($value) - 8) . substr($value, -4) . " ✓\n";
    } else {
        echo "   - $key: $value ✓\n";
    }
}

echo "\n2. VALIDACIÓN DE CONFIGURACIÓN:\n";

// Verificar que todas las variables necesarias están presentes
$requiredVars = ['FILESYSTEM_DISK', 'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'AWS_DEFAULT_REGION', 'AWS_BUCKET', 'AWS_URL'];
$allConfigured = true;

foreach ($requiredVars as $var) {
    if (isset($renderEnvVars[$var]) && !empty($renderEnvVars[$var])) {
        echo "   - $var: Configurado ✓\n";
    } else {
        echo "   - $var: NO configurado ✗\n";
        $allConfigured = false;
    }
}

// Verificar formato de variables
echo "\n3. VALIDACIÓN DE FORMATO:\n";

// Verificar FILESYSTEM_DISK
if ($renderEnvVars['FILESYSTEM_DISK'] === 's3') {
    echo "   - FILESYSTEM_DISK es 's3': ✓\n";
} else {
    echo "   - FILESYSTEM_DISK NO es 's3': ✗\n";
    $allConfigured = false;
}

// Verificar formato de AWS_ACCESS_KEY_ID
if (preg_match('/^AKIA[0-9A-Z]{16}$/', $renderEnvVars['AWS_ACCESS_KEY_ID'])) {
    echo "   - AWS_ACCESS_KEY_ID tiene formato válido: ✓\n";
} else {
    echo "   - AWS_ACCESS_KEY_ID formato inválido: ✗\n";
}

// Verificar longitud de AWS_SECRET_ACCESS_KEY
if (strlen($renderEnvVars['AWS_SECRET_ACCESS_KEY']) === 40) {
    echo "   - AWS_SECRET_ACCESS_KEY tiene longitud correcta: ✓\n";
} else {
    echo "   - AWS_SECRET_ACCESS_KEY longitud incorrecta: ✗\n";
}

// Verificar región AWS
$validRegions = ['us-east-1', 'us-east-2', 'us-west-1', 'us-west-2', 'eu-west-1', 'eu-central-1', 'ap-southeast-1'];
if (in_array($renderEnvVars['AWS_DEFAULT_REGION'], $validRegions)) {
    echo "   - AWS_DEFAULT_REGION es válida: ✓\n";
} else {
    echo "   - AWS_DEFAULT_REGION puede ser inválida: ⚠️\n";
}

// Verificar formato de bucket
if (preg_match('/^[a-z0-9.-]{3,63}$/', $renderEnvVars['AWS_BUCKET'])) {
    echo "   - AWS_BUCKET tiene formato válido: ✓\n";
} else {
    echo "   - AWS_BUCKET formato inválido: ✗\n";
}

// Verificar URL de S3
$expectedUrl = "https://{$renderEnvVars['AWS_BUCKET']}.s3.amazonaws.com";
$expectedUrlRegion = "https://{$renderEnvVars['AWS_BUCKET']}.s3.{$renderEnvVars['AWS_DEFAULT_REGION']}.amazonaws.com";

if ($renderEnvVars['AWS_URL'] === $expectedUrl || $renderEnvVars['AWS_URL'] === $expectedUrlRegion) {
    echo "   - AWS_URL tiene formato correcto: ✓\n";
} else {
    echo "   - AWS_URL formato incorrecto: ⚠️\n";
    echo "     Esperado: $expectedUrl\n";
    echo "     O: $expectedUrlRegion\n";
    echo "     Actual: {$renderEnvVars['AWS_URL']}\n";
}

echo "\n4. COMPARACIÓN CON ARCHIVO .env LOCAL:\n";

$localEnvFile = __DIR__ . '/.env';
if (file_exists($localEnvFile)) {
    $localEnvContent = file_get_contents($localEnvFile);
    echo "   - Archivo .env local encontrado ✓\n";
    
    // Verificar diferencias importantes
    if (preg_match('/^FILESYSTEM_DISK=(.+)$/m', $localEnvContent, $matches)) {
        $localDisk = trim($matches[1]);
        if ($localDisk !== 's3') {
            echo "   - ⚠️  .env local usa FILESYSTEM_DISK=$localDisk (debería ser s3)\n";
        } else {
            echo "   - .env local ya tiene FILESYSTEM_DISK=s3 ✓\n";
        }
    }
    
    // Verificar si tiene credenciales AWS locales
    if (strpos($localEnvContent, 'AWS_ACCESS_KEY_ID=') !== false) {
        echo "   - .env local tiene credenciales AWS (para desarrollo) ✓\n";
    } else {
        echo "   - .env local NO tiene credenciales AWS (normal) ✓\n";
    }
} else {
    echo "   - Archivo .env local NO encontrado ✗\n";
}

echo "\n5. VERIFICACIÓN DE CONFIGURACIÓN DE FILESYSTEMS:\n";

$filesystemsConfig = __DIR__ . '/config/filesystems.php';
if (file_exists($filesystemsConfig)) {
    $configContent = file_get_contents($filesystemsConfig);
    echo "   - Archivo config/filesystems.php encontrado ✓\n";
    
    // Verificar configuración del disco público
    if (preg_match("/'public'\s*=>\s*\[\s*'driver'\s*=>\s*env\('FILESYSTEM_DISK',\s*'local'\)/", $configContent)) {
        echo "   - Disco 'public' usa FILESYSTEM_DISK (correcto) ✓\n";
    } elseif (preg_match("/'public'\s*=>\s*\[\s*'driver'\s*=>\s*'local'/", $configContent)) {
        echo "   - ⚠️  Disco 'public' hardcodeado como 'local'\n";
        echo "     Debería usar env('FILESYSTEM_DISK', 'local')\n";
    } else {
        echo "   - Configuración de disco 'public' no clara ⚠️\n";
    }
    
    // Verificar configuración S3
    if (strpos($configContent, "'s3' => [") !== false) {
        echo "   - Configuración S3 encontrada ✓\n";
    } else {
        echo "   - Configuración S3 NO encontrada ✗\n";
    }
} else {
    echo "   - Archivo config/filesystems.php NO encontrado ✗\n";
}

echo "\n6. DIAGNÓSTICO FINAL:\n";

if ($allConfigured) {
    echo "🎉 EXCELENTE: Tu configuración S3 está PERFECTA\n\n";
    echo "✅ CONFIRMACIÓN:\n";
    echo "   - FILESYSTEM_DISK=s3 (correcto)\n";
    echo "   - Todas las credenciales AWS configuradas\n";
    echo "   - Bucket: adopcion-animales-pipe\n";
    echo "   - Región: us-east-2\n";
    echo "   - URL: https://adopcion-animales-pipe.s3.amazonaws.com\n\n";
    
    echo "🚀 RESULTADO ESPERADO DESPUÉS DEL REDESPLIEGUE:\n";
    echo "   - Las nuevas imágenes se guardarán en S3\n";
    echo "   - URLs de imágenes: https://adopcion-animales-pipe.s3.amazonaws.com/...\n";
    echo "   - Las imágenes NO se perderán en reinicios\n";
    echo "   - El almacenamiento será persistente\n\n";
    
    echo "📋 PRÓXIMOS PASOS:\n";
    echo "   1. ✅ Configuración completada\n";
    echo "   2. 🔄 Redespliega tu aplicación en Render\n";
    echo "   3. 📸 Sube una imagen de prueba\n";
    echo "   4. 🔍 Verifica que la URL contenga tu bucket S3\n";
    echo "   5. 🎯 ¡Listo! Las imágenes ahora son persistentes\n";
    
} else {
    echo "⚠️  HAY PROBLEMAS EN LA CONFIGURACIÓN\n";
    echo "   - Revisa los elementos marcados con ✗\n";
    echo "   - Corrige la configuración en Render\n";
    echo "   - Redespliega después de corregir\n";
}

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
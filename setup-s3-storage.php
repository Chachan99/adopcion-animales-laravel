<?php
/**
 * Script para verificar configuración de AWS S3 en Render
 * Ejecutar con: php setup-s3-storage.php
 */

require_once 'vendor/autoload.php';

echo "=== VERIFICACIÓN DE CONFIGURACIÓN AWS S3 ===\n\n";

// Verificar variables de entorno
$requiredEnvVars = [
    'FILESYSTEM_DISK',
    'AWS_ACCESS_KEY_ID', 
    'AWS_SECRET_ACCESS_KEY',
    'AWS_DEFAULT_REGION',
    'AWS_BUCKET'
];

echo "1. Verificando variables de entorno:\n";
$missingVars = [];
foreach ($requiredEnvVars as $var) {
    $value = getenv($var);
    if (empty($value)) {
        $missingVars[] = $var;
        echo "   ❌ $var: NO CONFIGURADA\n";
    } else {
        $maskedValue = $var === 'AWS_SECRET_ACCESS_KEY' ? str_repeat('*', strlen($value)) : $value;
        echo "   ✅ $var: $maskedValue\n";
    }
}

if (!empty($missingVars)) {
    echo "\n❌ FALTAN VARIABLES DE ENTORNO:\n";
    foreach ($missingVars as $var) {
        echo "   - $var\n";
    }
    echo "\nConfigura estas variables en Render Dashboard > Environment Variables\n";
    exit(1);
}

echo "\n2. Verificando conectividad con AWS S3:\n";

try {
    // Crear cliente S3
    $s3Client = new \Aws\S3\S3Client([
        'version' => 'latest',
        'region' => getenv('AWS_DEFAULT_REGION'),
        'credentials' => [
            'key' => getenv('AWS_ACCESS_KEY_ID'),
            'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
        ]
    ]);

    // Verificar que el bucket existe
    $bucket = getenv('AWS_BUCKET');
    echo "   Verificando bucket: $bucket\n";
    
    $result = $s3Client->headBucket(['Bucket' => $bucket]);
    echo "   ✅ Bucket accesible\n";

    // Probar subida de archivo de prueba
    echo "\n3. Probando subida de archivo:\n";
    $testContent = "Test file created at " . date('Y-m-d H:i:s');
    $testKey = 'test/render-test-' . time() . '.txt';
    
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key' => $testKey,
        'Body' => $testContent,
        'ContentType' => 'text/plain'
    ]);
    
    echo "   ✅ Archivo subido exitosamente\n";
    echo "   URL: " . $result['ObjectURL'] . "\n";

    // Limpiar archivo de prueba
    $s3Client->deleteObject([
        'Bucket' => $bucket,
        'Key' => $testKey
    ]);
    echo "   ✅ Archivo de prueba eliminado\n";

    echo "\n🎉 CONFIGURACIÓN S3 EXITOSA!\n";
    echo "Las imágenes ahora se guardarán en AWS S3 de forma persistente.\n";

} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    echo "\nVerifica:\n";
    echo "1. Credenciales AWS correctas\n";
    echo "2. Bucket existe y es accesible\n";
    echo "3. Permisos IAM configurados\n";
    exit(1);
}

echo "\n=== CONFIGURACIÓN COMPLETADA ===\n";
echo "Ahora puedes subir imágenes y se guardarán permanentemente en S3.\n";
?>
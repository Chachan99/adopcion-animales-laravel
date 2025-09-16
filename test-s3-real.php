<?php
/**
 * Script para Probar Conexión Real a S3
 * Usa las credenciales reales para verificar si S3 funciona
 */

// Configurar las credenciales directamente
$awsConfig = [
    'access_key' => 'AKIA53JRU407NWS4HEKF',
    'secret_key' => 't4zszMvKybxZKr6yEUybNhwoBSHV+hCdzXxYe1XD',
    'region' => 'us-east-2',
    'bucket' => 'adopcion-animales-pipe',
    'base_url' => 'https://adopcion-animales-pipe.s3.amazonaws.com'
];

echo "=== PRUEBA REAL DE CONEXIÓN S3 ===\n\n";

// 1. Verificar si AWS SDK está disponible
echo "1. VERIFICANDO DEPENDENCIAS:\n";

if (class_exists('Aws\S3\S3Client')) {
    echo "   - AWS SDK disponible ✓\n";
    $useAwsSdk = true;
} else {
    echo "   - AWS SDK NO disponible, usando cURL ⚠️\n";
    $useAwsSdk = false;
}

echo "\n";

// 2. Probar conexión usando AWS SDK (si está disponible)
if ($useAwsSdk) {
    echo "2. PROBANDO CON AWS SDK:\n";
    
    try {
        $s3Client = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => $awsConfig['region'],
            'credentials' => [
                'key' => $awsConfig['access_key'],
                'secret' => $awsConfig['secret_key'],
            ]
        ]);
        
        echo "   - Cliente S3 inicializado ✓\n";
        
        // Probar listado de objetos
        try {
            $result = $s3Client->listObjectsV2([
                'Bucket' => $awsConfig['bucket'],
                'MaxKeys' => 10
            ]);
            
            echo "   - Conexión al bucket exitosa ✓\n";
            
            $objects = $result['Contents'] ?? [];
            echo "   - Objetos en bucket: " . count($objects) . "\n";
            
            if (count($objects) > 0) {
                echo "   - Ejemplos de archivos:\n";
                foreach (array_slice($objects, 0, 5) as $object) {
                    echo "     * " . $object['Key'] . " (" . round($object['Size']/1024, 2) . " KB)\n";
                }
            }
            
            // Probar subida de archivo
            echo "\n   - PROBANDO SUBIDA DE ARCHIVO:\n";
            $testContent = "Prueba de subida S3 - " . date('Y-m-d H:i:s');
            $testKey = 'test/conexion-' . time() . '.txt';
            
            try {
                $result = $s3Client->putObject([
                    'Bucket' => $awsConfig['bucket'],
                    'Key' => $testKey,
                    'Body' => $testContent,
                    'ContentType' => 'text/plain'
                ]);
                
                echo "     ✅ Subida exitosa: $testKey\n";
                echo "     URL: " . $awsConfig['base_url'] . "/$testKey\n";
                
                // Verificar que el archivo existe
                $headResult = $s3Client->headObject([
                    'Bucket' => $awsConfig['bucket'],
                    'Key' => $testKey
                ]);
                
                echo "     ✅ Archivo verificado en S3\n";
                
                // Limpiar archivo de prueba
                $s3Client->deleteObject([
                    'Bucket' => $awsConfig['bucket'],
                    'Key' => $testKey
                ]);
                
                echo "     ✅ Archivo de prueba eliminado\n";
                
            } catch (Exception $e) {
                echo "     ❌ Error en subida: " . $e->getMessage() . "\n";
            }
            
        } catch (Exception $e) {
            echo "   - ❌ Error listando objetos: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "   - ❌ Error inicializando S3: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "2. PROBANDO CON cURL:\n";
    
    // Función para crear firma AWS v4
    function createAwsSignature($method, $uri, $query, $headers, $payload, $awsConfig) {
        $datetime = gmdate('Ymd\THis\Z');
        $date = gmdate('Ymd');
        
        $canonicalRequest = $method . "\n" . $uri . "\n" . $query . "\n";
        foreach ($headers as $key => $value) {
            $canonicalRequest .= strtolower($key) . ':' . $value . "\n";
        }
        $canonicalRequest .= "\n" . implode(';', array_keys($headers)) . "\n" . hash('sha256', $payload);
        
        $stringToSign = "AWS4-HMAC-SHA256\n" . $datetime . "\n" . $date . "/" . $awsConfig['region'] . "/s3/aws4_request\n" . hash('sha256', $canonicalRequest);
        
        $kDate = hash_hmac('sha256', $date, 'AWS4' . $awsConfig['secret_key'], true);
        $kRegion = hash_hmac('sha256', $awsConfig['region'], $kDate, true);
        $kService = hash_hmac('sha256', 's3', $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
        
        return hash_hmac('sha256', $stringToSign, $kSigning);
    }
    
    // Probar listado con cURL
    try {
        $url = "https://{$awsConfig['bucket']}.s3.{$awsConfig['region']}.amazonaws.com/";
        $datetime = gmdate('Ymd\THis\Z');
        
        $headers = [
            'host' => "{$awsConfig['bucket']}.s3.{$awsConfig['region']}.amazonaws.com",
            'x-amz-date' => $datetime
        ];
        
        $signature = createAwsSignature('GET', '/', '', $headers, '', $awsConfig);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Host: ' . $headers['host'],
            'X-Amz-Date: ' . $headers['x-amz-date'],
            'Authorization: AWS4-HMAC-SHA256 Credential=' . $awsConfig['access_key'] . '/' . gmdate('Ymd') . '/' . $awsConfig['region'] . '/s3/aws4_request, SignedHeaders=host;x-amz-date, Signature=' . $signature
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            echo "   - ✅ Conexión exitosa con cURL\n";
            echo "   - Respuesta recibida del bucket\n";
        } else {
            echo "   - ❌ Error de conexión: HTTP $httpCode\n";
            echo "   - Respuesta: " . substr($response, 0, 200) . "...\n";
        }
        
    } catch (Exception $e) {
        echo "   - ❌ Error con cURL: " . $e->getMessage() . "\n";
    }
}

echo "\n3. VERIFICANDO ARCHIVOS EXISTENTES EN S3:\n";

// Buscar archivos comunes que deberían estar en S3
$commonPaths = [
    'animales/',
    'usuarios/',
    'fundaciones/',
    'noticias/',
    'animales-perdidos/'
];

foreach ($commonPaths as $path) {
    $testUrl = $awsConfig['base_url'] . '/' . $path;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testUrl);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   - $path: Accesible ✓\n";
    } elseif ($httpCode == 404) {
        echo "   - $path: No existe (normal si no hay archivos) ⚠️\n";
    } else {
        echo "   - $path: Error HTTP $httpCode ❌\n";
    }
}

echo "\n4. DIAGNÓSTICO FINAL:\n";

echo "🔍 RESUMEN:\n";
echo "   - Bucket: {$awsConfig['bucket']}\n";
echo "   - Región: {$awsConfig['region']}\n";
echo "   - URL base: {$awsConfig['base_url']}\n";

echo "\n📋 PARA VERIFICAR SI SE ESTÁN GUARDANDO ARCHIVOS:\n";
echo "   1. Ve a tu aplicación en: https://adopcion-animales-app.onrender.com\n";
echo "   2. Sube una nueva imagen (animal, usuario, etc.)\n";
echo "   3. Inspecciona la URL de la imagen en el navegador\n";
echo "   4. Si contiene 'adopcion-animales-pipe.s3.amazonaws.com', ¡funciona!\n";
echo "   5. Si contiene '/storage/', aún usa almacenamiento local\n";

echo "\n🚨 SI NO FUNCIONA:\n";
echo "   - Verifica que redesplegaste la aplicación después de configurar las variables\n";
echo "   - Revisa los logs de Render para errores\n";
echo "   - Asegúrate de que FILESYSTEM_DISK=s3 esté en las variables de entorno\n";

echo "\n=== FIN DE LA PRUEBA ===\n";
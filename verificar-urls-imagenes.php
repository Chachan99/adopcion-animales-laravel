<?php
/**
 * Script para Verificar URLs de Imágenes en la Aplicación
 * Revisa la base de datos para ver qué URLs están usando las imágenes
 */

require_once 'bootstrap/app.php';

echo "=== VERIFICACIÓN DE URLs DE IMÁGENES ===\n\n";

try {
    // Conectar a la base de datos
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "1. CONEXIÓN A BASE DE DATOS:\n";
    echo "   - Conectado exitosamente ✓\n\n";
    
    // Verificar tablas que contienen imágenes
    $tables = [
        'animales' => ['foto', 'foto_adicional'],
        'usuarios' => ['foto'],
        'fundaciones' => ['logo'],
        'noticias' => ['imagen'],
        'animales_perdidos' => ['foto']
    ];
    
    echo "2. ANALIZANDO URLS DE IMÁGENES:\n";
    
    $totalImages = 0;
    $s3Images = 0;
    $localImages = 0;
    $nullImages = 0;
    
    foreach ($tables as $table => $columns) {
        echo "\n   📋 TABLA: $table\n";
        
        try {
            foreach ($columns as $column) {
                $query = "SELECT id, $column FROM $table WHERE $column IS NOT NULL AND $column != ''";
                $results = DB::select($query);
                
                echo "      - Columna '$column': " . count($results) . " registros\n";
                
                foreach ($results as $row) {
                    $totalImages++;
                    $imageUrl = $row->$column;
                    
                    if (empty($imageUrl)) {
                        $nullImages++;
                        continue;
                    }
                    
                    // Analizar tipo de URL
                    if (strpos($imageUrl, 'adopcion-animales-pipe.s3.amazonaws.com') !== false) {
                        $s3Images++;
                        echo "        ✅ S3: ID {$row->id} - " . substr($imageUrl, 0, 60) . "...\n";
                    } elseif (strpos($imageUrl, '/storage/') !== false || strpos($imageUrl, 'localhost') !== false) {
                        $localImages++;
                        echo "        📁 Local: ID {$row->id} - " . substr($imageUrl, 0, 60) . "...\n";
                    } else {
                        echo "        ❓ Otro: ID {$row->id} - " . substr($imageUrl, 0, 60) . "...\n";
                    }
                }
            }
        } catch (Exception $e) {
            echo "      ❌ Error consultando $table: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n3. RESUMEN DE ANÁLISIS:\n";
    echo "   📊 ESTADÍSTICAS:\n";
    echo "      - Total de imágenes: $totalImages\n";
    echo "      - Imágenes en S3: $s3Images\n";
    echo "      - Imágenes locales: $localImages\n";
    echo "      - Imágenes vacías/nulas: $nullImages\n";
    
    if ($s3Images > 0) {
        echo "\n   ✅ BUENAS NOTICIAS: Ya tienes imágenes en S3!\n";
    } else {
        echo "\n   ⚠️  TODAS LAS IMÁGENES SON LOCALES\n";
    }
    
    echo "\n4. VERIFICANDO ACCESIBILIDAD DE URLS:\n";
    
    // Probar algunas URLs para ver si son accesibles
    $sampleQuery = "SELECT foto FROM animales WHERE foto IS NOT NULL AND foto != '' LIMIT 5";
    $sampleImages = DB::select($sampleQuery);
    
    foreach ($sampleImages as $image) {
        $url = $image->foto;
        echo "   🔗 Probando: " . substr($url, 0, 50) . "...\n";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            echo "      ✅ Accesible (HTTP $httpCode)\n";
        } else {
            echo "      ❌ No accesible (HTTP $httpCode)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error conectando a la base de datos: " . $e->getMessage() . "\n";
    echo "Intentando método alternativo...\n\n";
    
    // Método alternativo: revisar archivos de configuración
    echo "MÉTODO ALTERNATIVO - REVISANDO ARCHIVOS:\n";
    
    // Buscar en archivos de migración
    $migrationFiles = glob('database/migrations/*.php');
    echo "   - Archivos de migración encontrados: " . count($migrationFiles) . "\n";
    
    foreach ($migrationFiles as $file) {
        $content = file_get_contents($file);
        if (strpos($content, 'foto') !== false || strpos($content, 'imagen') !== false || strpos($content, 'logo') !== false) {
            echo "     * " . basename($file) . " contiene campos de imagen\n";
        }
    }
    
    // Buscar en modelos
    $modelFiles = glob('app/Models/*.php');
    echo "\n   - Archivos de modelo encontrados: " . count($modelFiles) . "\n";
    
    foreach ($modelFiles as $file) {
        $content = file_get_contents($file);
        if (strpos($content, 'foto') !== false || strpos($content, 'imagen') !== false || strpos($content, 'logo') !== false) {
            echo "     * " . basename($file) . " maneja imágenes\n";
        }
    }
}

echo "\n5. DIAGNÓSTICO Y RECOMENDACIONES:\n";

echo "🔍 PARA VERIFICAR SI S3 FUNCIONA:\n";
echo "   1. Ve a: https://adopcion-animales-app.onrender.com\n";
echo "   2. Registra un nuevo animal con foto\n";
echo "   3. Inspecciona la URL de la imagen (clic derecho > Inspeccionar)\n";
echo "   4. Si la URL contiene 'adopcion-animales-pipe.s3.amazonaws.com', ¡S3 funciona!\n";

echo "\n📝 PASOS SIGUIENTES:\n";
if ($s3Images == 0) {
    echo "   - Las imágenes actuales son locales\n";
    echo "   - Necesitas redesplegar la aplicación con FILESYSTEM_DISK=s3\n";
    echo "   - Después, las nuevas imágenes se guardarán en S3\n";
    echo "   - Las imágenes existentes seguirán siendo locales hasta migrarlas\n";
} else {
    echo "   - ¡Ya tienes imágenes en S3!\n";
    echo "   - Puedes migrar las imágenes locales restantes\n";
}

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
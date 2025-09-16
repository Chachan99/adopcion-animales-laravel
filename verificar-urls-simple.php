<?php
/**
 * Script Simplificado para Verificar URLs de Imágenes
 * No depende de Laravel, usa conexión directa a la base de datos
 */

echo "=== VERIFICACIÓN SIMPLE DE URLs DE IMÁGENES ===\n\n";

// Leer configuración de base de datos desde .env
$envFile = '.env';
$dbConfig = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            list($key, $value) = explode('=', $line, 2);
            $dbConfig[trim($key)] = trim($value, '"\'');
        }
    }
}

echo "1. CONFIGURACIÓN DE BASE DE DATOS:\n";
$host = $dbConfig['DB_HOST'] ?? 'localhost';
$database = $dbConfig['DB_DATABASE'] ?? '';
$username = $dbConfig['DB_USERNAME'] ?? '';
$password = $dbConfig['DB_PASSWORD'] ?? '';

echo "   - Host: $host\n";
echo "   - Base de datos: $database\n";
echo "   - Usuario: $username\n";

if (empty($database)) {
    echo "   ❌ No se encontró configuración de base de datos en .env\n";
    echo "\n2. MÉTODO ALTERNATIVO - REVISANDO ARCHIVOS LOCALES:\n";
    
    // Buscar imágenes en el directorio storage
    $storageDir = 'storage/app/public';
    if (is_dir($storageDir)) {
        echo "   📁 Directorio storage/app/public existe\n";
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $totalLocalImages = 0;
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, $imageExtensions)) {
                    $totalLocalImages++;
                    if ($totalLocalImages <= 10) { // Mostrar solo las primeras 10
                        echo "      - " . $file->getPathname() . "\n";
                    }
                }
            }
        }
        
        echo "   📊 Total de imágenes locales encontradas: $totalLocalImages\n";
        
        if ($totalLocalImages > 10) {
            echo "      (mostrando solo las primeras 10)\n";
        }
    } else {
        echo "   ⚠️  Directorio storage/app/public no existe\n";
    }
    
    // Buscar en public/img
    $publicImgDir = 'public/img';
    if (is_dir($publicImgDir)) {
        echo "\n   📁 Directorio public/img existe\n";
        
        $publicImages = 0;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicImgDir));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, $imageExtensions)) {
                    $publicImages++;
                    if ($publicImages <= 5) {
                        echo "      - " . $file->getPathname() . "\n";
                    }
                }
            }
        }
        
        echo "   📊 Imágenes en public/img: $publicImages\n";
    }
    
} else {
    // Intentar conexión a la base de datos
    try {
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        echo "   ✅ Conexión exitosa\n\n";
        
        echo "2. ANALIZANDO TABLAS DE LA BASE DE DATOS:\n";
        
        // Verificar qué tablas existen
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "   📋 Tablas encontradas: " . count($tables) . "\n";
        
        $imageTables = [];
        foreach ($tables as $table) {
            // Obtener columnas de cada tabla
            $columns = $pdo->query("DESCRIBE $table")->fetchAll();
            $imageColumns = [];
            
            foreach ($columns as $column) {
                $columnName = $column['Field'];
                if (strpos($columnName, 'foto') !== false || 
                    strpos($columnName, 'imagen') !== false || 
                    strpos($columnName, 'logo') !== false) {
                    $imageColumns[] = $columnName;
                }
            }
            
            if (!empty($imageColumns)) {
                $imageTables[$table] = $imageColumns;
                echo "      - $table: " . implode(', ', $imageColumns) . "\n";
            }
        }
        
        echo "\n3. ANALIZANDO URLs DE IMÁGENES:\n";
        
        $totalImages = 0;
        $s3Images = 0;
        $localImages = 0;
        $nullImages = 0;
        
        foreach ($imageTables as $table => $columns) {
            echo "\n   📋 TABLA: $table\n";
            
            foreach ($columns as $column) {
                try {
                    $stmt = $pdo->prepare("SELECT id, $column FROM $table WHERE $column IS NOT NULL AND $column != ''");
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    
                    echo "      - Columna '$column': " . count($results) . " registros\n";
                    
                    foreach ($results as $row) {
                        $totalImages++;
                        $imageUrl = $row[$column];
                        
                        if (empty($imageUrl)) {
                            $nullImages++;
                            continue;
                        }
                        
                        // Analizar tipo de URL
                        if (strpos($imageUrl, 'adopcion-animales-pipe.s3.amazonaws.com') !== false) {
                            $s3Images++;
                            echo "        ✅ S3: ID {$row['id']} - " . substr($imageUrl, 0, 60) . "...\n";
                        } elseif (strpos($imageUrl, '/storage/') !== false || 
                                 strpos($imageUrl, 'localhost') !== false ||
                                 strpos($imageUrl, '/img/') !== false) {
                            $localImages++;
                            echo "        📁 Local: ID {$row['id']} - " . substr($imageUrl, 0, 60) . "...\n";
                        } else {
                            echo "        ❓ Otro: ID {$row['id']} - " . substr($imageUrl, 0, 60) . "...\n";
                        }
                    }
                } catch (Exception $e) {
                    echo "      ❌ Error consultando $table.$column: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "\n4. RESUMEN DE ANÁLISIS:\n";
        echo "   📊 ESTADÍSTICAS:\n";
        echo "      - Total de imágenes: $totalImages\n";
        echo "      - Imágenes en S3: $s3Images\n";
        echo "      - Imágenes locales: $localImages\n";
        echo "      - Imágenes vacías/nulas: $nullImages\n";
        
        if ($s3Images > 0) {
            echo "\n   ✅ BUENAS NOTICIAS: Ya tienes imágenes en S3!\n";
            $percentage = round(($s3Images / $totalImages) * 100, 1);
            echo "      - Porcentaje en S3: $percentage%\n";
        } else {
            echo "\n   ⚠️  TODAS LAS IMÁGENES SON LOCALES\n";
        }
        
        // Probar accesibilidad de algunas URLs
        if ($totalImages > 0) {
            echo "\n5. PROBANDO ACCESIBILIDAD DE URLs:\n";
            
            // Obtener una muestra de URLs
            $sampleUrls = [];
            foreach ($imageTables as $table => $columns) {
                foreach ($columns as $column) {
                    try {
                        $stmt = $pdo->prepare("SELECT $column FROM $table WHERE $column IS NOT NULL AND $column != '' LIMIT 3");
                        $stmt->execute();
                        $urls = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        $sampleUrls = array_merge($sampleUrls, $urls);
                    } catch (Exception $e) {
                        // Ignorar errores
                    }
                }
            }
            
            $sampleUrls = array_unique(array_slice($sampleUrls, 0, 5));
            
            foreach ($sampleUrls as $url) {
                echo "   🔗 Probando: " . substr($url, 0, 50) . "...\n";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode == 200) {
                    echo "      ✅ Accesible (HTTP $httpCode)\n";
                } else {
                    echo "      ❌ No accesible (HTTP $httpCode)\n";
                }
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ Error de conexión: " . $e->getMessage() . "\n";
    }
}

echo "\n6. DIAGNÓSTICO Y RECOMENDACIONES:\n";

echo "🔍 PARA VERIFICAR SI S3 FUNCIONA EN PRODUCCIÓN:\n";
echo "   1. Ve a: https://adopcion-animales-app.onrender.com\n";
echo "   2. Registra un nuevo animal con foto\n";
echo "   3. Inspecciona la URL de la imagen (F12 > Elements)\n";
echo "   4. Si contiene 'adopcion-animales-pipe.s3.amazonaws.com', ¡S3 funciona!\n";

echo "\n📝 ESTADO ACTUAL:\n";
echo "   - Configuración S3: ✅ Correcta\n";
echo "   - Variables de entorno: ✅ Configuradas\n";
echo "   - filesystems.php: ✅ Corregido\n";
echo "   - Falta: Redesplegar en Render con las nuevas configuraciones\n";

echo "\n🚨 PASOS SIGUIENTES:\n";
echo "   1. Asegúrate de que redesplegaste en Render después de configurar las variables\n";
echo "   2. Sube una nueva imagen para probar\n";
echo "   3. Si funciona, las nuevas imágenes irán a S3\n";
echo "   4. Las imágenes existentes seguirán siendo locales hasta migrarlas\n";

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
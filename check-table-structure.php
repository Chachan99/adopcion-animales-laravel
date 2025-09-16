<?php
/**
 * Script para verificar la estructura de las tablas
 */

echo "🔍 VERIFICADOR DE ESTRUCTURA DE TABLAS\n";
echo "======================================\n\n";

$dbPath = 'database/database.sqlite';

if (!file_exists($dbPath)) {
    echo "❌ La base de datos SQLite no existe.\n";
    exit(1);
}

try {
    $pdo = new PDO("sqlite:$dbPath", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ Conexión exitosa\n\n";
    
    // Verificar estructura de tabla usuarios
    echo "👥 ESTRUCTURA DE TABLA USUARIOS\n";
    echo "   " . str_repeat("-", 32) . "\n";
    
    $stmt = $pdo->query("PRAGMA table_info(usuarios)");
    $columns = $stmt->fetchAll();
    
    if (empty($columns)) {
        echo "   ❌ Tabla usuarios no existe\n";
    } else {
        echo "   📋 Columnas encontradas:\n";
        foreach ($columns as $col) {
            echo "      - {$col['name']} ({$col['type']})\n";
        }
        
        // Mostrar algunos registros
        echo "\n   📊 Datos de ejemplo:\n";
        $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 3");
        $users = $stmt->fetchAll();
        
        foreach ($users as $user) {
            $userInfo = [];
            foreach ($user as $key => $value) {
                if (!is_numeric($key)) {
                    $userInfo[] = "$key: $value";
                }
            }
            echo "      - " . implode(", ", array_slice($userInfo, 0, 4)) . "\n";
        }
    }
    echo "\n";
    
    // Verificar estructura de tabla animales
    echo "🐕 ESTRUCTURA DE TABLA ANIMALES\n";
    echo "   " . str_repeat("-", 32) . "\n";
    
    $stmt = $pdo->query("PRAGMA table_info(animales)");
    $columns = $stmt->fetchAll();
    
    if (empty($columns)) {
        echo "   ❌ Tabla animales no existe\n";
    } else {
        echo "   📋 Columnas encontradas:\n";
        foreach ($columns as $col) {
            echo "      - {$col['name']} ({$col['type']})\n";
        }
        
        // Mostrar algunos registros
        echo "\n   📊 Datos de ejemplo:\n";
        $stmt = $pdo->query("SELECT * FROM animales LIMIT 3");
        $animals = $stmt->fetchAll();
        
        foreach ($animals as $animal) {
            $animalInfo = [];
            foreach ($animal as $key => $value) {
                if (!is_numeric($key) && in_array($key, ['id', 'nombre', 'especie', 'raza', 'estado'])) {
                    $animalInfo[] = "$key: $value";
                }
            }
            echo "      - " . implode(", ", $animalInfo) . "\n";
        }
    }
    echo "\n";
    
    // Verificar estructura de tabla fundaciones
    echo "🏢 ESTRUCTURA DE TABLA PERFIL_FUNDACIONES\n";
    echo "   " . str_repeat("-", 42) . "\n";
    
    $stmt = $pdo->query("PRAGMA table_info(perfil_fundaciones)");
    $columns = $stmt->fetchAll();
    
    if (empty($columns)) {
        echo "   ❌ Tabla perfil_fundaciones no existe\n";
    } else {
        echo "   📋 Columnas encontradas:\n";
        foreach ($columns as $col) {
            echo "      - {$col['name']} ({$col['type']})\n";
        }
        
        // Mostrar algunos registros
        echo "\n   📊 Datos de ejemplo:\n";
        $stmt = $pdo->query("SELECT * FROM perfil_fundaciones LIMIT 3");
        $fundaciones = $stmt->fetchAll();
        
        foreach ($fundaciones as $fundacion) {
            $fundacionInfo = [];
            foreach ($fundacion as $key => $value) {
                if (!is_numeric($key) && in_array($key, ['id', 'nombre_fundacion', 'ciudad', 'usuario_id'])) {
                    $fundacionInfo[] = "$key: $value";
                }
            }
            echo "      - " . implode(", ", $fundacionInfo) . "\n";
        }
    }
    echo "\n";
    
    // Verificar todas las tablas disponibles
    echo "📋 TODAS LAS TABLAS DISPONIBLES\n";
    echo "   " . str_repeat("-", 32) . "\n";
    
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $stmt->fetch()['count'];
        echo "   📄 $table: $count registros\n";
    }
    
    echo "\n======================================\n";
    echo "✅ VERIFICACIÓN COMPLETADA\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
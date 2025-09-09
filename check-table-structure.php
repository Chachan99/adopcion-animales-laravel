<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== VERIFICANDO ESTRUCTURA DE TABLAS ===\n\n";

try {
    // Verificar solicitudes_adopcion
    echo "📊 Tabla: solicitudes_adopcion\n";
    if (Schema::hasTable('solicitudes_adopcion')) {
        $columns = Schema::getColumnListing('solicitudes_adopcion');
        echo "   Columnas: " . implode(', ', $columns) . "\n";
    } else {
        echo "   ❌ Tabla no existe\n";
    }
    
    echo "\n📊 Tabla: donaciones\n";
    if (Schema::hasTable('donaciones')) {
        $columns = Schema::getColumnListing('donaciones');
        echo "   Columnas: " . implode(', ', $columns) . "\n";
    } else {
        echo "   ❌ Tabla no existe\n";
    }
    
    echo "\n📊 Tabla: imagenes_animales\n";
    if (Schema::hasTable('imagenes_animales')) {
        $columns = Schema::getColumnListing('imagenes_animales');
        echo "   Columnas: " . implode(', ', $columns) . "\n";
    } else {
        echo "   ❌ Tabla no existe\n";
    }
    
    echo "\n📊 Todas las tablas en la BD:\n";
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
    foreach ($tables as $table) {
        echo "   - {$table->name}\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
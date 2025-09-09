<?php
/**
 * Script de diagnóstico específico para problemas de eliminación en Render
 * Ejecutar con: php render-delete-diagnostics.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Animal;
use App\Models\SolicitudAdopcion;
use App\Models\Donacion;

echo "=== DIAGNÓSTICO DE ELIMINACIÓN EN RENDER ===\n\n";

// 1. Verificar conexión a base de datos
echo "1. 🔍 VERIFICANDO CONEXIÓN A BASE DE DATOS:\n";
try {
    $connection = DB::connection();
    $pdo = $connection->getPdo();
    echo "   ✅ Conexión exitosa\n";
    echo "   📊 Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "   📊 Versión: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
} catch (Exception $e) {
    echo "   ❌ ERROR de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Verificar configuración de foreign keys
echo "\n2. 🔍 VERIFICANDO CONFIGURACIÓN DE FOREIGN KEYS:\n";
try {
    $foreignKeysEnabled = config('database.connections.' . config('database.default') . '.foreign_key_constraints', true);
    echo "   📊 Foreign key constraints: " . ($foreignKeysEnabled ? 'HABILITADAS' : 'DESHABILITADAS') . "\n";
    
    // Para PostgreSQL, verificar configuración específica
    if (DB::getDriverName() === 'pgsql') {
        $result = DB::select("SELECT current_setting('check_function_bodies') as check_functions");
        echo "   📊 Check function bodies: " . $result[0]->check_functions . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ ERROR verificando foreign keys: " . $e->getMessage() . "\n";
}

// 3. Verificar estructura de tablas relacionadas
echo "\n3. 🔍 VERIFICANDO ESTRUCTURA DE TABLAS:\n";
try {
    // Verificar tabla animales
    $animalesCount = DB::table('animales')->count();
    echo "   📊 Animales en BD: {$animalesCount}\n";
    
    // Verificar tabla solicitudes_adopcion
    $solicitudesCount = DB::table('solicitudes_adopcion')->count();
    echo "   📊 Solicitudes de adopción: {$solicitudesCount}\n";
    
    // Verificar tabla donaciones
    $donacionesCount = DB::table('donaciones')->count();
    echo "   📊 Donaciones: {$donacionesCount}\n";
    
    // Verificar foreign keys existentes
    if (DB::getDriverName() === 'pgsql') {
        $foreignKeys = DB::select("
            SELECT 
                tc.table_name, 
                kcu.column_name, 
                ccu.table_name AS foreign_table_name,
                ccu.column_name AS foreign_column_name,
                rc.delete_rule
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                  AND tc.table_schema = kcu.table_schema
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
                  AND ccu.table_schema = tc.table_schema
                JOIN information_schema.referential_constraints AS rc
                  ON tc.constraint_name = rc.constraint_name
            WHERE tc.constraint_type = 'FOREIGN KEY'
              AND (tc.table_name = 'solicitudes_adopcion' OR tc.table_name = 'donaciones')
              AND ccu.table_name = 'animales'
        ");
        
        echo "   📋 Foreign keys relacionadas con animales:\n";
        foreach ($foreignKeys as $fk) {
            echo "      - {$fk->table_name}.{$fk->column_name} -> {$fk->foreign_table_name}.{$fk->foreign_column_name} (DELETE: {$fk->delete_rule})\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ ERROR verificando estructura: " . $e->getMessage() . "\n";
}

// 4. Verificar permisos de storage
echo "\n4. 🔍 VERIFICANDO PERMISOS DE STORAGE:\n";
try {
    $storagePath = storage_path('app/public');
    echo "   📂 Ruta de storage: {$storagePath}\n";
    echo "   📊 Storage existe: " . (is_dir($storagePath) ? 'SÍ' : 'NO') . "\n";
    echo "   📊 Storage escribible: " . (is_writable($storagePath) ? 'SÍ' : 'NO') . "\n";
    
    // Probar crear y eliminar archivo de prueba
    $testFile = 'test_delete_' . time() . '.txt';
    Storage::disk('public')->put($testFile, 'test content');
    echo "   ✅ Crear archivo de prueba: EXITOSO\n";
    
    Storage::disk('public')->delete($testFile);
    echo "   ✅ Eliminar archivo de prueba: EXITOSO\n";
    
} catch (Exception $e) {
    echo "   ❌ ERROR con storage: " . $e->getMessage() . "\n";
}

// 5. Probar eliminación simulada
echo "\n5. 🔍 PROBANDO ELIMINACIÓN SIMULADA:\n";
try {
    DB::beginTransaction();
    
    // Buscar un animal con relaciones para probar
    $animalConRelaciones = DB::table('animales')
        ->leftJoin('solicitudes_adopcion', 'animales.id', '=', 'solicitudes_adopcion.animal_id')
        ->leftJoin('donaciones', 'animales.id', '=', 'donaciones.animal_id')
        ->select('animales.id', 'animales.nombre')
        ->selectRaw('COUNT(DISTINCT solicitudes_adopcion.id) as solicitudes')
        ->selectRaw('COUNT(DISTINCT donaciones.id) as donaciones')
        ->groupBy('animales.id', 'animales.nombre')
        ->havingRaw('COUNT(DISTINCT solicitudes_adopcion.id) > 0 OR COUNT(DISTINCT donaciones.id) > 0')
        ->first();
    
    if ($animalConRelaciones) {
        echo "   📊 Animal de prueba: {$animalConRelaciones->nombre} (ID: {$animalConRelaciones->id})\n";
        echo "   📊 Solicitudes relacionadas: {$animalConRelaciones->solicitudes}\n";
        echo "   📊 Donaciones relacionadas: {$animalConRelaciones->donaciones}\n";
        
        // Simular eliminación de relaciones
        echo "   🔄 Simulando eliminación de solicitudes...\n";
        $solicitudesEliminadas = DB::table('solicitudes_adopcion')
            ->where('animal_id', $animalConRelaciones->id)
            ->count();
        echo "   📊 Solicitudes a eliminar: {$solicitudesEliminadas}\n";
        
        echo "   🔄 Simulando eliminación de donaciones...\n";
        $donacionesEliminadas = DB::table('donaciones')
            ->where('animal_id', $animalConRelaciones->id)
            ->count();
        echo "   📊 Donaciones a eliminar: {$donacionesEliminadas}\n";
        
        echo "   ✅ Simulación exitosa - NO se eliminaron datos reales\n";
    } else {
        echo "   📊 No se encontraron animales con relaciones para probar\n";
    }
    
    DB::rollBack();
    
} catch (Exception $e) {
    DB::rollBack();
    echo "   ❌ ERROR en simulación: " . $e->getMessage() . "\n";
    echo "   📋 Tipo de error: " . get_class($e) . "\n";
    if ($e instanceof \Illuminate\Database\QueryException) {
        echo "   📋 Código SQL: " . $e->getCode() . "\n";
        echo "   📋 SQL State: " . $e->errorInfo[0] ?? 'N/A' . "\n";
    }
}

// 6. Verificar logs de errores
echo "\n6. 🔍 VERIFICANDO LOGS DE ERRORES:\n";
try {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        echo "   📂 Log existe: SÍ\n";
        echo "   📊 Tamaño del log: " . number_format(filesize($logPath) / 1024, 2) . " KB\n";
        
        // Buscar errores recientes relacionados con eliminación
        $logContent = file_get_contents($logPath);
        $deleteErrors = preg_match_all('/Error al eliminar|foreign key constraint|Integrity constraint/', $logContent, $matches);
        echo "   📊 Errores de eliminación encontrados: {$deleteErrors}\n";
        
        if ($deleteErrors > 0) {
            echo "   📋 Últimos errores relacionados:\n";
            $lines = explode("\n", $logContent);
            $errorLines = array_filter($lines, function($line) {
                return strpos($line, 'Error al eliminar') !== false || 
                       strpos($line, 'foreign key constraint') !== false ||
                       strpos($line, 'Integrity constraint') !== false;
            });
            
            foreach (array_slice($errorLines, -3) as $errorLine) {
                echo "      " . substr($errorLine, 0, 100) . "...\n";
            }
        }
    } else {
        echo "   📂 Log no existe\n";
    }
} catch (Exception $e) {
    echo "   ❌ ERROR verificando logs: " . $e->getMessage() . "\n";
}

// 7. Recomendaciones
echo "\n7. 💡 RECOMENDACIONES:\n";
echo "   📋 Para solucionar problemas de eliminación en Render:\n";
echo "      1. Verificar que las foreign keys tengan CASCADE configurado\n";
echo "      2. Asegurar que las transacciones estén funcionando correctamente\n";
echo "      3. Verificar permisos de escritura en storage\n";
echo "      4. Revisar logs específicos de PostgreSQL en Render\n";
echo "      5. Considerar usar soft deletes si hay problemas persistentes\n";

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
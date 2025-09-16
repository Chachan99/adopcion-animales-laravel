<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Animal;
use App\Models\PerfilFundacion;

Route::get('/test-db', function() {
    try {
        // Check if the table exists
        if (!Schema::hasTable('animales')) {
            return response()->json(['error' => 'Table animales does not exist'], 404);
        }

        // Get the column listing
        $columns = Schema::getColumnListing('animales');
        
        // Get the first row to see the data (if any)
        $firstRow = DB::table('animales')->first();
        
        return response()->json([
            'columns' => $columns,
            'first_row' => $firstRow
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Endpoint de prueba para verificar el guardado de animales
Route::get('/test-animal-save', function () {
    try {
        Log::info('🧪 Iniciando prueba de guardado de animales');
        
        // 1. Verificar conexión a base de datos
        $dbConnection = config('database.default');
        $dbHost = config('database.connections.' . $dbConnection . '.host');
        
        $response = [
            'status' => 'success',
            'timestamp' => now()->toDateTimeString(),
            'database' => [
                'connection' => $dbConnection,
                'host' => $dbHost ?: 'N/A',
                'connected' => false
            ],
            'tables' => [],
            'test_data' => null,
            'errors' => []
        ];
        
        // Probar conexión
        try {
            DB::connection()->getPdo();
            $response['database']['connected'] = true;
            Log::info('✅ Conexión a base de datos exitosa');
        } catch (Exception $e) {
            $response['errors'][] = 'Error de conexión: ' . $e->getMessage();
            Log::error('❌ Error de conexión a base de datos: ' . $e->getMessage());
        }
        
        // 2. Verificar tablas existentes
        try {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
            $response['tables'] = array_map(function($table) {
                return $table->name;
            }, $tables);
            Log::info('📋 Tablas encontradas: ' . implode(', ', $response['tables']));
        } catch (Exception $e) {
            $response['errors'][] = 'Error al obtener tablas: ' . $e->getMessage();
        }
        
        // 3. Buscar fundación de prueba
        $fundacion = null;
        try {
            $fundacion = PerfilFundacion::first();
            if ($fundacion) {
                Log::info("🏢 Fundación encontrada: {$fundacion->nombre} (ID: {$fundacion->id})");
            } else {
                $response['errors'][] = 'No se encontró ninguna fundación';
                Log::warning('⚠️ No se encontró ninguna fundación');
            }
        } catch (Exception $e) {
            $response['errors'][] = 'Error al buscar fundación: ' . $e->getMessage();
            Log::error('❌ Error al buscar fundación: ' . $e->getMessage());
        }
        
        // 4. Intentar crear animal de prueba
        if ($fundacion) {
            try {
                $animalData = [
                    'fundacion_id' => $fundacion->id,
                    'nombre' => 'Test Animal ' . time(),
                    'tipo' => 'perro',
                    'edad' => 2,
                    'tipo_edad' => 'años',
                    'sexo' => 'macho',
                    'descripcion' => 'Animal de prueba para verificar guardado',
                    'imagen' => 'test-image.jpg',
                    'latitud' => 4.6097,
                    'longitud' => -74.0817,
                    'direccion' => 'Bogotá, Colombia - Prueba',
                    'estado' => 'disponible'
                ];
                
                $animal = Animal::create($animalData);
                
                $response['test_data'] = [
                    'animal_created' => true,
                    'animal_id' => $animal->id,
                    'animal_name' => $animal->nombre,
                    'fundacion_name' => $fundacion->nombre
                ];
                
                Log::info("🐕 Animal de prueba creado exitosamente: {$animal->nombre} (ID: {$animal->id})");
                
                // Verificar que se guardó correctamente
                $savedAnimal = Animal::find($animal->id);
                if ($savedAnimal) {
                    $response['test_data']['verification'] = 'Animal verificado en base de datos';
                    Log::info('✅ Animal verificado en base de datos');
                } else {
                    $response['errors'][] = 'Animal creado pero no se puede verificar';
                    Log::warning('⚠️ Animal creado pero no se puede verificar');
                }
                
            } catch (Exception $e) {
                $response['errors'][] = 'Error al crear animal: ' . $e->getMessage();
                $response['test_data'] = [
                    'animal_created' => false,
                    'error_details' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString()
                ];
                Log::error('❌ Error al crear animal: ' . $e->getMessage());
            }
        }
        
        // 5. Información del entorno
        $response['environment'] = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'storage_writable' => is_writable(storage_path()),
            'logs_writable' => is_writable(storage_path('logs'))
        ];
        
        Log::info('🧪 Prueba de guardado completada', $response);
        
        return response()->json($response, 200);
        
    } catch (Exception $e) {
        Log::error('💥 Error general en prueba: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Error general en la prueba',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Endpoint para limpiar animales de prueba
Route::get('/test-cleanup', function () {
    try {
        $deleted = Animal::where('nombre', 'like', 'Test Animal%')->delete();
        Log::info("🧹 Limpieza completada: $deleted animales de prueba eliminados");
        
        return response()->json([
            'status' => 'success',
            'message' => "Se eliminaron $deleted animales de prueba",
            'deleted_count' => $deleted
        ]);
    } catch (Exception $e) {
        Log::error('❌ Error en limpieza: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Error al limpiar animales de prueba',
            'error' => $e->getMessage()
        ], 500);
    }
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PerfilFundacion;
use App\Models\Animal;
use Exception;

class DiagnosticoController extends Controller
{
    public function diagnosticoAnimales()
    {
        $diagnostico = [];
        $diagnostico['timestamp'] = now()->format('Y-m-d H:i:s');
        $diagnostico['entorno'] = config('app.env');
        
        try {
            // 1. Verificar conexión a base de datos
            DB::connection()->getPdo();
            $diagnostico['base_datos']['conexion'] = '✅ CONECTADA';
            
            // 2. Contar fundaciones
            $fundaciones = PerfilFundacion::count();
            $diagnostico['base_datos']['fundaciones'] = $fundaciones;
            
            if ($fundaciones === 0) {
                $diagnostico['base_datos']['problema'] = '❌ NO HAY FUNDACIONES';
                $diagnostico['base_datos']['solucion'] = 'Ejecutar: php artisan db:seed --class=DemoDataSeeder --force';
            } else {
                $diagnostico['base_datos']['fundaciones_ok'] = '✅ HAY FUNDACIONES DISPONIBLES';
            }
            
            // 3. Contar animales existentes
            $animales = Animal::count();
            $diagnostico['base_datos']['animales_existentes'] = $animales;
            
            // 4. Intentar crear un animal de prueba (solo si hay fundaciones)
            if ($fundaciones > 0) {
                try {
                    $primeraFundacion = PerfilFundacion::first();
                    
                    $animalPrueba = new Animal([
                        'nombre' => 'Test Diagnóstico ' . time(),
                        'tipo' => 'perro',
                        'raza' => 'Mestizo',
                        'edad' => 2,
                        'tipo_edad' => 'años',
                        'sexo' => 'macho',
                        'descripcion' => 'Animal de prueba para diagnóstico',
                        'direccion' => 'Dirección de prueba',
                        'fundacion_id' => $primeraFundacion->id,
                        'estado' => 'adopcion'
                    ]);
                    
                    $animalPrueba->save();
                    
                    $diagnostico['prueba_guardado']['resultado'] = '✅ ANIMAL GUARDADO EXITOSAMENTE';
                    $diagnostico['prueba_guardado']['animal_id'] = $animalPrueba->id;
                    $diagnostico['prueba_guardado']['fundacion_usada'] = $primeraFundacion->nombre_fundacion;
                    
                    // Verificar que se guardó correctamente
                    $animalVerificacion = Animal::find($animalPrueba->id);
                    if ($animalVerificacion) {
                        $diagnostico['prueba_guardado']['verificacion'] = '✅ ANIMAL VERIFICADO EN BASE DE DATOS';
                    } else {
                        $diagnostico['prueba_guardado']['verificacion'] = '❌ ERROR: ANIMAL NO ENCONTRADO DESPUÉS DE GUARDAR';
                    }
                    
                    // Limpiar: eliminar el animal de prueba
                    $animalPrueba->delete();
                    $diagnostico['prueba_guardado']['limpieza'] = '✅ ANIMAL DE PRUEBA ELIMINADO';
                    
                } catch (Exception $e) {
                    $diagnostico['prueba_guardado']['resultado'] = '❌ ERROR AL GUARDAR ANIMAL';
                    $diagnostico['prueba_guardado']['error'] = $e->getMessage();
                    $diagnostico['prueba_guardado']['archivo'] = $e->getFile();
                    $diagnostico['prueba_guardado']['linea'] = $e->getLine();
                }
            } else {
                $diagnostico['prueba_guardado']['resultado'] = '⚠️ NO SE PUEDE PROBAR - NO HAY FUNDACIONES';
            }
            
            // 5. Información del sistema
            $diagnostico['sistema']['php_version'] = PHP_VERSION;
            $diagnostico['sistema']['laravel_version'] = app()->version();
            $diagnostico['sistema']['timezone'] = config('app.timezone');
            
            // 6. Variables de entorno críticas
            $diagnostico['configuracion']['app_key'] = config('app.key') ? '✅ CONFIGURADA' : '❌ NO CONFIGURADA';
            $diagnostico['configuracion']['app_debug'] = config('app.debug') ? 'true' : 'false';
            $diagnostico['configuracion']['db_connection'] = config('database.default');
            
        } catch (Exception $e) {
            $diagnostico['error_general'] = [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine()
            ];
        }
        
        // Generar recomendaciones
        $diagnostico['recomendaciones'] = [];
        
        if (isset($diagnostico['base_datos']['fundaciones']) && $diagnostico['base_datos']['fundaciones'] === 0) {
            $diagnostico['recomendaciones'][] = '🔧 EJECUTAR SEEDERS: php artisan db:seed --class=DemoDataSeeder --force';
        }
        
        if (isset($diagnostico['configuracion']['app_key']) && $diagnostico['configuracion']['app_key'] === '❌ NO CONFIGURADA') {
            $diagnostico['recomendaciones'][] = '🔧 GENERAR APP_KEY: php artisan key:generate';
        }
        
        if (isset($diagnostico['prueba_guardado']['resultado']) && strpos($diagnostico['prueba_guardado']['resultado'], '✅') !== false) {
            $diagnostico['recomendaciones'][] = '✅ EL GUARDADO DE ANIMALES FUNCIONA CORRECTAMENTE';
        }
        
        // Retornar como JSON formateado para fácil lectura
        return response()->json($diagnostico, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    public function ejecutarSeeders()
    {
        try {
            // Verificar si ya hay fundaciones
            $fundacionesExistentes = PerfilFundacion::count();
            
            if ($fundacionesExistentes > 0) {
                return response()->json([
                    'status' => 'info',
                    'mensaje' => 'Ya existen ' . $fundacionesExistentes . ' fundaciones en la base de datos',
                    'fundaciones' => PerfilFundacion::select('id', 'nombre_fundacion', 'email')->get()
                ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            
            // Ejecutar seeders
            \Artisan::call('db:seed', [
                '--class' => 'DemoDataSeeder',
                '--force' => true
            ]);
            
            $output = \Artisan::output();
            
            // Verificar resultados
            $fundacionesCreadas = PerfilFundacion::count();
            $animalesCreados = Animal::count();
            
            return response()->json([
                'status' => 'success',
                'mensaje' => 'Seeders ejecutados exitosamente',
                'resultados' => [
                    'fundaciones_creadas' => $fundacionesCreadas,
                    'animales_creados' => $animalesCreados,
                    'output_artisan' => $output
                ],
                'fundaciones' => PerfilFundacion::select('id', 'nombre_fundacion', 'email')->get()
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error al ejecutar seeders',
                'error' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine()
            ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
}
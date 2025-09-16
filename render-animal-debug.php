<?php

/**
 * Script de diagnóstico específico para el problema de guardado de animales en Render
 * Ejecutar en producción para identificar la causa del problema
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNÓSTICO DE GUARDADO DE ANIMALES EN RENDER ===\n\n";

try {
    // 1. Verificar conexión a base de datos
    echo "1. 🔍 VERIFICANDO CONEXIÓN A BASE DE DATOS...\n";
    $pdo = DB::connection()->getPdo();
    echo "   ✅ Conexión exitosa\n";
    echo "   📊 Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "   🏠 Host: " . config('database.connections.pgsql.host') . "\n";
    echo "   🗄️ Database: " . config('database.connections.pgsql.database') . "\n\n";

    // 2. Verificar tablas necesarias
    echo "2. 🔍 VERIFICANDO TABLAS...\n";
    $tables = ['usuarios', 'perfil_fundaciones', 'animales'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "   ✅ Tabla '$table': $count registros\n";
        } catch (Exception $e) {
            echo "   ❌ Error en tabla '$table': " . $e->getMessage() . "\n";
        }
    }
    echo "\n";

    // 3. Verificar fundaciones
    echo "3. 🔍 VERIFICANDO FUNDACIONES...\n";
    $fundaciones = App\Models\PerfilFundacion::all();
    if ($fundaciones->count() > 0) {
        echo "   ✅ Fundaciones encontradas: " . $fundaciones->count() . "\n";
        foreach ($fundaciones as $fundacion) {
            echo "   📋 ID: {$fundacion->id} - Nombre: {$fundacion->nombre}\n";
        }
    } else {
        echo "   ❌ NO HAY FUNDACIONES - Este es el problema principal\n";
        echo "   💡 Solución: Crear al menos una fundación\n";
    }
    echo "\n";

    // 4. Verificar usuarios
    echo "4. 🔍 VERIFICANDO USUARIOS...\n";
    $usuarios = App\Models\Usuario::where('tipo_usuario', 'fundacion')->get();
    if ($usuarios->count() > 0) {
        echo "   ✅ Usuarios de fundación encontrados: " . $usuarios->count() . "\n";
        foreach ($usuarios as $usuario) {
            echo "   👤 ID: {$usuario->id} - Email: {$usuario->email}\n";
        }
    } else {
        echo "   ⚠️ No hay usuarios de tipo 'fundacion'\n";
    }
    echo "\n";

    // 5. Intentar crear un animal de prueba
    echo "5. 🔍 PROBANDO GUARDADO DE ANIMAL...\n";
    
    if ($fundaciones->count() > 0) {
        $fundacion = $fundaciones->first();
        
        // Crear animal de prueba
        $animal = new App\Models\Animal();
        $animal->nombre = 'Test Animal Render ' . date('Y-m-d H:i:s');
        $animal->tipo = 'perro';
        $animal->edad = 2;
        $animal->tipo_edad = 'años';
        $animal->sexo = 'macho';
        $animal->descripcion = 'Animal de prueba para Render';
        $animal->latitud = -34.6037;
        $animal->longitud = -58.3816;
        $animal->direccion = 'Buenos Aires, Argentina';
        $animal->fundacion_id = $fundacion->id;
        $animal->estado = 'disponible';
        $animal->fecha_ingreso = now();
        
        // Habilitar logging de queries
        DB::enableQueryLog();
        
        try {
            $animal->save();
            echo "   ✅ Animal guardado exitosamente\n";
            echo "   🆔 ID del animal: " . $animal->id . "\n";
            echo "   📝 Nombre: " . $animal->nombre . "\n";
            echo "   🏠 Fundación: " . $animal->fundacion->nombre . "\n";
            
            // Mostrar queries ejecutadas
            $queries = DB::getQueryLog();
            echo "   📊 Queries ejecutadas: " . count($queries) . "\n";
            
        } catch (Exception $e) {
            echo "   ❌ ERROR AL GUARDAR ANIMAL:\n";
            echo "   🚨 Mensaje: " . $e->getMessage() . "\n";
            echo "   📁 Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
            
            // Mostrar queries que fallaron
            $queries = DB::getQueryLog();
            if (!empty($queries)) {
                echo "   📊 Última query ejecutada:\n";
                $lastQuery = end($queries);
                echo "   SQL: " . $lastQuery['query'] . "\n";
                echo "   Bindings: " . json_encode($lastQuery['bindings']) . "\n";
            }
        }
        
        DB::disableQueryLog();
    } else {
        echo "   ⏭️ Saltando prueba - No hay fundaciones disponibles\n";
    }
    echo "\n";

    // 6. Verificar configuración de Laravel
    echo "6. 🔍 VERIFICANDO CONFIGURACIÓN DE LARAVEL...\n";
    echo "   🌍 APP_ENV: " . config('app.env') . "\n";
    echo "   🐛 APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n";
    echo "   🔑 APP_KEY configurada: " . (config('app.key') ? 'Sí' : 'No') . "\n";
    echo "   🗄️ DB_CONNECTION: " . config('database.default') . "\n";
    echo "   💾 FILESYSTEM_DISK: " . config('filesystems.default') . "\n";
    echo "   📧 MAIL_MAILER: " . config('mail.default') . "\n";
    echo "\n";

    // 7. Verificar variables de entorno críticas
    echo "7. 🔍 VERIFICANDO VARIABLES DE ENTORNO...\n";
    $envVars = [
        'APP_KEY' => env('APP_KEY') ? 'Configurada' : 'NO CONFIGURADA',
        'DB_HOST' => env('DB_HOST', 'No configurada'),
        'DB_DATABASE' => env('DB_DATABASE', 'No configurada'),
        'DB_USERNAME' => env('DB_USERNAME', 'No configurada'),
        'DATABASE_URL' => env('DATABASE_URL') ? 'Configurada' : 'No configurada',
    ];
    
    foreach ($envVars as $var => $value) {
        $status = ($value === 'NO CONFIGURADA' || $value === 'No configurada') ? '❌' : '✅';
        echo "   $status $var: $value\n";
    }
    echo "\n";

    // 8. Verificar permisos y directorios
    echo "8. 🔍 VERIFICANDO PERMISOS Y DIRECTORIOS...\n";
    $directories = [
        'storage/app' => storage_path('app'),
        'storage/logs' => storage_path('logs'),
        'bootstrap/cache' => base_path('bootstrap/cache'),
    ];
    
    foreach ($directories as $name => $path) {
        if (is_dir($path)) {
            $writable = is_writable($path) ? 'Escribible' : 'NO escribible';
            $status = is_writable($path) ? '✅' : '❌';
            echo "   $status $name: $writable\n";
        } else {
            echo "   ❌ $name: No existe\n";
        }
    }
    echo "\n";

    // 9. Resumen y recomendaciones
    echo "9. 📋 RESUMEN Y RECOMENDACIONES...\n";
    
    if ($fundaciones->count() === 0) {
        echo "   🚨 PROBLEMA PRINCIPAL: No hay fundaciones en la base de datos\n";
        echo "   💡 SOLUCIÓN: Ejecutar seeder o crear fundación manualmente\n";
        echo "   📝 Comando: php artisan db:seed --class=DemoDataSeeder\n";
    } else {
        echo "   ✅ Fundaciones disponibles\n";
    }
    
    echo "\n   🔧 COMANDOS ÚTILES PARA RENDER:\n";
    echo "   - Ejecutar migraciones: php artisan migrate --force\n";
    echo "   - Ejecutar seeders: php artisan db:seed --force\n";
    echo "   - Limpiar caché: php artisan cache:clear\n";
    echo "   - Optimizar: php artisan optimize\n";

} catch (Exception $e) {
    echo "❌ ERROR CRÍTICO:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
echo "\n📞 PRÓXIMOS PASOS:\n";
echo "1. Si no hay fundaciones, ejecutar: php artisan db:seed --class=DemoDataSeeder\n";
echo "2. Verificar que las variables de entorno estén configuradas en Render\n";
echo "3. Hacer redeploy en Render después de los cambios\n";
echo "4. Probar el registro de animales desde la interfaz web\n";

?>
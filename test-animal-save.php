<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE GUARDADO DE ANIMALES ===\n\n";

try {
    // 1. Verificar conexión a base de datos
    echo "1. Verificando conexión a base de datos...\n";
    DB::connection()->getPdo();
    echo "✓ Conexión exitosa\n\n";

    // 2. Verificar fundaciones
    echo "2. Verificando fundaciones...\n";
    $fundaciones = App\Models\PerfilFundacion::all();
    echo "✓ Fundaciones encontradas: " . $fundaciones->count() . "\n";
    
    if ($fundaciones->count() > 0) {
        $fundacion = $fundaciones->first();
        echo "✓ Primera fundación: " . $fundacion->nombre . " (ID: " . $fundacion->id . ")\n\n";
        
        // 3. Intentar crear un animal
        echo "3. Creando animal de prueba...\n";
        
        $animal = new App\Models\Animal();
        $animal->nombre = 'Test Animal ' . date('Y-m-d H:i:s');
        $animal->tipo = 'perro';
        $animal->edad = 2;
        $animal->tipo_edad = 'años';
        $animal->sexo = 'macho';
        $animal->descripcion = 'Animal de prueba para verificar guardado';
        $animal->latitud = -34.6037;
        $animal->longitud = -58.3816;
        $animal->direccion = 'Buenos Aires, Argentina';
        $animal->fundacion_id = $fundacion->id;
        $animal->estado = 'disponible';
        $animal->fecha_ingreso = now();
        
        echo "✓ Datos del animal preparados\n";
        
        // Intentar guardar
        $animal->save();
        
        echo "✓ Animal guardado exitosamente con ID: " . $animal->id . "\n";
        echo "✓ Nombre: " . $animal->nombre . "\n";
        echo "✓ Fundación: " . $animal->fundacion->nombre . "\n\n";
        
        // 4. Verificar que se guardó correctamente
        echo "4. Verificando que el animal se guardó...\n";
        $animalGuardado = App\Models\Animal::find($animal->id);
        if ($animalGuardado) {
            echo "✓ Animal encontrado en la base de datos\n";
            echo "✓ Total de animales: " . App\Models\Animal::count() . "\n";
        } else {
            echo "✗ Error: Animal no encontrado después del guardado\n";
        }
        
    } else {
        echo "✗ No hay fundaciones disponibles\n";
    }
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DE LA PRUEBA ===\n";
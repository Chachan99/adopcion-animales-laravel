<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 PRUEBA DE VALIDACIONES DEL FORMULARIO DE ANIMALES\n";
echo "===================================================\n\n";

// Simular datos del formulario
$formData = [
    'nombre' => 'Firulais Test',
    'tipo' => 'perro',
    'edad' => 3,
    'tipo_edad' => 'años',
    'sexo' => 'macho',
    'descripcion' => 'Un perro muy amigable para pruebas',
    'latitud' => 4.6097,
    'longitud' => -74.0817,
    'direccion' => 'Bogotá, Colombia'
];

echo "1. 📝 DATOS DEL FORMULARIO:\n";
foreach ($formData as $key => $value) {
    echo "   $key: $value\n";
}

echo "\n2. ✅ VALIDACIONES:\n";

// Probar cada validación individualmente
$validationRules = [
    'nombre' => 'required|string|max:255',
    'tipo' => 'required|string',
    'edad' => 'required|integer|min:0',
    'tipo_edad' => 'required|in:meses,años,anios',
    'sexo' => 'required|string|in:macho,hembra',
    'descripcion' => 'required|string',
    'latitud' => 'required|numeric',
    'longitud' => 'required|numeric',
    'direccion' => 'required|string|max:500'
];

$validator = Validator::make($formData, $validationRules);

if ($validator->passes()) {
    echo "   ✅ Todas las validaciones PASARON\n";
} else {
    echo "   ❌ Validaciones FALLARON:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "   - $error\n";
    }
}

echo "\n3. 🔐 VERIFICAR AUTENTICACIÓN:\n";
try {
    // Simular usuario autenticado
    $usuario = DB::table('usuarios')->where('rol', 'fundacion')->first();
    if ($usuario) {
        echo "   ✅ Usuario fundación encontrado: {$usuario->nombre} (ID: {$usuario->id})\n";
        
        // Verificar perfil de fundación
        $fundacion = DB::table('perfil_fundaciones')->where('usuario_id', $usuario->id)->first();
        if ($fundacion) {
            echo "   ✅ Perfil de fundación encontrado: {$fundacion->nombre} (ID: {$fundacion->id})\n";
        } else {
            echo "   ❌ No se encontró perfil de fundación para el usuario\n";
        }
    } else {
        echo "   ❌ No se encontró usuario con rol 'fundacion'\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n4. 🗄️ SIMULACIÓN DE GUARDADO:\n";
try {
    if ($validator->passes() && $usuario && $fundacion) {
        // Simular el guardado sin imagen
        $animalData = [
            'fundacion_id' => $fundacion->id,
            'nombre' => $formData['nombre'],
            'tipo' => $formData['tipo'],
            'edad' => $formData['edad'],
            'tipo_edad' => $formData['tipo_edad'],
            'sexo' => $formData['sexo'],
            'descripcion' => $formData['descripcion'],
            'latitud' => $formData['latitud'],
            'longitud' => $formData['longitud'],
            'direccion' => $formData['direccion'],
            'estado' => 'disponible',
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        $animalId = DB::table('animales')->insertGetId($animalData);
        echo "   ✅ Animal guardado exitosamente (ID: $animalId)\n";
        
        // Verificar que se guardó
        $animal = DB::table('animales')->find($animalId);
        if ($animal) {
            echo "   ✅ Animal verificado en base de datos: {$animal->nombre}\n";
        }
        
    } else {
        echo "   ❌ No se puede simular guardado - faltan requisitos\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error en simulación: " . $e->getMessage() . "\n";
}

echo "\n5. 🚨 POSIBLES PROBLEMAS EN RENDER:\n";
echo "   1. ❓ Imagen requerida pero no se puede subir\n";
echo "   2. ❓ Variables de entorno diferentes (DATABASE_URL)\n";
echo "   3. ❓ Permisos de escritura en storage\n";
echo "   4. ❓ Timeout en transacciones de base de datos\n";
echo "   5. ❓ Errores de validación no mostrados al usuario\n";

echo "\n✅ Prueba completada.\n";
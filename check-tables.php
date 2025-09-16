<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 VERIFICANDO TABLAS Y GUARDADO DE ANIMALES\n";
echo "============================================\n\n";

// 1. Verificar tablas existentes
echo "1. 📋 TABLAS EXISTENTES:\n";
try {
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    foreach ($tables as $table) {
        echo "   ✅ " . $table->name . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 2. Verificar tabla usuarios específicamente
echo "\n2. 👥 VERIFICAR TABLA USUARIOS:\n";
try {
    if (DB::getSchemaBuilder()->hasTable('usuarios')) {
        echo "   ✅ Tabla 'usuarios' existe\n";
        $count = DB::table('usuarios')->count();
        echo "   📊 Registros: $count\n";
        
        // Mostrar algunos usuarios
        $usuarios = DB::table('usuarios')->limit(3)->get();
        foreach ($usuarios as $user) {
            echo "   - ID: {$user->id}, Nombre: {$user->nombre}, Rol: {$user->rol}\n";
        }
    } else {
        echo "   ❌ Tabla 'usuarios' NO existe\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 3. Verificar tabla perfil_fundaciones
echo "\n3. 🏢 VERIFICAR TABLA PERFIL_FUNDACIONES:\n";
try {
    if (DB::getSchemaBuilder()->hasTable('perfil_fundaciones')) {
        echo "   ✅ Tabla 'perfil_fundaciones' existe\n";
        $count = DB::table('perfil_fundaciones')->count();
        echo "   📊 Registros: $count\n";
        
        // Mostrar algunas fundaciones
        $fundaciones = DB::table('perfil_fundaciones')->limit(3)->get();
        foreach ($fundaciones as $fund) {
            echo "   - ID: {$fund->id}, Nombre: {$fund->nombre}\n";
        }
    } else {
        echo "   ❌ Tabla 'perfil_fundaciones' NO existe\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 4. Probar inserción de animal
echo "\n4. 🐕 PRUEBA DE INSERCIÓN DE ANIMAL:\n";
try {
    // Buscar una fundación existente
    $fundacion = DB::table('perfil_fundaciones')->first();
    
    if ($fundacion) {
        echo "   ✅ Fundación encontrada: {$fundacion->nombre} (ID: {$fundacion->id})\n";
        
        // Intentar insertar un animal de prueba
        $animalId = DB::table('animales')->insertGetId([
            'fundacion_id' => $fundacion->id,
            'nombre' => 'Test Animal ' . time(),
            'tipo' => 'perro',
            'raza' => 'Mestizo',
            'edad' => 2,
            'tipo_edad' => 'años',
            'sexo' => 'macho',
            'descripcion' => 'Animal de prueba para debugging',
            'estado' => 'disponible',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "   ✅ Animal insertado correctamente (ID: $animalId)\n";
        
        // Verificar que se guardó
        $animal = DB::table('animales')->find($animalId);
        if ($animal) {
            echo "   ✅ Animal recuperado: {$animal->nombre}\n";
        }
        
    } else {
        echo "   ❌ No se encontró ninguna fundación\n";
        echo "   💡 Creando fundación de prueba...\n";
        
        // Crear usuario de prueba
        $userId = DB::table('usuarios')->insertGetId([
            'nombre' => 'Usuario Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'rol' => 'fundacion',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Crear fundación de prueba
        $fundacionId = DB::table('perfil_fundaciones')->insertGetId([
            'usuario_id' => $userId,
            'nombre' => 'Fundación Test',
            'descripcion' => 'Fundación de prueba',
            'telefono' => '123456789',
            'email' => 'test@fundacion.com',
            'direccion' => 'Dirección test',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "   ✅ Fundación de prueba creada (ID: $fundacionId)\n";
        
        // Ahora insertar el animal
        $animalId = DB::table('animales')->insertGetId([
            'fundacion_id' => $fundacionId,
            'nombre' => 'Test Animal ' . time(),
            'tipo' => 'perro',
            'raza' => 'Mestizo',
            'edad' => 2,
            'tipo_edad' => 'años',
            'sexo' => 'macho',
            'descripcion' => 'Animal de prueba para debugging',
            'estado' => 'disponible',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "   ✅ Animal insertado correctamente (ID: $animalId)\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error en inserción: " . $e->getMessage() . "\n";
    echo "   📋 Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✅ Verificación completada.\n";
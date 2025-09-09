<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFICANDO DATOS DE FUNDACIONES ===\n\n";

try {
    echo "📊 Perfil Fundaciones:\n";
    $fundaciones = DB::table('perfil_fundaciones')
        ->select('id', 'usuario_id', 'nombre')
        ->get();
    
    foreach ($fundaciones as $f) {
        echo "   ID: {$f->id}, Usuario_ID: {$f->usuario_id}, Nombre: {$f->nombre}\n";
    }
    
    echo "\n📊 Animales existentes:\n";
    $animales = DB::table('animales')
        ->select('id', 'nombre', 'fundacion_id')
        ->get();
    
    foreach ($animales as $a) {
        echo "   ID: {$a->id}, Nombre: {$a->nombre}, Fundacion_ID: {$a->fundacion_id}\n";
    }
    
    echo "\n📊 Verificando relación:\n";
    $relacion = DB::table('animales as a')
        ->join('perfil_fundaciones as pf', 'a.fundacion_id', '=', 'pf.usuario_id')
        ->select('a.id as animal_id', 'a.nombre as animal_nombre', 'pf.id as fundacion_id', 'pf.nombre as fundacion_nombre')
        ->get();
    
    if ($relacion->count() > 0) {
        foreach ($relacion as $r) {
            echo "   ✅ Animal '{$r->animal_nombre}' (ID: {$r->animal_id}) -> Fundación '{$r->fundacion_nombre}' (ID: {$r->fundacion_id})\n";
        }
    } else {
        echo "   ❌ No hay relaciones válidas entre animales y fundaciones\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
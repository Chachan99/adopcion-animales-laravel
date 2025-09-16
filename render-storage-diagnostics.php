<?php
echo "=== DIAGNÓSTICO DE ALMACENAMIENTO EN RENDER ===\n\n";

// 1. Verificar configuración de filesystem
echo "📁 CONFIGURACIÓN DE FILESYSTEM:\n";
echo "- Default disk: " . config('filesystems.default') . "\n";
echo "- APP_URL: " . env('APP_URL') . "\n";
echo "- FILESYSTEM_DISK: " . env('FILESYSTEM_DISK', 'local') . "\n\n";

// 2. Verificar directorios de storage
echo "📂 DIRECTORIOS DE STORAGE:\n";
$storagePublic = storage_path('app/public');
echo "- Storage public path: $storagePublic\n";
echo "- Storage public exists: " . (is_dir($storagePublic) ? 'SÍ' : 'NO') . "\n";
echo "- Storage public writable: " . (is_writable($storagePublic) ? 'SÍ' : 'NO') . "\n";

$publicStorage = public_path('storage');
echo "- Public storage path: $publicStorage\n";
echo "- Public storage exists: " . (is_dir($publicStorage) ? 'SÍ' : 'NO') . "\n";
echo "- Public storage is symlink: " . (is_link($publicStorage) ? 'SÍ' : 'NO') . "\n\n";

// 3. Verificar subdirectorios específicos
echo "📁 SUBDIRECTORIOS ESPECÍFICOS:\n";
$directories = ['animales', 'usuarios', 'fundaciones', 'noticias', 'animales-perdidos'];
foreach ($directories as $dir) {
    $path = storage_path("app/public/$dir");
    echo "- $dir: " . (is_dir($path) ? 'EXISTS' : 'MISSING') . " | Writable: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
}
echo "\n";

// 4. Verificar permisos
echo "🔐 PERMISOS:\n";
echo "- Storage app permissions: " . substr(sprintf('%o', fileperms(storage_path('app'))), -4) . "\n";
echo "- Storage public permissions: " . substr(sprintf('%o', fileperms($storagePublic)), -4) . "\n";
if (is_dir($publicStorage)) {
    echo "- Public storage permissions: " . substr(sprintf('%o', fileperms($publicStorage)), -4) . "\n";
}
echo "\n";

// 5. Test de escritura
echo "✍️ TEST DE ESCRITURA:\n";
try {
    $testFile = storage_path('app/public/test-render.txt');
    file_put_contents($testFile, 'Test file for Render - ' . date('Y-m-d H:i:s'));
    echo "- Escritura en storage/app/public: ✅ EXITOSA\n";
    
    if (file_exists($testFile)) {
        echo "- Archivo test creado: ✅ SÍ\n";
        unlink($testFile);
        echo "- Archivo test eliminado: ✅ SÍ\n";
    }
} catch (Exception $e) {
    echo "- Escritura en storage/app/public: ❌ ERROR - " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Verificar archivos existentes
echo "📄 ARCHIVOS EXISTENTES:\n";
foreach ($directories as $dir) {
    $path = storage_path("app/public/$dir");
    if (is_dir($path)) {
        $files = glob($path . '/*');
        echo "- $dir: " . count($files) . " archivos\n";
    }
}
echo "\n";

// 7. Información del sistema
echo "🖥️ INFORMACIÓN DEL SISTEMA:\n";
echo "- PHP Version: " . PHP_VERSION . "\n";
echo "- OS: " . PHP_OS . "\n";
echo "- User: " . get_current_user() . "\n";
echo "- Working directory: " . getcwd() . "\n";
echo "- Temp directory: " . sys_get_temp_dir() . "\n\n";

// 8. Recomendaciones para Render
echo "💡 RECOMENDACIONES PARA RENDER:\n";
echo "1. Render tiene un sistema de archivos efímero - los archivos se pierden en cada deploy\n";
echo "2. Se recomienda usar almacenamiento externo como Cloudinary o AWS S3\n";
echo "3. Verificar que el comando 'php artisan storage:link' se ejecute en el build\n";
echo "4. Considerar usar variables de entorno para configurar almacenamiento externo\n\n";

echo "=== DIAGNÓSTICO COMPLETADO ===\n";
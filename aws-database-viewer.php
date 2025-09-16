<?php
/**
 * Visor de Base de Datos AWS RDS PostgreSQL
 * Script para conectar y visualizar la base de datos PostgreSQL en AWS RDS
 */

echo "=== VISOR DE BASE DE DATOS AWS RDS POSTGRESQL ===\n\n";

// Configuración de la base de datos (puedes modificar estos valores)
$config = [
    'host' => 'tu_host_postgresql.amazonaws.com',  // Reemplazar con tu endpoint RDS
    'port' => '5432',
    'database' => 'tu_database_name',              // Reemplazar con tu nombre de BD
    'username' => 'tu_username',                   // Reemplazar con tu usuario
    'password' => 'tu_password'                    // Reemplazar con tu contraseña
];

// También puedes usar variables de entorno si las tienes configuradas
if (getenv('DB_HOST')) {
    $config['host'] = getenv('DB_HOST');
    $config['port'] = getenv('DB_PORT') ?: '5432';
    $config['database'] = getenv('DB_DATABASE');
    $config['username'] = getenv('DB_USERNAME');
    $config['password'] = getenv('DB_PASSWORD');
}

echo "1. Configuración de conexión:\n";
echo "   Host: " . $config['host'] . "\n";
echo "   Puerto: " . $config['port'] . "\n";
echo "   Base de datos: " . $config['database'] . "\n";
echo "   Usuario: " . $config['username'] . "\n";
echo "   Contraseña: " . str_repeat('*', strlen($config['password'])) . "\n\n";

try {
    // Crear conexión PDO a PostgreSQL
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 10
    ]);
    
    echo "✅ Conexión exitosa a AWS RDS PostgreSQL\n\n";
    
    // 2. Obtener información de la base de datos
    echo "2. Información de la base de datos:\n";
    $version = $pdo->query("SELECT version()")->fetchColumn();
    echo "   Versión PostgreSQL: " . substr($version, 0, 50) . "...\n";
    
    $dbSize = $pdo->query("SELECT pg_size_pretty(pg_database_size(current_database()))")->fetchColumn();
    echo "   Tamaño de la BD: " . $dbSize . "\n\n";
    
    // 3. Listar todas las tablas
    echo "3. Tablas disponibles:\n";
    $tables = $pdo->query("
        SELECT table_name, 
               (SELECT COUNT(*) FROM information_schema.columns WHERE table_name = t.table_name) as column_count
        FROM information_schema.tables t
        WHERE table_schema = 'public' 
        AND table_type = 'BASE TABLE'
        ORDER BY table_name
    ")->fetchAll();
    
    if (empty($tables)) {
        echo "   ⚠️  No se encontraron tablas en la base de datos\n\n";
    } else {
        foreach ($tables as $table) {
            // Contar registros en cada tabla
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM \"{$table['table_name']}\"")->fetchColumn();
                echo "   📋 {$table['table_name']} ({$table['column_count']} columnas, {$count} registros)\n";
            } catch (Exception $e) {
                echo "   📋 {$table['table_name']} ({$table['column_count']} columnas, error al contar)\n";
            }
        }
        echo "\n";
    }
    
    // 4. Verificar tablas principales del sistema
    echo "4. Verificación de tablas principales:\n";
    $mainTables = ['usuarios', 'animales', 'perfil_fundaciones', 'solicitudes_adopcion', 'donaciones', 'noticias'];
    
    foreach ($mainTables as $tableName) {
        $exists = $pdo->query("
            SELECT COUNT(*) 
            FROM information_schema.tables 
            WHERE table_schema = 'public' 
            AND table_name = '$tableName'
        ")->fetchColumn();
        
        if ($exists) {
            $count = $pdo->query("SELECT COUNT(*) FROM \"$tableName\"")->fetchColumn();
            echo "   ✅ $tableName: $count registros\n";
        } else {
            echo "   ❌ $tableName: No existe\n";
        }
    }
    echo "\n";
    
    // 5. Mostrar estructura de tabla específica (usuarios como ejemplo)
    if (in_array('usuarios', array_column($tables, 'table_name'))) {
        echo "5. Estructura de la tabla 'usuarios':\n";
        $columns = $pdo->query("
            SELECT column_name, data_type, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_name = 'usuarios'
            ORDER BY ordinal_position
        ")->fetchAll();
        
        foreach ($columns as $column) {
            $nullable = $column['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $column['column_default'] ? " DEFAULT: {$column['column_default']}" : '';
            echo "   - {$column['column_name']}: {$column['data_type']} ({$nullable}){$default}\n";
        }
        echo "\n";
        
        // Mostrar algunos registros de ejemplo
        echo "6. Registros de ejemplo (usuarios):\n";
        $users = $pdo->query("SELECT id, name, email, rol, created_at FROM usuarios LIMIT 5")->fetchAll();
        
        if (empty($users)) {
            echo "   No hay usuarios registrados\n\n";
        } else {
            foreach ($users as $user) {
                echo "   ID: {$user['id']} | {$user['name']} | {$user['email']} | {$user['rol']} | {$user['created_at']}\n";
            }
            echo "\n";
        }
    }
    
    // 6. Verificar migraciones
    if (in_array('migrations', array_column($tables, 'table_name'))) {
        echo "7. Estado de migraciones:\n";
        $migrations = $pdo->query("SELECT COUNT(*) FROM migrations")->fetchColumn();
        echo "   Total de migraciones ejecutadas: $migrations\n";
        
        $lastMigration = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 1")->fetchColumn();
        echo "   Última migración: $lastMigration\n\n";
    }
    
    echo "=== RESUMEN ===\n";
    echo "✅ Conexión a AWS RDS PostgreSQL: EXITOSA\n";
    echo "📊 Total de tablas: " . count($tables) . "\n";
    echo "🔧 Base de datos configurada correctamente\n\n";
    
    echo "💡 CONSEJOS:\n";
    echo "1. Para conectarte desde herramientas externas usa:\n";
    echo "   Host: {$config['host']}\n";
    echo "   Puerto: {$config['port']}\n";
    echo "   Base de datos: {$config['database']}\n";
    echo "   Usuario: {$config['username']}\n\n";
    
    echo "2. Herramientas recomendadas para visualizar:\n";
    echo "   - pgAdmin (https://www.pgadmin.org/)\n";
    echo "   - DBeaver (https://dbeaver.io/)\n";
    echo "   - TablePlus (https://tableplus.com/)\n";
    echo "   - DataGrip (https://www.jetbrains.com/datagrip/)\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión a la base de datos:\n";
    echo "   Mensaje: " . $e->getMessage() . "\n";
    echo "   Código: " . $e->getCode() . "\n\n";
    
    echo "🔧 SOLUCIONES POSIBLES:\n";
    echo "1. Verificar que las credenciales sean correctas\n";
    echo "2. Comprobar que el endpoint RDS sea accesible\n";
    echo "3. Verificar que el Security Group permita conexiones en puerto 5432\n";
    echo "4. Confirmar que la instancia RDS esté ejecutándose\n";
    echo "5. Verificar que PHP tenga la extensión pdo_pgsql instalada\n\n";
    
    echo "📋 CONFIGURACIÓN ACTUAL:\n";
    echo "   Host: {$config['host']}\n";
    echo "   Puerto: {$config['port']}\n";
    echo "   Base de datos: {$config['database']}\n";
    echo "   Usuario: {$config['username']}\n\n";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n\n";
}

echo "=== FIN DEL ANÁLISIS ===\n";
?>
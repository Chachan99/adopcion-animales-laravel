<?php
/**
 * Visor Web de Base de Datos AWS RDS PostgreSQL
 * Herramienta web para visualizar y explorar la base de datos
 */

// Configuración de la base de datos
$config = [
    'host' => getenv('DB_HOST') ?: 'tu_host_postgresql.amazonaws.com',
    'port' => getenv('DB_PORT') ?: '5432',
    'database' => getenv('DB_DATABASE') ?: 'tu_database_name',
    'username' => getenv('DB_USERNAME') ?: 'tu_username',
    'password' => getenv('DB_PASSWORD') ?: 'tu_password'
];

$pdo = null;
$error = null;
$tables = [];
$selectedTable = $_GET['table'] ?? null;
$query = $_POST['query'] ?? null;

// Intentar conexión
try {
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 10
    ]);
    
    // Obtener lista de tablas
    $tables = $pdo->query("
        SELECT table_name, 
               (SELECT COUNT(*) FROM information_schema.columns WHERE table_name = t.table_name) as column_count
        FROM information_schema.tables t
        WHERE table_schema = 'public' 
        AND table_type = 'BASE TABLE'
        ORDER BY table_name
    ")->fetchAll();
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWS RDS Database Viewer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.8;
            font-size: 1.1em;
        }
        
        .content {
            padding: 30px;
        }
        
        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .success {
            background: #efe;
            border: 1px solid #cfc;
            color: #3c3;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #007bff;
        }
        
        .info-card h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .table-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }
        
        .table-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: #e9ecef;
        }
        
        .table-card h4 {
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .table-card small {
            color: #666;
        }
        
        .query-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .query-section h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        textarea {
            width: 100%;
            height: 120px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            resize: vertical;
        }
        
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .results-table th {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .results-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .results-table tr:hover {
            background: #f8f9fa;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .back-btn:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗄️ AWS RDS Database Viewer</h1>
            <p>Explorador interactivo de base de datos PostgreSQL</p>
        </div>
        
        <div class="content">
            <?php if ($error): ?>
                <div class="error">
                    <h3>❌ Error de Conexión</h3>
                    <p><strong>Mensaje:</strong> <?= htmlspecialchars($error) ?></p>
                    <br>
                    <p><strong>Configuración actual:</strong></p>
                    <ul>
                        <li>Host: <?= htmlspecialchars($config['host']) ?></li>
                        <li>Puerto: <?= htmlspecialchars($config['port']) ?></li>
                        <li>Base de datos: <?= htmlspecialchars($config['database']) ?></li>
                        <li>Usuario: <?= htmlspecialchars($config['username']) ?></li>
                    </ul>
                    <br>
                    <p><strong>Soluciones:</strong></p>
                    <ul>
                        <li>Verificar credenciales de AWS RDS</li>
                        <li>Comprobar Security Groups (puerto 5432)</li>
                        <li>Verificar que la instancia RDS esté activa</li>
                        <li>Instalar extensión PHP pdo_pgsql</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="success">
                    <h3>✅ Conexión Exitosa</h3>
                    <p>Conectado a AWS RDS PostgreSQL: <strong><?= htmlspecialchars($config['host']) ?></strong></p>
                </div>
                
                <?php if ($selectedTable): ?>
                    <!-- Vista de tabla específica -->
                    <a href="?" class="back-btn">← Volver a la lista de tablas</a>
                    
                    <h2>📋 Tabla: <?= htmlspecialchars($selectedTable) ?></h2>
                    
                    <?php
                    try {
                        // Obtener estructura de la tabla
                        $columns = $pdo->query("
                            SELECT column_name, data_type, is_nullable, column_default
                            FROM information_schema.columns
                            WHERE table_name = '$selectedTable'
                            ORDER BY ordinal_position
                        ")->fetchAll();
                        
                        echo "<h3>Estructura de la tabla:</h3>";
                        echo "<table class='results-table'>";
                        echo "<tr><th>Columna</th><th>Tipo</th><th>Nullable</th><th>Default</th></tr>";
                        foreach ($columns as $column) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($column['column_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($column['data_type']) . "</td>";
                            echo "<td>" . ($column['is_nullable'] === 'YES' ? 'Sí' : 'No') . "</td>";
                            echo "<td>" . htmlspecialchars($column['column_default'] ?: 'N/A') . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        
                        // Mostrar datos de la tabla
                        $data = $pdo->query("SELECT * FROM \"$selectedTable\" LIMIT 50")->fetchAll();
                        
                        if (!empty($data)) {
                            echo "<h3>Datos (primeros 50 registros):</h3>";
                            echo "<table class='results-table'>";
                            echo "<tr>";
                            foreach (array_keys($data[0]) as $column) {
                                echo "<th>" . htmlspecialchars($column) . "</th>";
                            }
                            echo "</tr>";
                            
                            foreach ($data as $row) {
                                echo "<tr>";
                                foreach ($row as $value) {
                                    echo "<td>" . htmlspecialchars($value ?: 'NULL') . "</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No hay datos en esta tabla.</p>";
                        }
                        
                    } catch (Exception $e) {
                        echo "<div class='error'>Error al consultar la tabla: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                    
                <?php else: ?>
                    <!-- Vista principal -->
                    <div class="info-grid">
                        <div class="info-card">
                            <h3>🏢 Servidor</h3>
                            <p><?= htmlspecialchars($config['host']) ?></p>
                        </div>
                        <div class="info-card">
                            <h3>🗃️ Base de Datos</h3>
                            <p><?= htmlspecialchars($config['database']) ?></p>
                        </div>
                        <div class="info-card">
                            <h3>📊 Total Tablas</h3>
                            <p><?= count($tables) ?> tablas</p>
                        </div>
                        <div class="info-card">
                            <h3>👤 Usuario</h3>
                            <p><?= htmlspecialchars($config['username']) ?></p>
                        </div>
                    </div>
                    
                    <h2>📋 Tablas Disponibles</h2>
                    <div class="tables-grid">
                        <?php foreach ($tables as $table): ?>
                            <?php
                            try {
                                $count = $pdo->query("SELECT COUNT(*) FROM \"{$table['table_name']}\"")->fetchColumn();
                            } catch (Exception $e) {
                                $count = 'Error';
                            }
                            ?>
                            <a href="?table=<?= urlencode($table['table_name']) ?>" class="table-card">
                                <h4><?= htmlspecialchars($table['table_name']) ?></h4>
                                <small><?= $table['column_count'] ?> columnas • <?= $count ?> registros</small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Sección de consultas personalizadas -->
                    <div class="query-section">
                        <h3>🔍 Ejecutar Consulta SQL</h3>
                        <form method="post">
                            <textarea name="query" placeholder="Escribe tu consulta SQL aquí... (ej: SELECT * FROM usuarios LIMIT 10)"><?= htmlspecialchars($query ?: '') ?></textarea>
                            <button type="submit" class="btn">Ejecutar Consulta</button>
                        </form>
                        
                        <?php if ($query): ?>
                            <?php
                            try {
                                $result = $pdo->query($query)->fetchAll();
                                
                                if (!empty($result)) {
                                    echo "<h4>Resultados:</h4>";
                                    echo "<table class='results-table'>";
                                    echo "<tr>";
                                    foreach (array_keys($result[0]) as $column) {
                                        echo "<th>" . htmlspecialchars($column) . "</th>";
                                    }
                                    echo "</tr>";
                                    
                                    foreach ($result as $row) {
                                        echo "<tr>";
                                        foreach ($row as $value) {
                                            echo "<td>" . htmlspecialchars($value ?: 'NULL') . "</td>";
                                        }
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                } else {
                                    echo "<p>La consulta no devolvió resultados.</p>";
                                }
                                
                            } catch (Exception $e) {
                                echo "<div class='error'>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</div>";
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
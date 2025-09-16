# 🗄️ Guía Completa: Cómo Ver la Base de Datos en AWS RDS

## 📋 Resumen

Esta guía te ayudará a configurar y acceder a tu base de datos PostgreSQL en AWS RDS de diferentes maneras.

## 🔧 Herramientas Creadas

### 1. Script de Línea de Comandos
**Archivo:** `aws-database-viewer.php`
- Conexión directa a PostgreSQL en AWS RDS
- Análisis completo de tablas y estructura
- Verificación de datos y migraciones
- Diagnóstico de problemas de conexión

### 2. Visor Web Interactivo
**Archivo:** `aws-db-web-viewer.php`
- Interfaz web moderna y fácil de usar
- Exploración visual de tablas
- Ejecutor de consultas SQL personalizado
- Vista detallada de estructura de tablas

## 🚀 Cómo Usar las Herramientas

### Opción 1: Script de Línea de Comandos

```bash
# Ejecutar desde la terminal
php aws-database-viewer.php
```

### Opción 2: Visor Web

1. Iniciar servidor local:
```bash
php -S localhost:8000
```

2. Abrir en el navegador:
```
http://localhost:8000/aws-db-web-viewer.php
```

## ⚙️ Configuración de Credenciales

### Método 1: Variables de Entorno (Recomendado)

Configura estas variables en tu sistema o archivo `.env`:

```bash
DB_HOST=tu-rds-endpoint.amazonaws.com
DB_PORT=5432
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Método 2: Edición Directa

Edita los archivos PHP y reemplaza:
- `tu_host_postgresql.amazonaws.com` → Tu endpoint RDS
- `tu_database_name` → Nombre de tu base de datos
- `tu_username` → Tu usuario de PostgreSQL
- `tu_password` → Tu contraseña

## 🏗️ Configuración de AWS RDS

### 1. Crear Instancia RDS PostgreSQL

1. **Accede a AWS Console** → RDS
2. **Crear base de datos**
3. **Selecciona PostgreSQL**
4. **Configuración:**
   - Tipo de instancia: `db.t3.micro` (capa gratuita)
   - Almacenamiento: 20 GB
   - Usuario maestro: `postgres` o personalizado
   - Contraseña: Segura y memorable

### 2. Configurar Security Groups

1. **Ve a EC2** → Security Groups
2. **Encuentra el SG de tu RDS**
3. **Agregar regla de entrada:**
   - Tipo: PostgreSQL
   - Puerto: 5432
   - Origen: Tu IP o 0.0.0.0/0 (menos seguro)

### 3. Obtener Endpoint

1. **Ve a RDS** → Databases
2. **Selecciona tu instancia**
3. **Copia el Endpoint** (ej: `mydb.abc123.us-east-1.rds.amazonaws.com`)

## 🔍 Herramientas Externas Recomendadas

### 1. pgAdmin (Gratuito)
- **Descarga:** https://www.pgadmin.org/
- **Características:** Interfaz completa, gestión avanzada
- **Configuración:**
  - Host: Tu endpoint RDS
  - Puerto: 5432
  - Base de datos: Tu nombre de BD
  - Usuario/Contraseña: Tus credenciales

### 2. DBeaver (Gratuito)
- **Descarga:** https://dbeaver.io/
- **Características:** Multiplataforma, soporte múltiples BD
- **Configuración:** Similar a pgAdmin

### 3. TablePlus (Pago)
- **Descarga:** https://tableplus.com/
- **Características:** Interfaz moderna, muy intuitivo

### 4. DataGrip (Pago - JetBrains)
- **Descarga:** https://www.jetbrains.com/datagrip/
- **Características:** IDE completo para bases de datos

## 🐛 Solución de Problemas

### Error: "could not connect to server"

**Causas posibles:**
1. **Security Group mal configurado**
   - Solución: Permitir puerto 5432 desde tu IP

2. **Endpoint incorrecto**
   - Solución: Verificar endpoint en AWS Console

3. **Instancia RDS detenida**
   - Solución: Iniciar instancia en AWS Console

### Error: "authentication failed"

**Causas posibles:**
1. **Credenciales incorrectas**
   - Solución: Verificar usuario/contraseña

2. **Base de datos no existe**
   - Solución: Crear base de datos o usar la correcta

### Error: "PHP extension not found"

**Solución:**
```bash
# Ubuntu/Debian
sudo apt-get install php-pgsql

# CentOS/RHEL
sudo yum install php-pgsql

# Windows (XAMPP)
# Descomentar extension=pdo_pgsql en php.ini
```

## 📊 Comandos SQL Útiles

### Información General
```sql
-- Versión de PostgreSQL
SELECT version();

-- Tamaño de la base de datos
SELECT pg_size_pretty(pg_database_size(current_database()));

-- Listar todas las tablas
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public';
```

### Análisis de Tablas
```sql
-- Contar registros en todas las tablas
SELECT 
    schemaname,
    tablename,
    n_tup_ins - n_tup_del as row_count
FROM pg_stat_user_tables;

-- Estructura de una tabla
SELECT column_name, data_type, is_nullable 
FROM information_schema.columns 
WHERE table_name = 'nombre_tabla';
```

### Consultas de Aplicación
```sql
-- Usuarios registrados
SELECT id, name, email, rol, created_at FROM usuarios LIMIT 10;

-- Animales disponibles
SELECT id, nombre, especie, raza, estado FROM animales WHERE estado = 'disponible';

-- Solicitudes de adopción recientes
SELECT * FROM solicitudes_adopcion ORDER BY created_at DESC LIMIT 5;
```

## 🔐 Mejores Prácticas de Seguridad

1. **Nunca uses 0.0.0.0/0 en Security Groups** para producción
2. **Usa SSL/TLS** para conexiones
3. **Crea usuarios específicos** con permisos limitados
4. **Habilita logs de auditoría** en RDS
5. **Usa IAM Database Authentication** cuando sea posible

## 💡 Consejos Adicionales

1. **Monitoreo:** Configura CloudWatch para monitorear tu RDS
2. **Backups:** Habilita backups automáticos
3. **Actualizaciones:** Mantén PostgreSQL actualizado
4. **Costos:** Usa instancias t3.micro para desarrollo (capa gratuita)
5. **Multi-AZ:** Para producción, considera Multi-AZ para alta disponibilidad

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs de RDS en AWS Console
2. Verifica la conectividad de red
3. Consulta la documentación de AWS RDS
4. Usa las herramientas de diagnóstico incluidas

---

**¡Ahora tienes todo lo necesario para acceder y gestionar tu base de datos PostgreSQL en AWS RDS!** 🎉
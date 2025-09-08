# Guía de Despliegue - Sistema de Adopción de Animales

## Despliegue en Railway

Railway es una plataforma gratuita que ofrece hosting para aplicaciones Laravel con base de datos PostgreSQL incluida.

### Prerrequisitos

1. Cuenta en [Railway](https://railway.app)
2. Repositorio Git (GitHub, GitLab, etc.)
3. Código fuente preparado para producción

### Pasos para el Despliegue

#### 1. Preparar el Proyecto

```bash
# Instalar dependencias de producción
composer install --optimize-autoloader --no-dev

# Generar clave de aplicación
php artisan key:generate

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 2. Configurar Railway

1. **Crear nuevo proyecto en Railway**
   - Ir a [railway.app](https://railway.app)
   - Hacer clic en "New Project"
   - Seleccionar "Deploy from GitHub repo"
   - Conectar tu repositorio

2. **Agregar base de datos PostgreSQL**
   - En el dashboard del proyecto, hacer clic en "+ New"
   - Seleccionar "Database" → "PostgreSQL"
   - Railway creará automáticamente las variables de entorno

#### 3. Configurar Variables de Entorno

En el dashboard de Railway, ir a la pestaña "Variables" y agregar:

```env
APP_NAME="Adopción de Animales"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_GENERADA_AQUI
APP_DEBUG=false
APP_URL=https://tu-app.up.railway.app

APP_LOCALE=es
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stack
LOG_LEVEL=error

# Las variables de DB se configuran automáticamente por Railway:
# DB_CONNECTION=pgsql
# DB_HOST=${{PGHOST}}
# DB_PORT=${{PGPORT}}
# DB_DATABASE=${{PGDATABASE}}
# DB_USERNAME=${{PGUSER}}
# DB_PASSWORD=${{PGPASSWORD}}

SESSION_DRIVER=database
SESSION_LIFETIME=120

FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

# Configuración de correo (opcional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Adopción de Animales"
```

#### 4. Configurar Build y Deploy

Railway detectará automáticamente que es una aplicación PHP/Laravel y usará el `Procfile`.

Si necesitas comandos personalizados, puedes agregar en la configuración:

```bash
# Build Command (opcional)
composer install --optimize-autoloader --no-dev && php artisan config:cache && php artisan route:cache && php artisan view:cache

# Start Command (ya configurado en Procfile)
vendor/bin/heroku-php-apache2 public/
```

#### 5. Ejecutar Migraciones

Una vez desplegada la aplicación:

1. Ir a la pestaña "Deployments" en Railway
2. Hacer clic en el deployment más reciente
3. Abrir la terminal integrada
4. Ejecutar:

```bash
# Solo migrar (seguro para producción)
php artisan migrate --force

# El AdminSeeder es condicional y solo crea datos si no existen usuarios
# NO usar db:seed automático en producción
php artisan storage:link
```

### Configuración Post-Despliegue

#### 1. Verificar Funcionamiento

- Acceder a la URL proporcionada por Railway
- Probar el login de administrador: `admin@admin.com` / `admin123`
- Verificar que las imágenes se muestren correctamente
- Probar funcionalidades principales

#### 2. Configurar Dominio Personalizado (Opcional)

1. En Railway, ir a "Settings" → "Domains"
2. Agregar tu dominio personalizado
3. Configurar los registros DNS según las instrucciones

### Solución de Problemas Comunes

#### Error 500 - Internal Server Error

```bash
# Verificar logs
php artisan log:show

# Limpiar caché
php artisan config:clear
php artisan cache:clear
```

#### Problemas de Base de Datos

```bash
# Verificar conexión
php artisan tinker
# En tinker: DB::connection()->getPdo();

# Re-ejecutar migraciones
php artisan migrate:fresh --force
php artisan db:seed --class=AdminSeeder --force
```

#### Imágenes no se Muestran

```bash
# Recrear enlace simbólico
php artisan storage:link

# Verificar permisos
chmod -R 755 storage/
chmod -R 755 public/storage/
```

### Comandos Útiles para Producción

```bash
# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize

# Limpiar caché (si hay problemas)
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Verificar estado de la aplicación
php artisan about
```

### Monitoreo y Mantenimiento

- **Logs**: Accesibles desde el dashboard de Railway
- **Métricas**: Railway proporciona métricas básicas de CPU y memoria
- **Backups**: Configurar backups regulares de la base de datos
- **Updates**: Mantener Laravel y dependencias actualizadas

### Recursos Adicionales

- [Documentación de Railway](https://docs.railway.app/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Optimización de Laravel](https://laravel.com/docs/deployment#optimization)

---

**Nota**: Railway ofrece $5 USD de crédito gratuito mensual, suficiente para aplicaciones pequeñas a medianas. Para aplicaciones con mayor tráfico, considera los planes de pago.
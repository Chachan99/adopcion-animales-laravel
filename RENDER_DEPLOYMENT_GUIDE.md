# Guía de Despliegue en Render.com

## Pasos para Desplegar tu Aplicación de Adopción de Animales

### 1. Preparar el Repositorio en GitHub

1. **Crear un repositorio en GitHub:**
   - Ve a [GitHub](https://github.com) y crea un nuevo repositorio
   - Nombre sugerido: `adopcion-animales-laravel`
   - Hazlo público (necesario para el plan gratuito de Render)

2. **Subir el código:**
   ```bash
   git remote add origin https://github.com/TU_USUARIO/adopcion-animales-laravel.git
   git branch -M main
   git push -u origin main
   ```

### 2. Configurar la Base de Datos en Render

1. **Crear cuenta en Render:**
   - Ve a [render.com](https://render.com)
   - Regístrate con tu cuenta de GitHub

2. **Crear base de datos PostgreSQL:**
   - En el dashboard, haz clic en "New +"
   - Selecciona "PostgreSQL"
   - Configuración:
     - **Name:** `adopcion-animales-db`
     - **Database:** `adopcion_animales`
     - **User:** `adopcion_user`
     - **Region:** Oregon (US West)
     - **PostgreSQL Version:** 15
     - **Plan:** Free
   - Haz clic en "Create Database"

3. **Obtener credenciales de la base de datos:**
   - Una vez creada, ve a la pestaña "Info"
   - Copia los siguientes valores:
     - **Internal Database URL** (para usar en la aplicación)
     - **External Database URL** (para conexiones externas)

### 3. Crear el Web Service en Render

1. **Crear nuevo Web Service:**
   - En el dashboard, haz clic en "New +"
   - Selecciona "Web Service"
   - Conecta tu repositorio de GitHub

#### Tipos de Environment en Render:

**Opción A: Docker (Recomendado para este proyecto)**
- Usa el Dockerfile que ya está configurado
- No requiere configurar Build Command ni Start Command
- Más control sobre el entorno de ejecución
- Ideal para aplicaciones Laravel complejas

**Opción B: Node/Static Site**
- Requiere configurar manualmente Build Command y Start Command
- Menos control sobre dependencias del sistema
- No recomendado para Laravel con PHP

2. **Configuración del servicio:**
   - **Name:** `adopcion-animales-app`
   - **Region:** Oregon (US West)
   - **Branch:** `main`
   - **Root Directory:** (dejar vacío)
   - **Environment:** `Docker`
   
   **IMPORTANTE:** En Render, cuando seleccionas "Docker" como Environment, los comandos Build y Start se toman automáticamente del Dockerfile. No necesitas configurar Build Command ni Start Command manualmente, ya que:
   - El **Build Command** se ejecuta automáticamente durante la construcción de la imagen Docker
   - El **Start Command** se toma del `CMD` definido en el Dockerfile
   
   Si no ves las opciones "Build Command" y "Start Command", es porque has seleccionado correctamente "Docker" como Environment.

### 4. Configurar Variables de Entorno

En la sección "Environment" del Web Service, agrega estas variables:

```
APP_NAME="Adopción de Animales"
APP_ENV=production
APP_KEY=base64:GENERA_UNA_NUEVA_CLAVE
APP_DEBUG=false
APP_TIMEZONE=America/Mexico_City
APP_URL=https://tu-app-name.onrender.com

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=[HOST_DE_TU_DB_POSTGRESQL]
DB_PORT=5432
DB_DATABASE=adopcion_animales
DB_USERNAME=adopcion_user
DB_PASSWORD=[PASSWORD_DE_TU_DB]

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

# Render specific
PORT=8080
RENDER=true
```

### 5. Generar APP_KEY

Para generar una nueva `APP_KEY`:

1. **Localmente (si tienes PHP):**
   ```bash
   php artisan key:generate --show
   ```

2. **Online:**
   - Ve a [generate-random.org/laravel-key-generator](https://generate-random.org/laravel-key-generator)
   - Copia la clave generada (debe empezar con `base64:`)

### 6. Configurar la URL de la Base de Datos

1. **Obtener la URL interna de PostgreSQL:**
   - Ve a tu base de datos en Render
   - Copia la "Internal Database URL"
   - Ejemplo: `postgresql://adopcion_user:password@dpg-xxxxx-a/adopcion_animales`

2. **Extraer los componentes:**
   - **DB_HOST:** `dpg-xxxxx-a` (parte después de @ y antes de /)
   - **DB_DATABASE:** `adopcion_animales` (parte después de /)
   - **DB_USERNAME:** `adopcion_user` (parte después de // y antes de :)
   - **DB_PASSWORD:** `password` (parte después de : y antes de @)

3. **DÓNDE COLOCAR ESTOS VALORES:**

   **Ubicación en Render:**
   ```
   Dashboard → Tu Web Service → Environment (menú lateral)
   ```

   **Pasos detallados:**
   - Ve a tu **Web Service** en Render (NO a la base de datos)
   - En el menú lateral izquierdo, haz clic en **"Environment"**
   - Verás una lista de variables de entorno
   - Busca y edita las variables que empiezan con `DB_`:
     - `DB_HOST` → Reemplaza con tu host (ej: `dpg-xxxxx-a`)
     - `DB_DATABASE` → Reemplaza con tu database (ej: `adopcion_animales`)
     - `DB_USERNAME` → Reemplaza con tu username (ej: `adopcion_user`)
     - `DB_PASSWORD` → Reemplaza con tu password
   - Haz clic en **"Save Changes"**
   - Render redesplegará automáticamente tu aplicación

   **💡 Tip:** Si no ves las variables `DB_*`, significa que aún no has agregado las variables de entorno del paso 4.

### 7. Desplegar la Aplicación

1. **Iniciar el despliegue:**
   - Haz clic en "Create Web Service"
   - Render comenzará a construir tu aplicación
   - El proceso puede tomar 5-10 minutos

2. **Monitorear el despliegue:**
   - Ve a la pestaña "Logs" para ver el progreso
   - Busca errores en caso de que falle

### 8. Verificar el Despliegue

1. **Acceder a la aplicación:**
   - Una vez completado, tu app estará disponible en:
   - `https://tu-app-name.onrender.com`

2. **Verificar funcionalidades:**
   - Registro de usuarios
   - Login
   - Subida de imágenes
   - Funciones de administrador

### 9. Configuraciones Post-Despliegue

1. **Crear usuario administrador:**
   - Accede a tu aplicación
   - Regístrate como usuario normal
   - Conecta a tu base de datos y actualiza el rol:
   ```sql
   UPDATE usuarios SET tipo_usuario = 'admin' WHERE email = 'tu-email@ejemplo.com';
   ```

2. **Configurar dominio personalizado (opcional):**
   - En Render, ve a "Settings" > "Custom Domains"
   - Agrega tu dominio personalizado

### 10. Mantenimiento y Actualizaciones

1. **Actualizaciones automáticas:**
   - Render redesplegará automáticamente cuando hagas push a la rama `main`

2. **Ver logs:**
   - Ve a "Logs" en tu Web Service para monitorear la aplicación

3. **Reiniciar la aplicación:**
   - En "Settings", usa "Manual Deploy" para reiniciar

## 🔧 Solución de Problemas

### ❌ Error 500 - Internal Server Error
**Síntomas**: La aplicación muestra "Oops! An Error Occurred" con error 500.

**Causas más comunes**:
1. **APP_KEY no configurada**: Laravel requiere una clave de aplicación
2. **Variables de entorno faltantes**: DB_HOST, DB_PASSWORD, etc.
3. **Base de datos no conectada**: Credenciales incorrectas
4. **Migraciones no ejecutadas**: Tablas no creadas

**Solución paso a paso**:
1. **Configurar APP_KEY**:
   - Genera una nueva: `php artisan key:generate --show`
   - Copia el resultado (ej: `base64:ABC123...`)
   - Agrégala en Environment de Render

2. **Verificar variables de entorno**:
   - Ve a tu Web Service → Environment
   - Asegúrate de tener TODAS las variables del archivo `render-env-variables.txt`
   - Especialmente: `APP_KEY`, `DB_HOST`, `DB_PASSWORD`, `APP_ENV=production`

3. **Verificar conexión de base de datos**:
   - Confirma que el password de `DB_PASSWORD` sea correcto
   - Debe coincidir con el de tu Internal Database URL

### Problemas durante la Configuración

**No aparecen Build Command y Start Command:**
- ✅ Esto es NORMAL cuando seleccionas "Docker" como Environment
- Los comandos se toman automáticamente del Dockerfile
- Si necesitas comandos personalizados, están definidos en el Dockerfile y deploy.sh

**Error al conectar repositorio:**
- Verifica que el repositorio sea público (requerido para plan gratuito)
- Asegúrate de tener permisos de acceso al repositorio
- Reconecta tu cuenta de GitHub en Render si es necesario

### Error de Conexión a Base de Datos
- Verifica que las credenciales de DB sean correctas
- Asegúrate de usar la URL interna de la base de datos
- Verifica que la base de datos esté en la misma región

### Error 500 - Internal Server Error
- Revisa los logs en Render
- Verifica que `APP_KEY` esté configurada
- Asegúrate de que `APP_DEBUG=false` en producción

### Imágenes no se muestran
- Verifica que el directorio `storage/app/public` tenga permisos correctos
- El script `deploy.sh` debería crear el symlink automáticamente

### Aplicación lenta
- El plan gratuito de Render tiene limitaciones de recursos
- Considera optimizar consultas de base de datos
- Usa caché cuando sea posible

## Recursos Adicionales

- [Documentación de Render](https://render.com/docs)
- [Documentación de Laravel](https://laravel.com/docs)
- [Guía de PostgreSQL en Render](https://render.com/docs/databases)

---

¡Tu aplicación de adopción de animales ya está lista para ser desplegada en Render.com! 🐕🐱
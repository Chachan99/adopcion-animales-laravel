# Configuración de Base de Datos PostgreSQL en Render

## Problema Identificado
La aplicación no puede conectarse a PostgreSQL porque:
1. `DATABASE_URL` no está configurada
2. No hay un servicio PostgreSQL vinculado
3. Las variables individuales de DB están vacías

## Solución: Configurar PostgreSQL en Render

### Paso 1: Crear Servicio PostgreSQL
1. Ve al dashboard de Render: https://dashboard.render.com
2. Haz clic en "New +" → "PostgreSQL"
3. Configura:
   - **Name**: `adopcion-animales-db`
   - **Database**: `adopcion_animales`
   - **User**: `adopcion_user`
   - **Region**: Misma región que tu web service
   - **Plan**: Free (para desarrollo)

### Paso 2: Obtener DATABASE_URL
Después de crear la base de datos:
1. Ve a tu servicio PostgreSQL en Render
2. En la pestaña "Info", encontrarás:
   - **Internal Database URL** (para usar dentro de Render)
   - **External Database URL** (para conexiones externas)
3. Copia la **Internal Database URL**

### Paso 3: Configurar Variables de Entorno
En tu Web Service de Render, ve a "Environment" y agrega:

```
DATABASE_URL=postgresql://usuario:password@host:5432/database
DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxx-a.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=adopcion_animales
DB_USERNAME=adopcion_user
DB_PASSWORD=tu_password_generado
```

### Paso 4: Variables Adicionales Requeridas
```
APP_KEY=base64:tu_app_key_generada
APP_ENV=production
APP_DEBUG=false
APP_URL=https://adopcion-animales-app.onrender.com
```

## Verificación
Después de configurar:
1. Redeploy tu aplicación
2. Los logs deberían mostrar:
   ```
   ✅ DATABASE_URL configurada
   ✅ Conexión a PostgreSQL exitosa
   ```

## Troubleshooting
- Si ves "Connection refused": Verifica que DATABASE_URL apunte al Internal URL
- Si ves "Authentication failed": Verifica usuario y password
- Si ves "Database does not exist": Verifica el nombre de la base de datos

## Comandos de Migración
Una vez conectado, ejecutar:
```bash
php artisan migrate:fresh --seed
```
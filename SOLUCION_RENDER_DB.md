# 🔧 Solución: Datos No Se Guardan en Render

## Problema Identificado
Tu aplicación en Render no está guardando datos en la base de datos. Esto generalmente se debe a:

1. **Variables de entorno mal configuradas**
2. **Base de datos PostgreSQL no vinculada correctamente**
3. **Migraciones no ejecutadas**
4. **Problemas de conexión o permisos**

## 🚀 Solución Paso a Paso

### Paso 1: Ejecutar Diagnóstico

1. **Sube el archivo de diagnóstico a tu repositorio:**
   ```bash
   git add render-diagnostics.php
   git commit -m "Add database diagnostics script"
   git push
   ```

2. **Ejecuta el diagnóstico en Render:**
   - Ve a tu Web Service en Render
   - En la pestaña "Shell", ejecuta:
   ```bash
   php render-diagnostics.php
   ```
   - **Guarda la salida completa** para identificar errores específicos

### Paso 2: Verificar Servicio PostgreSQL

1. **Confirma que tienes un servicio PostgreSQL:**
   - Ve a tu dashboard de Render
   - Deberías ver un servicio PostgreSQL separado
   - Si NO lo tienes, créalo:
     - Clic en "New +" → "PostgreSQL"
     - Name: `adopcion-animales-db`
     - Database: `adopcion_animales`
     - Plan: Free

2. **Obtener DATABASE_URL:**
   - Ve a tu servicio PostgreSQL
   - Pestaña "Info"
   - Copia la **"Internal Database URL"**
   - Ejemplo: `postgresql://usuario:password@dpg-xxxxx-a.oregon-postgres.render.com/adopcion_animales`

### Paso 3: Configurar Variables de Entorno

1. **Ve a tu Web Service en Render**
2. **Pestaña "Environment"**
3. **Agrega/Verifica estas variables:**

```bash
# === CRÍTICAS ===
DATABASE_URL=postgresql://usuario:password@host/database
APP_KEY=base64:TU_CLAVE_GENERADA_AQUI

# === BÁSICAS ===
APP_NAME="Adopción de Animales"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app-name.onrender.com
APP_TIMEZONE=America/Mexico_City

# === LOCALIZACIÓN ===
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

# === BASE DE DATOS ===
DB_CONNECTION=pgsql
# Nota: Las siguientes se configuran automáticamente desde DATABASE_URL
# DB_HOST=se-configura-automaticamente
# DB_PORT=se-configura-automaticamente
# DB_DATABASE=se-configura-automaticamente
# DB_USERNAME=se-configura-automaticamente
# DB_PASSWORD=se-configura-automaticamente

# === SESIONES Y CACHE ===
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# === LOGS ===
LOG_CHANNEL=stack
LOG_LEVEL=error

# === RENDER ESPECÍFICO ===
RENDER=true
PORT=8080
```

### Paso 4: Generar APP_KEY

**Opción A: Localmente**
```bash
php artisan key:generate --show
```

**Opción B: Online**
- Ve a: https://generate-random.org/laravel-key-generator
- Copia la clave que empiece con `base64:`

### Paso 5: Forzar Redespliegue

1. **En tu Web Service de Render:**
   - Pestaña "Settings"
   - Scroll hasta "Danger Zone"
   - Clic en "Manual Deploy"
   - Selecciona "Clear build cache & deploy"

2. **Monitorear el despliegue:**
   - Ve a la pestaña "Logs"
   - Busca errores relacionados con:
     - Conexión a base de datos
     - Migraciones
     - Variables de entorno

### Paso 6: Verificar Después del Despliegue

1. **Ejecutar diagnóstico nuevamente:**
   ```bash
   php render-diagnostics.php
   ```

2. **Verificar que las migraciones se ejecutaron:**
   ```bash
   php artisan migrate:status
   ```

3. **Probar inserción de datos:**
   ```bash
   php artisan tinker
   ```
   ```php
   // En tinker:
   App\Models\Usuario::count()
   App\Models\Animal::count()
   ```

## 🔍 Problemas Comunes y Soluciones

### Error: "Driver [pgsql] not supported"
**Solución:**
- Verifica que `"ext-pdo_pgsql": "*"` esté en composer.json
- Fuerza rebuild con cache limpio

### Error: "SQLSTATE[08006] Connection refused"
**Solución:**
- DATABASE_URL incorrecta o no configurada
- Servicio PostgreSQL no creado o inactivo
- Usar "Internal Database URL" no "External"

### Error: "Base table or view not found"
**Solución:**
- Migraciones no ejecutadas
- Verificar logs de despliegue
- Ejecutar manualmente: `php artisan migrate --force`

### Error: "No application encryption key"
**Solución:**
- APP_KEY no configurada
- Generar nueva clave y agregarla a variables de entorno

## 📋 Checklist de Verificación

- [ ] Servicio PostgreSQL creado en Render
- [ ] DATABASE_URL copiada correctamente
- [ ] APP_KEY generada y configurada
- [ ] Variables de entorno configuradas
- [ ] Redespliegue con cache limpio ejecutado
- [ ] Logs de despliegue revisados
- [ ] Diagnóstico ejecutado sin errores
- [ ] Datos de prueba insertados correctamente

## 🆘 Si Aún No Funciona

1. **Comparte la salida completa de:**
   ```bash
   php render-diagnostics.php
   ```

2. **Comparte los logs de despliegue de Render**

3. **Verifica en el Shell de Render:**
   ```bash
   echo $DATABASE_URL
   php -m | grep pdo
   php artisan migrate:status
   ```

## 📞 Contacto de Emergencia

Si necesitas ayuda inmediata:
1. Ejecuta el diagnóstico
2. Toma screenshots de los errores
3. Comparte la información específica del problema

---

**💡 Tip:** Render puede tardar 2-3 minutos en aplicar cambios de variables de entorno. Ten paciencia después de cada cambio.
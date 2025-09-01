# 🚀 Guía de Despliegue - Alternativas Gratuitas a Railway

Esta guía te muestra las mejores alternativas gratuitas para desplegar tu aplicación Laravel de adopción de animales.

## 📋 Índice
1. [Render.com (Recomendado)](#rendercom-recomendado)
2. [Vercel con Serverless](#vercel-con-serverless)
3. [Otras Alternativas](#otras-alternativas)
4. [Comparación de Plataformas](#comparación-de-plataformas)

---

## 🎯 Render.com (Recomendado)

**✅ Mejor opción para Laravel** - Soporte nativo para PHP, PostgreSQL gratuito, y fácil configuración.

### Características del Plan Gratuito:
- ✅ 750 horas de cómputo por mes
- ✅ PostgreSQL gratuito (90 días, luego $7/mes)
- ✅ SSL automático
- ✅ Despliegue automático desde Git
- ✅ Soporte completo para Laravel

### Pasos para Desplegar en Render:

#### 1. Preparar el Proyecto

**Crear Dockerfile:**
```dockerfile
# Usar imagen oficial de PHP con Nginx
FROM richarvey/nginx-php-fpm:3.1.6

# Copiar código de la aplicación
COPY . /var/www/html/

# Configurar permisos
RUN chown -R nginx:nginx /var/www/html

# Exponer puerto 8080 (requerido por Render)
EXPOSE 8080
```

**Crear .dockerignore:**
```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
```

**Crear script de despliegue (deploy.sh):**
```bash
#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --optimize-autoloader

echo "Generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Optimizing application..."
php artisan optimize
```

#### 2. Configurar en Render

1. **Crear cuenta en [render.com](https://render.com)**
2. **Crear PostgreSQL Database:**
   - Ir a "New" → "PostgreSQL"
   - Nombre: `adopcion-animales-db`
   - Copiar la URL interna de la base de datos

3. **Crear Web Service:**
   - Ir a "New" → "Web Service"
   - Conectar tu repositorio de GitHub
   - Configuración:
     - **Environment:** Docker
     - **Build Command:** `./deploy.sh`
     - **Start Command:** `nginx -g "daemon off;"`

4. **Variables de Entorno:**
```env
APP_NAME="Adopción de Animales"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_GENERADA
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com

DB_CONNECTION=pgsql
DB_HOST=tu-host-postgresql
DB_PORT=5432
DB_DATABASE=tu-database
DB_USERNAME=tu-username
DB_PASSWORD=tu-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
```

---

## ⚡ Vercel con Serverless

**Ideal para:** Aplicaciones con mucho tráfico de lectura y pocas escrituras.

### Características del Plan Gratuito:
- ✅ 100GB de ancho de banda
- ✅ Funciones serverless ilimitadas
- ✅ SSL automático
- ✅ CDN global
- ❌ No incluye base de datos (necesitas externa)

### Pasos para Desplegar en Vercel:

#### 1. Preparar el Proyecto

**Crear vercel.json:**
```json
{
  "version": 2,
  "regions": ["iad1"],
  "functions": {
    "api/index.php": {
      "runtime": "vercel-php@0.7.1"
    }
  },
  "routes": [
    {
      "src": "/build/(.*)",
      "dest": "/build/$1"
    },
    {
      "src": "/(.*)",
      "dest": "/api/index.php"
    }
  ],
  "outputDirectory": "public"
}
```

**Crear api/index.php:**
```php
<?php

// Cargar el autoloader de Composer
require __DIR__ . '/../vendor/autoload.php';

// Cargar la aplicación Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Manejar la solicitud
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
```

**Modificar bootstrap/app.php:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
```

#### 2. Base de Datos Externa

Usa **Neon.tech** (PostgreSQL gratuito):
1. Crear cuenta en [neon.tech](https://neon.tech)
2. Crear base de datos
3. Copiar string de conexión

#### 3. Desplegar
1. Instalar Vercel CLI: `npm i -g vercel`
2. Ejecutar: `vercel --prod`
3. Configurar variables de entorno en el dashboard

---

## 🔄 Otras Alternativas

### 1. **Fly.io**
- ✅ 3 aplicaciones gratuitas
- ✅ 256MB RAM por app
- ✅ Soporte completo para Laravel
- 📖 [Guía oficial](https://fly.io/docs/laravel/)

### 2. **Koyeb**
- ✅ $5.50 de créditos mensuales
- ✅ Despliegue desde Git
- ✅ SSL automático

### 3. **Back4App**
- ✅ 25,000 requests/mes gratuitos
- ✅ Base de datos incluida
- ✅ Fácil configuración

---

## 📊 Comparación de Plataformas

| Plataforma | Costo | Base de Datos | Facilidad | Laravel Support |
|------------|-------|---------------|-----------|----------------|
| **Render** | 🟢 Gratuito | ✅ PostgreSQL | 🟢 Fácil | ✅ Excelente |
| **Vercel** | 🟢 Gratuito | ❌ Externa | 🟡 Medio | 🟡 Serverless |
| **Fly.io** | 🟢 Gratuito | 🟡 Limitada | 🟢 Fácil | ✅ Excelente |
| **Koyeb** | 🟡 Créditos | ❌ Externa | 🟢 Fácil | ✅ Bueno |

---

## 🎯 Recomendación Final

**Para tu aplicación de adopción de animales, recomiendo Render.com porque:**

1. ✅ **Soporte nativo para Laravel** - No necesitas configuraciones complejas
2. ✅ **PostgreSQL incluido** - Base de datos gratuita por 90 días
3. ✅ **Fácil configuración** - Solo necesitas Docker y variables de entorno
4. ✅ **SSL automático** - Seguridad incluida
5. ✅ **Despliegue automático** - Se actualiza automáticamente desde Git

---

## 🚀 Próximos Pasos

1. **Elige tu plataforma** (recomiendo Render.com)
2. **Prepara los archivos** según la guía de tu plataforma elegida
3. **Configura la base de datos** externa si es necesario
4. **Despliega tu aplicación**
5. **Configura el dominio** personalizado (opcional)

¿Necesitas ayuda con alguna plataforma específica? ¡Avísame y te ayudo con la configuración detallada!
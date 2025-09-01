#!/usr/bin/env bash
set -e

echo "🚀 Iniciando despliegue de Laravel en Render..."

# Instalar dependencias de Composer
echo "📦 Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generar clave de aplicación si no existe
echo "🔑 Generando clave de aplicación..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --show --no-interaction
else
    echo "Clave de aplicación ya configurada"
fi

# Limpiar cachés existentes
echo "🧹 Limpiando cachés..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Crear enlace simbólico para storage
echo "🔗 Creando enlace simbólico para storage..."
php artisan storage:link || true

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones de base de datos..."
php artisan migrate --force --no-interaction

# Cachear configuraciones para producción
echo "⚡ Optimizando aplicación para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimizar autoloader
echo "🔧 Optimizando autoloader..."
composer dump-autoload --optimize --no-dev

# Configurar permisos
echo "🔒 Configurando permisos..."
chmod -R 755 storage bootstrap/cache

echo "✅ Despliegue completado exitosamente!"
echo "🌐 La aplicación está lista para servir tráfico"
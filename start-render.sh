#!/bin/bash

echo "=== INICIANDO SERVICIOS EN RENDER ==="

# Verificar si PHP-FPM está configurado
echo "Verificando configuración de PHP-FPM..."

# Buscar configuración de PHP-FPM
PHP_FPM_CONF_DIR=$(find /etc -name "php-fpm.d" 2>/dev/null | head -1)
PHP_FPM_WWW_CONF=$(find /etc -name "www.conf" 2>/dev/null | head -1)

if [ -n "$PHP_FPM_WWW_CONF" ]; then
    echo "✅ Configuración de PHP-FPM encontrada en: $PHP_FPM_WWW_CONF"
else
    echo "❌ Configuración de PHP-FPM no encontrada"
    # Intentar crear configuración básica
    if [ -n "$PHP_FPM_CONF_DIR" ]; then
        cat > "$PHP_FPM_CONF_DIR/www.conf" << 'EOF'
[www]
user = nginx
group = nginx
listen = /var/run/php-fpm.sock
listen.owner = nginx
listen.group = nginx
listen.mode = 0660
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
EOF
        echo "✅ Configuración básica creada"
    fi
fi

# Crear directorio para socket si no existe
mkdir -p /var/run
chown www-data:www-data /var/run

# Iniciar PHP-FPM manualmente
echo "Iniciando PHP-FPM..."
php-fpm -D

# Verificar que el socket se creó
if [ -S "/var/run/php-fpm.sock" ]; then
    echo "✅ Socket PHP-FPM creado correctamente"
    ls -la /var/run/php-fpm.sock
else
    echo "❌ Error: Socket PHP-FPM no se creó"
    echo "Intentando iniciar PHP-FPM en modo TCP..."
    # Fallback a TCP
    # Buscar archivo de configuración de PHP-FPM
    PHP_FPM_CONF=$(find /etc -name "www.conf" 2>/dev/null | head -1)
    if [ -n "$PHP_FPM_CONF" ]; then
        sed -i 's|listen = /var/run/php-fpm.sock|listen = 127.0.0.1:9000|g' "$PHP_FPM_CONF"
    fi
    pkill php-fpm 2>/dev/null || true
    php-fpm -D
    
    # Actualizar nginx para usar TCP
    sed -i 's|fastcgi_pass unix:/var/run/php-fpm.sock|fastcgi_pass 127.0.0.1:9000|g' /etc/nginx/nginx.conf
fi

# Ejecutar configuración de base de datos
echo "Configurando directorios de caché..."
# Asegurar que los directorios de caché existan y tengan permisos correctos
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage/framework/cache
chmod -R 777 /var/www/html/storage/framework/sessions
chmod -R 777 /var/www/html/storage/framework/views
chmod -R 777 /var/www/html/storage/logs
echo "✅ Directorios de caché configurados"

# Limpiar y optimizar caché de Laravel
echo "Optimizando caché de Laravel..."
cd /var/www/html
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true
echo "✅ Caché de Laravel optimizada"

echo "Configurando base de datos..."
php /var/www/html/render-setup.php

# Iniciar Nginx
echo "Iniciando Nginx..."
nginx -g "daemon off;"
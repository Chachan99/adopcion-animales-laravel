#!/bin/bash

echo "=== INICIANDO SERVICIOS EN RENDER ==="

# Verificar si PHP-FPM está configurado
echo "Verificando configuración de PHP-FPM..."

# Buscar configuración de PHP-FPM
PHP_FPM_CONF_DIR=$(find /etc -name "php-fpm.d" 2>/dev/null | head -1)
PHP_FPM_WWW_CONF=$(find /etc -name "www.conf" 2>/dev/null | head -1)

# Forzar configuración TCP en puerto 9001 para evitar conflictos
if [ -n "$PHP_FPM_WWW_CONF" ]; then
    echo "✅ Configuración de PHP-FPM encontrada en: $PHP_FPM_WWW_CONF"
    # Cambiar a puerto 9001 inmediatamente
    sed -i 's|listen = .*|listen = 127.0.0.1:9001|g' "$PHP_FPM_WWW_CONF"
    echo "✅ PHP-FPM configurado para usar puerto 9001"
else
    echo "❌ Configuración de PHP-FPM no encontrada"
    # Intentar crear configuración básica con puerto TCP
    if [ -n "$PHP_FPM_CONF_DIR" ]; then
        cat > "$PHP_FPM_CONF_DIR/www.conf" << 'EOF'
[www]
user = nginx
group = nginx
listen = 127.0.0.1:9001
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
EOF
        echo "✅ Configuración básica creada con puerto 9001"
    fi
fi

# Buscar y actualizar TODAS las configuraciones de PHP-FPM
find /etc -name "*.conf" -path "*/php*" -exec sed -i 's|listen = 127.0.0.1:9000|listen = 127.0.0.1:9001|g' {} \;
find /etc -name "*.conf" -path "*/php*" -exec sed -i 's|listen = 9000|listen = 127.0.0.1:9001|g' {} \;

# Asegurar que Nginx esté configurado para TCP
echo "Configurando Nginx para usar TCP..."
sed -i 's|fastcgi_pass unix:/var/run/php-fpm.sock|fastcgi_pass 127.0.0.1:9001|g' /etc/nginx/nginx.conf
sed -i 's|fastcgi_pass 127.0.0.1:9000|fastcgi_pass 127.0.0.1:9001|g' /etc/nginx/nginx.conf

# Matar cualquier proceso PHP-FPM existente
echo "Deteniendo procesos PHP-FPM existentes..."
pkill php-fpm 2>/dev/null || true
sleep 2

# Iniciar PHP-FPM en modo TCP
echo "Iniciando PHP-FPM en puerto 9001..."
php-fpm -D

# Verificar que PHP-FPM esté corriendo
sleep 3
if pgrep php-fpm > /dev/null; then
    echo "✅ PHP-FPM iniciado correctamente"
    netstat -tlnp | grep :9001 || echo "⚠️ Puerto 9001 no visible en netstat"
else
    echo "❌ Error: PHP-FPM no se pudo iniciar"
    # Mostrar logs para debug
    tail -20 /var/log/php*fpm* 2>/dev/null || echo "No se encontraron logs de PHP-FPM"
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
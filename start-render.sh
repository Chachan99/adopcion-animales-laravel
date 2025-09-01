#!/bin/bash

echo "=== INICIANDO SERVICIOS EN RENDER ==="

# Verificar si PHP-FPM está configurado
echo "Verificando configuración de PHP-FPM..."
if [ ! -f "/etc/php82/php-fpm.d/www.conf" ]; then
    echo "❌ Configuración de PHP-FPM no encontrada"
    # Crear configuración básica
    mkdir -p /etc/php82/php-fpm.d/
    cat > /etc/php82/php-fpm.d/www.conf << 'EOF'
[www]
user = www-data
group = www-data
listen = /var/run/php-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
EOF
else
    echo "✅ Configuración de PHP-FPM encontrada"
fi

# Crear directorio para socket si no existe
mkdir -p /var/run
chown www-data:www-data /var/run

# Iniciar PHP-FPM manualmente
echo "Iniciando PHP-FPM..."
php-fpm82 -D

# Verificar que el socket se creó
if [ -S "/var/run/php-fpm.sock" ]; then
    echo "✅ Socket PHP-FPM creado correctamente"
    ls -la /var/run/php-fpm.sock
else
    echo "❌ Error: Socket PHP-FPM no se creó"
    echo "Intentando iniciar PHP-FPM en modo TCP..."
    # Fallback a TCP
    sed -i 's|listen = /var/run/php-fpm.sock|listen = 127.0.0.1:9000|g' /etc/php82/php-fpm.d/www.conf
    pkill php-fpm82
    php-fpm82 -D
    
    # Actualizar nginx para usar TCP
    sed -i 's|fastcgi_pass unix:/var/run/php-fpm.sock|fastcgi_pass 127.0.0.1:9000|g' /etc/nginx/nginx.conf
fi

# Ejecutar configuración de base de datos
echo "Configurando base de datos..."
php /var/www/html/render-setup.php

# Iniciar Nginx
echo "Iniciando Nginx..."
nginx -g "daemon off;"
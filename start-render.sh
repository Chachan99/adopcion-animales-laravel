#!/bin/bash

echo "=== INICIANDO SERVICIOS EN RENDER ==="

# Verificar y configurar driver PostgreSQL
echo "Verificando driver PostgreSQL..."
echo "📋 Módulos PHP disponibles:"
php -m | grep -i pdo || echo "❌ No se encontraron módulos PDO"
php -m | grep -i pgsql || echo "❌ No se encontraron módulos PostgreSQL"

if php -m | grep -q pdo_pgsql; then
    echo "✅ Driver PostgreSQL (pdo_pgsql) disponible"
else
    echo "❌ Driver PostgreSQL no encontrado"
    echo "🔧 Intentando configurar extensiones..."
    
    # Crear directorio de configuración si no existe
    mkdir -p /usr/local/etc/php/conf.d/
    
    # Agregar extensiones PostgreSQL
    echo "extension=pdo" > /usr/local/etc/php/conf.d/pgsql.ini
    echo "extension=pgsql" >> /usr/local/etc/php/conf.d/pgsql.ini
    echo "extension=pdo_pgsql" >> /usr/local/etc/php/conf.d/pgsql.ini
    
    echo "✅ Configuración de extensiones PostgreSQL agregada"
     
     # Reiniciar PHP-FPM para cargar las nuevas extensiones
     echo "🔄 Reiniciando PHP-FPM para cargar extensiones..."
     pkill php-fpm 2>/dev/null || true
     sleep 2
     
     # Verificar nuevamente
     echo "🔄 Verificando extensiones después de configuración..."
     php -m | grep -i pdo || echo "⚠️ PDO aún no disponible"
     php -m | grep -i pgsql || echo "⚠️ PostgreSQL aún no disponible"
fi

# Verificación final del driver antes de continuar
echo "🔍 Verificación final del driver PostgreSQL..."
if php -r "try { new PDO('pgsql:host=localhost'); echo 'PDO PostgreSQL OK'; } catch(Exception \$e) { echo 'Error: ' . \$e->getMessage(); }" 2>/dev/null | grep -q "PDO PostgreSQL OK\|driver"; then
    echo "✅ Driver PostgreSQL funcional"
else
    echo "❌ Driver PostgreSQL no funcional - continuando con diagnósticos"
fi

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

# Verificación crítica del driver antes de render-setup.php
echo "🔍 Verificación crítica del driver PostgreSQL antes de configuración..."
php -r "echo 'Extensiones PDO: '; print_r(PDO::getAvailableDrivers()); echo PHP_EOL;" || echo "❌ Error al verificar drivers PDO"

if php -r "echo in_array('pgsql', PDO::getAvailableDrivers()) ? 'SI' : 'NO';" 2>/dev/null | grep -q "SI"; then
    echo "✅ Driver pgsql confirmado en PDO"
else
    echo "❌ Driver pgsql NO disponible en PDO"
    echo "🔧 Intentando cargar manualmente..."
    php -r "dl('pdo_pgsql.so');" 2>/dev/null || echo "⚠️ No se pudo cargar pdo_pgsql.so"
fi

php /var/www/html/render-setup.php

# Iniciar Nginx
echo "Iniciando Nginx..."
nginx -g "daemon off;"
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

# Forzar configuración TCP en puerto 9000 (puerto estándar)
if [ -n "$PHP_FPM_WWW_CONF" ]; then
    echo "✅ Configuración de PHP-FPM encontrada en: $PHP_FPM_WWW_CONF"
    # Cambiar a puerto 9000 inmediatamente
    sed -i 's|listen = .*|listen = 127.0.0.1:9000|g' "$PHP_FPM_WWW_CONF"
    echo "✅ PHP-FPM configurado para usar puerto 9000"
else
    echo "❌ Configuración de PHP-FPM no encontrada"
    # Intentar crear configuración básica con puerto TCP
    if [ -n "$PHP_FPM_CONF_DIR" ]; then
        cat > "$PHP_FPM_CONF_DIR/www.conf" << 'EOF'
[www]
user = nginx
group = nginx
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 10
pm.start_servers = 3
pm.min_spare_servers = 2
pm.max_spare_servers = 5
EOF
        echo "✅ Configuración básica creada con puerto 9000"
    fi
fi

# Buscar y actualizar TODAS las configuraciones de PHP-FPM para usar puerto 9000
find /etc -name "*.conf" -path "*/php*" -exec sed -i 's|listen = 127.0.0.1:9001|listen = 127.0.0.1:9000|g' {} \;
find /etc -name "*.conf" -path "*/php*" -exec sed -i 's|listen = 9001|listen = 127.0.0.1:9000|g' {} \;

# Asegurar que Nginx esté configurado para TCP
echo "Configurando Nginx para usar TCP..."
sed -i 's|fastcgi_pass unix:/var/run/php-fpm.sock|fastcgi_pass 127.0.0.1:9000|g' /etc/nginx/nginx.conf
sed -i 's|fastcgi_pass 127.0.0.1:9001|fastcgi_pass 127.0.0.1:9000|g' /etc/nginx/nginx.conf

# Matar cualquier proceso PHP-FPM existente
echo "Deteniendo procesos PHP-FPM existentes..."
pkill php-fpm 2>/dev/null || true
sleep 2

# Iniciar PHP-FPM en modo TCP
echo "Iniciando PHP-FPM en puerto 9000..."
php-fpm -D

# Verificar que PHP-FPM esté corriendo
sleep 3
if pgrep php-fpm > /dev/null; then
    echo "✅ PHP-FPM iniciado correctamente"
    netstat -tlnp | grep :9000 || echo "⚠️ Puerto 9000 no visible en netstat"
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

# Compilar assets de frontend
echo "🎨 Compilando assets de frontend..."
cd /var/www/html

# Verificar si Node.js está disponible
if command -v node >/dev/null 2>&1; then
    echo "✅ Node.js encontrado: $(node --version)"
else
    echo "📦 Intentando instalar Node.js usando múltiples métodos..."
    
    # Método 1: Intentar con gestor de paquetes del sistema
    if command -v apt-get >/dev/null 2>&1; then
        echo "🔧 Método 1: Usando apt-get..."
        apt-get update -qq
        apt-get install -y nodejs npm
    elif command -v yum >/dev/null 2>&1; then
        echo "🔧 Método 1: Usando yum..."
        yum install -y nodejs npm
    elif command -v apk >/dev/null 2>&1; then
        echo "🔧 Método 1: Usando apk..."
        apk add --no-cache nodejs npm
    fi
    
    # Verificar si el método 1 funcionó
    if command -v node >/dev/null 2>&1; then
        echo "✅ Node.js instalado via gestor de paquetes: $(node --version)"
    else
        echo "🔧 Método 2: Instalación manual con NVM..."
        
        # Instalar NVM
        curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
        export NVM_DIR="$HOME/.nvm"
        [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
        
        # Instalar Node.js con NVM
        if command -v nvm >/dev/null 2>&1; then
            nvm install 18
            nvm use 18
            nvm alias default 18
        fi
        
        # Verificar instalación con NVM
        if command -v node >/dev/null 2>&1; then
            echo "✅ Node.js instalado via NVM: $(node --version)"
        else
            echo "🔧 Método 3: Descarga directa como último recurso..."
            
            # Detectar arquitectura
            ARCH=$(uname -m)
            case $ARCH in
                x86_64) NODE_ARCH="x64" ;;
                aarch64) NODE_ARCH="arm64" ;;
                *) NODE_ARCH="x64" ;;
            esac
            
            # Descargar Node.js
            NODE_VERSION="18.19.0"
            NODE_URL="https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-${NODE_ARCH}.tar.xz"
            
            echo "🔽 Descargando Node.js ${NODE_VERSION}..."
            if curl -fsSL "$NODE_URL" -o node.tar.xz && tar -xf node.tar.xz; then
                NODE_DIR="node-v${NODE_VERSION}-linux-${NODE_ARCH}"
                if [ -d "$NODE_DIR" ]; then
                    # Crear enlaces simbólicos en lugar de copiar
                    ln -sf "$(pwd)/$NODE_DIR/bin/node" /usr/local/bin/node
                    ln -sf "$(pwd)/$NODE_DIR/bin/npm" /usr/local/bin/npm
                    ln -sf "$(pwd)/$NODE_DIR/bin/npx" /usr/local/bin/npx
                    
                    # Agregar al PATH
                    export PATH="$(pwd)/$NODE_DIR/bin:$PATH"
                    echo "✅ Node.js configurado con enlaces simbólicos"
                fi
            fi
        fi
    fi
fi

# Verificar si npm está disponible
if command -v npm >/dev/null 2>&1; then
    echo "✅ npm encontrado: $(npm --version)"
else
    echo "❌ npm no disponible después de instalar Node.js"
fi

if [ -f "package.json" ]; then
    echo "📦 Instalando dependencias npm..."
    npm install --production=false
    echo "🔨 Ejecutando build..."
    npm run build
    
    # Verificar que los assets se compilaron correctamente
    if [ -f "public/build/manifest.json" ]; then
        echo "✅ Assets compilados correctamente - manifest.json encontrado"
        ls -la public/build/
    else
        echo "❌ Error: manifest.json no se generó"
        echo "📁 Contenido del directorio public/build:"
        ls -la public/build/ 2>/dev/null || echo "Directorio public/build no existe"
        
        # Como fallback, copiar los assets precompilados si existen
        echo "🔄 Intentando usar assets precompilados..."
        if [ -d "public/build" ] && [ -f "public/build/manifest.json" ]; then
            echo "✅ Assets precompilados encontrados"
        else
            echo "⚠️ Creando directorio build y copiando assets básicos"
            mkdir -p public/build/assets
            # Crear un manifest.json básico como fallback
            cat > public/build/manifest.json << 'EOF'
{
  "resources/css/app.css": {
    "file": "assets/app.css",
    "isEntry": true,
    "src": "resources/css/app.css"
  },
  "resources/js/app.js": {
    "file": "assets/app.js",
    "isEntry": true,
    "src": "resources/js/app.js"
  }
}
EOF
            echo "✅ Manifest básico creado como fallback"
        fi
    fi
else
    echo "⚠️ package.json no encontrado en /var/www/html, saltando compilación de assets"
    pwd
    ls -la
fi

# Limpiar y optimizar caché de Laravel
echo "🧹 Limpiando caché de Laravel..."
cd /var/www/html
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan config:cache || true
php artisan route:cache || true
echo "✅ Caché optimizado"

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

# Configurar Nginx
echo "Configurando Nginx..."

# Copiar configuración personalizada si existe
if [ -f "/var/www/html/docker/nginx.conf" ]; then
    echo "📋 Copiando configuración personalizada de Nginx..."
    cp /var/www/html/docker/nginx.conf /etc/nginx/nginx.conf
    echo "✅ Configuración personalizada aplicada"
else
    echo "⚠️ No se encontró configuración personalizada, usando configuración por defecto"
fi

# Verificar configuración de Nginx
echo "🔍 Verificando configuración de Nginx..."
if nginx -t; then
    echo "✅ Configuración de Nginx válida"
else
    echo "❌ Error en configuración de Nginx"
    echo "📋 Contenido de nginx.conf:"
    cat /etc/nginx/nginx.conf
    echo "📋 Logs de error de Nginx:"
    tail -20 /var/log/nginx/error.log 2>/dev/null || echo "No hay logs de error disponibles"
    exit 1
fi

# Verificar que los directorios necesarios existan
echo "🔍 Verificando directorios necesarios..."
mkdir -p /var/log/nginx
mkdir -p /var/run
touch /var/run/nginx.pid
chmod 644 /var/run/nginx.pid
echo "✅ Directorios de Nginx configurados"

# Verificar que PHP-FPM esté respondiendo antes de iniciar Nginx
echo "🔍 Verificando conectividad con PHP-FPM..."
# Usar timeout y curl como alternativa a nc si no está disponible
if command -v nc >/dev/null 2>&1; then
    if nc -z 127.0.0.1 9000; then
        echo "✅ PHP-FPM responde en puerto 9000 (nc)"
    else
        echo "❌ PHP-FPM no responde en puerto 9000 (nc)"
    fi
elif command -v timeout >/dev/null 2>&1; then
    if timeout 3 bash -c "</dev/tcp/127.0.0.1/9000"; then
        echo "✅ PHP-FPM responde en puerto 9000 (timeout)"
    else
        echo "❌ PHP-FPM no responde en puerto 9000 (timeout)"
    fi
else
    echo "⚠️ No se puede verificar conectividad (nc/timeout no disponibles)"
fi

echo "📋 Procesos PHP-FPM:"
ps aux | grep php-fpm || echo "No hay procesos PHP-FPM"
echo "📋 Puertos en uso:"
netstat -tlnp | grep :900 || echo "No hay puertos 900x en uso"
echo "📋 Todos los puertos en uso:"
netstat -tlnp | head -10 || echo "No se puede mostrar puertos"

# Iniciar Nginx
echo "🚀 Iniciando Nginx..."
if nginx -g "daemon off;"; then
    echo "✅ Nginx iniciado correctamente"
else
    echo "❌ Error al iniciar Nginx"
    echo "📋 Logs de error de Nginx:"
    tail -50 /var/log/nginx/error.log 2>/dev/null || echo "No hay logs de error disponibles"
    exit 1
fi
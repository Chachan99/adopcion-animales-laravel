# Usar imagen oficial de PHP con Nginx
FROM richarvey/nginx-php-fpm:3.1.6

# Instalar dependencias del sistema
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git

# Configurar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer primero para aprovechar cache de Docker
COPY composer.json composer.lock ./

# Instalar dependencias de Composer
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar el resto del código de la aplicación
COPY . .

# Completar instalación de Composer
RUN composer dump-autoload --no-dev --optimize

# Configurar permisos
RUN chown -R nginx:nginx /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Crear enlaces simbólicos para storage
RUN php artisan storage:link || true

# Copiar scripts de configuración
COPY render-setup.php ./
COPY check-database.php ./

# Configurar Nginx para puerto 8080 (requerido por Render)
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Crear script de inicio personalizado
RUN echo '#!/bin/bash' > /render-start.sh && \
    echo 'echo "=== INICIANDO APLICACIÓN EN RENDER ===" ' >> /render-start.sh && \
    echo 'echo "Configurando base de datos..." ' >> /render-start.sh && \
    echo 'php /var/www/html/render-setup.php' >> /render-start.sh && \
    echo 'echo "Iniciando servicios..." ' >> /render-start.sh && \
    echo 'exec /start.sh' >> /render-start.sh && \
    chmod +x /render-start.sh

# Exponer puerto 8080
EXPOSE 8080

# Comando de inicio personalizado
CMD ["/render-start.sh"]
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev libpng-dev \
    libjpeg-dev libfreetype6-dev libpq-dev libssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Permisos para almacenamiento y cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Comando de arranque
CMD php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000

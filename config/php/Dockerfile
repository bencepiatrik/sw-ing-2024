FROM library/php:8.3.12-fpm-alpine3.20

# Nainštalujte potrebné nástroje a závislosti
RUN apk update && apk add --no-cache \
    linux-headers \
    git \
    libzip-dev \
    zip \
    unzip \
    autoconf \
    gcc \
    g++ \
    make \
    libc-dev \
    build-base \
    openssh-client

# Inštalácia PHP rozšírení
RUN docker-php-ext-install pdo pdo_mysql opcache

# Inštalácia Redis rozšírenia
RUN pecl install redis && docker-php-ext-enable redis

# Inštalácia Composeru
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Nastavenie pracovného adresára
WORKDIR /var/www/api

# Konfigurácia PHP
COPY ./config/php/php.ini /usr/local/etc/php/php.ini

# Kopírovanie Composer súborov a inštalácia závislostí
COPY ./backend/composer.json ./backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Kopírovanie zdrojového kóduc:\xampp\htdocs\sw-ing-copy\frontend\src
COPY ./backend ./

# Nastavenie oprávnení
RUN chown -R www-data:www-data ./storage ./bootstrap/cache
RUN chmod -R 775 ./storage ./bootstrap/cache

# Optimalizácia Laravel aplikácie
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exponovanie portu
EXPOSE 9000

# Štart PHP-FPM
CMD ["php-fpm"]

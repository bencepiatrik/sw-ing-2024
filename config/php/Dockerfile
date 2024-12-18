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

# Nastavenie pracovného adresára
WORKDIR /var/www/api

# Konfigurácia PHP
COPY ./config/php/php.ini /usr/local/etc/php/php.ini

# Inštalácia Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Kopírovanie zdrojového kódu
COPY ./backend ./
COPY ./backend/.env.example ./.env

# Nastavenie oprávnení
RUN chmod 777 -R ./storage ./bootstrap/cache

# Inštalácia závislostí Laravel
RUN composer install
RUN php artisan optimize

# Exponovanie portu
EXPOSE 9000

# Spúšťanie PHP-FPM
CMD /bin/sh -c " \
          cp -n .env.example .env \
          && php artisan optimize \
          && php-fpm"

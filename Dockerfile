FROM php:apache

RUN a2enmod rewrite

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY ./src /var/www/html/
WORKDIR /var/www/html

CMD ["apache2-foreground"]

FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libcurl4-openssl-dev libzip-dev libonig-dev curl \
    && docker-php-ext-install -j"$(nproc)" mysqli mbstring curl zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

RUN install -d -o www-data -g www-data -m 0700 /var/lib/php/sessions \
    && printf 'session.save_path = "/var/lib/php/sessions"\n' \
       > /usr/local/etc/php/conf.d/sessions.ini

RUN printf '<Directory /var/www/html/Baitaplon>\n    Options FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n' \
    > /etc/apache2/conf-available/baitaplon.conf \
    && a2enconf baitaplon

COPY --chown=www-data:www-data . /var/www/html/Baitaplon

WORKDIR /var/www/html/Baitaplon
EXPOSE 80

FROM php:8.2-apache
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libonig-dev libzip-dev libpq-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd
# Aktiviert Apache Rewrite Modul
RUN a2enmod rewrite
# Composer installieren
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# Arbeitsverzeichnis definieren
WORKDIR /var/www/html
# Projektdateien kopieren
COPY . .
# Rechte setzen
RUN chown -R www-data:www-data /var/www/html
# Symfony Permissions Fix
RUN chmod -R 755 /var/www/html/var
# Apache-Konfiguration (wird unten noch erklärt)
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

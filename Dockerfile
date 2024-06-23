# Utilisez une image PHP officielle en tant que base
FROM php:7.4-fpm

# Définissez le répertoire de travail dans l'image
WORKDIR /var/www/html

# Installez les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Installez les extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiez les fichiers du projet dans l'image
COPY . .

# Exécutez Composer pour installer les dépendances du projet
RUN composer install --optimize-autoloader --no-dev

# Définissez les autorisations des fichiers
RUN chown- suite -R www-data:www-data storage bootstrap/cache

# Exposez le port 9000 pour PHP-FPM
EXPOSE 9000

# Démarrez PHP-FPM lorsque le conteneur est lancé
CMD ["php-fpm"]

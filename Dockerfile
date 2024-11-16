# Dockerfile
FROM php:8.2-fpm

# Install PDO and other necessary extensions for PHP and Composer
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libonig-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer.json to the working directory
COPY composer.json ./

# No scripts are run during install (such as PHPStan or PHPUnit)
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copy existing application directory permissions
COPY --chown=www-data:www-data . .

# Now we should have an autoloader that is capable of autoload
RUN composer dump-autoload --optimize

EXPOSE 9000

CMD ["php-fpm"]
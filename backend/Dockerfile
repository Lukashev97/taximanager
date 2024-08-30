FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN apt install zip unzip

RUN docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy backend dependencies to container
COPY composer.json ./
RUN composer clear-cache
RUN composer install

# Copy all backend files
COPY . .

# Expose the port for Symfony
EXPOSE 8000

# Start the Symfony server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

# docker exec -it project-root_backend_1 bash
# php bin/console doctrine:database:create
# php bin/console doctrine:migrations:migrate

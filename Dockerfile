# Use PHP 8.2 base image
FROM php:8.2-fpm

# Install system dependencies and libraries required for PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    tzdata \
    git \
    unzip \
    zip \
    curl \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . .

# Ensure necessary permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p /var/www/html/storage/framework/{cache,sessions,views} \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Install application dependencies
RUN composer install --no-dev --optimize-autoloader

# Install front-end dependencies and compile assets
RUN npm install && npm run build

# Expose the port the app will run on
EXPOSE 9000

# Entrypoint script to handle migrations and serve the application
CMD php artisan config:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=9000

FROM dunglas/frankenphp:php8.2

# Install PHP Extensions (termasuk GD)
RUN install-php-extensions \
    gd \
    intl \
    pdo_mysql \
    zip \
    exif \
    pcntl \
    bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Install Laravel dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Laravel serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

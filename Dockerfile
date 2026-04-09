FROM dunglas/frankenphp:php8.2

# Install PHP Extensions termasuk GD
RUN install-php-extensions \
    gd \
    intl \
    pdo_mysql \
    zip \
    exif \
    pcntl \
    bcmath

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
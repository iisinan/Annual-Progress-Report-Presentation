FROM php:8.3-cli

# Install system dependencies and PHP extensions required for Laravel and PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy the application code
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Build frontend assets
RUN npm install && npm run build

# Expose port 8000
EXPOSE 8000

# Start the Laravel development server and create the admin user
CMD php artisan migrate --force && php artisan db:seed --class=AdminUserSeeder --force && php artisan serve --host=0.0.0.0 --port=8000

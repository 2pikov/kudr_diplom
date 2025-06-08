FROM php:8.1-fpm

# Set non-interactive mode for apt-get
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update --fix-missing && \
    curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get install -y --no-install-recommends \
    nodejs \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmysqlclient-dev \
    mysql-client && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . .

# Install dependencies
RUN composer install --no-dev
RUN npm install
RUN npm run build

# Create storage link
RUN php artisan storage:link

# Set permissions (crucial for Laravel on some hosts)
RUN chmod -R 777 storage bootstrap/cache

# Generate application key if not exists
RUN php artisan key:generate --force

# Run database migrations
RUN php artisan migrate --force

# Clear all caches
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan route:clear

# Expose port 9000 for FPM
EXPOSE 9000

# Start PHP-FPM service
CMD ["php-fpm"] 
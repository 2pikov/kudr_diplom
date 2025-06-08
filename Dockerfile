FROM php:8.1-fpm

# Set non-interactive mode for apt-get
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update --fix-missing && \
    apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmysqlclient-dev \
    mysql-client && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js and npm (separate step for robustness)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory to the standard web root for FPM
WORKDIR /var/www/html

# Copy existing application directory
COPY . .

# Install Composer and npm dependencies
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

# Expose port 8000 (standard for Laravel development server)
EXPOSE 8000

# Start Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"] 

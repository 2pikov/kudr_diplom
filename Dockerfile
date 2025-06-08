FROM php:8.1-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

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

# Set permissions
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

# Expose port 9000
EXPOSE 9000

# Start command with error reporting
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"] 
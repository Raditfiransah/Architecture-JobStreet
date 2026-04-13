# Stage 1: PHP Dependencies
FROM composer:latest AS vendor
WORKDIR /var/www
COPY composer*.json ./
RUN composer install --no-interaction --prefer-dist --no-dev --no-scripts

# Stage 2: Build Assets
FROM node:20-alpine AS assets-builder
WORKDIR /var/www
COPY package*.json ./
RUN npm install
COPY . .
# Copy vendor from Stage 1 because Vite needs Ziggy located in vendor/
COPY --from=vendor /var/www/vendor /var/www/vendor
RUN npm run build

# Stage 3: PHP Application
FROM php:8.3-fpm

# Arguments
ARG user=laravel
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-libmysqlclient-dev \
    libzip-dev \
    libicu-dev \
    g++ \
    make \
    autoconf \
    libfcgi-bin \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/

# Copy entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy existing application directory
COPY . /var/www

# Copy vendor from Stage 1
COPY --from=vendor /var/www/vendor /var/www/vendor

# Copy built assets from Stage 2
COPY --from=assets-builder /var/www/public/build /var/www/public/build

# Set permissions
RUN chown -R $user:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000

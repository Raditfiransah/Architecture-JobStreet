#!/bin/sh
set -e

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Install dependencies if vendor folder is missing or if desired
# Note: composer install is usually safer to run every time to ensure sync
echo "Running composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate app key if not set
if [ -z "$(grep APP_KEY .env | cut -d '=' -f 2)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Clear cache
echo "Clearing config..."
php artisan config:clear
php artisan cache:clear

# Wait for database
echo "Waiting for database connection to $DB_HOST:$DB_PORT..."
until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; do
    echo "Database ($DB_HOST) is unavailable - sleeping"
    sleep 2
done

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create storage link if it doesn't exist
if [ ! -d public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

# Fix permissions for Laravel directories
echo "Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "Starting PHP-FPM..."
exec php-fpm

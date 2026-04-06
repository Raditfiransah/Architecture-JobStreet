# Deployment Guide

This guide covers deploying Web Architect to various environments using Docker and manual methods.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Environment Types](#environment-types)
- [Docker Deployment (Recommended)](#docker-deployment-recommended)
- [Manual Deployment](#manual-deployment)
- [Server Requirements](#server-requirements)
- [SSL/TLS Setup](#ssltls-setup)
- [CI/CD Pipeline](#cicd-pipeline)
- [Monitoring & Logging](#monitoring--logging)
- [Rollback Procedures](#rollback-procedures)

---

## Prerequisites

For **all deployment methods**:

- Git installed and configured
- SSH access to deployment server
- Domain name configured (optional for staging)
- Composer 2.x on server (if manual deployment)
- Node.js 20+ on server (if manual deployment)

For **Docker deployment**:
- Docker Engine 20.10+
- Docker Compose 2.0+

## Environment Types

The application supports three main environments:

| Environment | Purpose | Domain Example | Database |
|-------------|---------|----------------|----------|
| **Production** | Live user traffic | webarchitect.com | Production MySQL |
| **Staging** | Pre-production testing | staging.webarchitect.com | Staging MySQL |
| **Development** | Local development | localhost:8000 | Local MySQL/SQLite |

---

## Docker Deployment (Recommended)

### 1. Server Preparation

```bash
# Connect to your server
ssh user@your-server-ip

# Install Docker if not present
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo apt-get install docker-compose-plugin

# Add user to docker group (optional, to avoid sudo)
sudo usermod -aG docker $USER
# Log out and back in for group changes to take effect
```

### 2. Clone Repository

```bash
# Navigate to web root (e.g., /var/www or /var/www/html)
cd /var/www

# Clone your repository
sudo git clone <repository-url> webarchitect
cd webarchitect
```

Or if using **Deployer** or similar tool, set up automated cloning.

### 3. Environment Configuration

```bash
# Copy environment example
cp .env.example .env

# Edit .env file with production values
sudo nano .env
```

Required `.env` production settings:

```env
APP_NAME="Web Architect"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://webarchitect.com

# Database (using Docker service)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=webarchitect_production
DB_USERNAME=webarchitect
DB_PASSWORD=strong_password_here

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (using Resend or other SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@webarchitect.com
MAIL_FROM_NAME="${APP_NAME}"

# OTP & Security
OTP_EXPIRY_MINUTES=10
OTP_LENGTH=6

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=production
```

Generate application key:
```bash
php artisan key:generate
```

### 4. Configure Docker Compose for Production

Edit `docker-compose.yml`:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: webarchitect-app
    # Remove volumes for production to avoid file permission issues
    # Or use named volumes for persistent storage
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    # Enable Laravel optimization
    command: php-fpm

  nginx:
    image: nginx:alpine
    container_name: webarchitect-nginx
    ports:
      - "80:80"
      - "443:443"  # For SSL
    volumes:
      - ./:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
      - ./ssl:/etc/nginx/ssl  # SSL certificates
    depends_on:
      - app
    restart: always

  db:
    image: mysql:8.0
    container_name: webarchitect-db
    environment:
      MYSQL_DATABASE: webarchitect_production
      MYSQL_USER: webarchitect
      MYSQL_PASSWORD: your_strong_password
      MYSQL_ROOT_PASSWORD: root_strong_password
    volumes:
      - mysqldata:/var/lib/mysql
      - ./docker/mysql/init.sql:/docker-entrypoint-initdb.d/init.sql  # Optional init
    restart: always
    # Remove port mapping (3306:3306) for production security

  # ... other services (redis, phpmyadmin)
```

### 5. Build and Start Services

```bash
# Build Docker images (first time only)
docker-compose build --no-cache

# Start services in detached mode
docker-compose up -d

# Check status
docker-compose ps

# View logs
docker-compose logs -f app
docker-compose logs -f nginx
```

### 6. Install Dependencies & Migrate

```bash
# SSH into app container
docker-compose exec app bash

# Install Composer dependencies (production)
composer install --no-dev --optimize-autoloader --no-interaction

# Build frontend assets
cd /var/www
npm run build

# Run migrations
php artisan migrate --force

# Generate optimized config and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional: Seed database with initial data
php artisan db:seed --class=AdminUserSeeder

# Exit container
exit
```

### 7. Permissions & Security

```bash
# Set correct permissions
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

### 8. Configure Nginx SSL (Let's Encrypt)

```bash
# Install Certbot on host (not container)
sudo apt-get install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d webarchitect.com -d www.webarchitect.com

# Certbot will auto-configure nginx
# Auto-renewal is added to crontab automatically
```

Test auto-renewal:
```bash
sudo certbot renew --dry-run
```

### 9. Set Up Supervisor for Queue Workers (Optional)

Create `docker/supervisor/supervisord.conf` or use separate container:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
stopwaitsecs=600
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/worker.log
```

Then create Dockerfile for supervisor or use existing queue service.

### 10. Final Checks

```bash
# Check all containers are healthy
docker-compose ps

# Test application
curl -I http://localhost
curl -I http://localhost/api/health  # if health endpoint exists

# Check Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Test database connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

---

## Manual Deployment (Without Docker)

### 1. Server Requirements

See [Server Requirements](#server-requirements) section.

### 2. Upload Code

```bash
# Using Git (recommended)
cd /var/www
git clone <repository-url> webarchitect
cd webarchitect
git checkout main  # or production branch

# Or using FTP/SFTP
# Upload all files to /var/www/webarchitect
```

### 3. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

### 4. Install JavaScript Dependencies & Build

```bash
npm ci --only=production
npm run build
```

### 5. Environment Setup

```bash
cp .env.example .env
php artisan key:generate

# Edit .env with production values
nano .env
```

### 6. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE webarchitect_production;
CREATE USER 'webarchitect'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON webarchitect_production.* TO 'webarchitect'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force
```

### 7. Web Server Configuration

#### Nginx Configuration

Create `/etc/nginx/sites-available/webarchitect`:

```nginx
server {
    listen 80;
    server_name webarchitect.com www.webarchitect.com;
    root /var/www/webarchitect/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/webarchitect /etc/nginx/sites-enabled/
sudo nginx -t  # Test configuration
sudo systemctl reload nginx
```

### 8. PHP Configuration

Edit `/etc/php/8.3/fpm/php.ini`:

```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
memory_limit = 256M
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=10000
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.3-fpm
```

### 9. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 10. Set Up Queue Worker (if using queues)

```bash
# Using Supervisor
sudo nano /etc/supervisor/conf.d/laravel-worker.conf

# Add:
[program:laravel-worker]
process_name=%(program_name)s
command=php /var/www/webarchitect/artisan queue:work --sleep=3 --tries=3
user=www-data
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/var/log/laravel/worker.log

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker
```

---

## Server Requirements

### Minimum Requirements

| Component | Version | Notes |
|-----------|---------|-------|
| PHP | 8.3+ | Required extensions: pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip, curl |
| MySQL | 8.0+ | Or MariaDB 10.3+ |
| Nginx | 1.18+ | Or Apache 2.4+ with mod_rewrite |
| Redis | 7.0+ | Optional (for cache/session/queue) |
| Node.js | 20+ | For asset compilation |
| RAM | 2GB | Minimum, 4GB+ recommended |
| Disk | 10GB | Includes code, assets, and logs |

### PHP Extensions

```bash
# Ubuntu/Debian
sudo apt-get install php8.3-{common,cli,gd,mysql,mbstring,bcmath,xml,fpm,curl,zip}

# CentOS/RHEL
sudo yum install php php-common php-cli php-gd php-mysqlnd php-mbstring php-bcmath php-xml php-fpm php-curl php-zip
```

### Extensions List

Required:
- `openssl` (for encryption)
- `pdo` (database)
- `pdo_mysql` (MySQL driver)
- `mbstring` (string handling)
- `exif` (image metadata)
- `pcntl` (process control for queues)
- `bcmath` (arbitrary precision math)
- `gd` (image processing)
- `zip` (compression)
- `curl` (HTTP requests)
- `tokenizer` (parsing)

Optional:
- `redis` (for Redis driver)
- `memcached` (alternative cache)
- `soap` (if using SOAP APIs)
- `xml` (if using XML parsing)

---

## SSL/TLS Setup

### Using Let's Encrypt (Free)

```bash
# Install Certbot on Ubuntu
sudo apt-get update
sudo apt-get install certbot python3-certbot-nginx

# Obtain certificate (auto-configure nginx)
sudo certbot --nginx -d webarchitect.com -d www.webarchitect.com

# Test auto-renewal
sudo certbot renew --dry-run

# Certbot automatically adds renewal to crontab
```

### Using Commercial Certificate

1. Purchase certificate from CA (DigiCert, Sectigo, etc.)
2. Generate CSR:
   ```bash
   openssl req -newkey rsa:2048 -nodes -keyout webarchitect.key -out webarchitect.csr
   ```
3. Submit CSR to CA, complete validation
4. Download certificate files (certificate + intermediate)
5. Upload to server:
   ```bash
   sudo cp webarchitect.crt /etc/ssl/certs/
   sudo cp webarchitect.key /etc/ssl/private/
   sudo cp intermediate.crt /etc/ssl/certs/
   ```
6. Update Nginx config:
   ```nginx
   ssl_certificate /etc/ssl/certs/webarchitect.crt;
   ssl_certificate_key /etc/ssl/private/webarchitect.key;
   ssl_trusted_certificate /etc/ssl/certs/intermediate.crt;
   ```
7. Reload Nginx: `sudo systemctl reload nginx`

---

## CI/CD Pipeline

### GitHub Actions Example

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip
          coverage: none

      - name: Install Composer dependencies
        run: composer install --no-dev --optimize-autoloader

      - name: Install Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '20'

      - name: Install NPM dependencies
        run: npm ci

      - name: Build assets
        run: npm run build

      - name: Deploy via SSH
        uses: appleboy/ssh-action@v0.1.5
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /var/www/webarchitect
            git pull origin main
            cp .env.example .env
            # Add production-specific env vars
            echo "APP_KEY=${{ secrets.APP_KEY }}" >> .env
            echo "DB_PASSWORD=${{ secrets.DB_PASSWORD }}" >> .env
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            docker-compose restart app nginx
```

---

## Monitoring & Logging

### Laravel Logs

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Using Pail (included)
php artisan pail
```

### Nginx Logs

```bash
# Access logs
tail -f /var/log/nginx/access.log

# Error logs
tail -f /var/log/nginx/error.log
```

### MySQL Slow Query Log

Enable in MySQL config (`/etc/mysql/my.cnf`):

```ini
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
log_queries_not_using_indexes = 1
```

View slow queries:
```bash
mysqldumpslow -s t /var/log/mysql/slow.log
```

### Application Monitoring

Consider integrating:
- **Laravel Pulse** - Built-in monitoring dashboard (paid)
- **Sentry** - Error tracking
- **Horizon** - Queue monitoring (paid)
- **Clockwork** - Debug & profiling
- **New Relic / Datadog** - Performance monitoring

---

## Rollback Procedures

### Docker Deployment Rollback

```bash
# View previous images/containers
docker-compose ps -a
docker images

# Stop current containers
docker-compose down

# If using specific tag, switch to previous version:
# Edit docker-compose.yml to use previous image tag
docker-compose pull
docker-compose up -d

# Or revert git to previous commit
git log --oneline -10  # Find commit to rollback to
git checkout <previous-commit-hash>
docker-compose build --no-cache
docker-compose up -d
```

### Manual Deployment Rollback

```bash
cd /var/www/webarchitect
git log --oneline -10  # Find stable commit
git checkout <stable-commit>

# Re-dependencies
composer install --no-dev --optimize-autoloader
npm ci --only=production
npm run build

# Clear caches
php artisan optimize:clear

# Migrate if needed (be careful with rollbacks)
php artisan migrate

# Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx
```

### Database Rollback

```bash
# Rollback migrations
php artisan migrate:rollback --step=1  # Rollback one batch
php artisan migrate:rollback --step=5  # Rollback 5 batches

# To go back to specific migration
php artisan migrate:rollback --step=X

# If you need to re-run migrations after rollback
php artisan migrate
```

### Emergency Rollback Checklist

1. ✅ Identify failed deployment commit
2. ✅ Notify team/users of maintenance window
3. ✅ Put application in maintenance mode:
   ```bash
   php artisan down --render="errors.maintenance"
   ```
4. ✅ Rollback code to previous version
5. ✅ Rollback database if needed (only if safe)
6. ✅ Rebuild assets and optimize
7. ✅ Restart services
8. ✅ Verify application health: `curl -I https://webarchitect.com/health`
9. ✅ Bring application up:
   ```bash
   php artisan up
   ```
10. ✅ Notify team/users deployment complete

---

## Health Checks

Create a simple health check endpoint:

```php
// routes/web.php
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'healthy';
    } catch (\Exception $e) {
        $dbStatus = 'unhealthy';
    }
    
    return response()->json([
        'status' => $dbStatus === 'healthy' ? 'ok' : 'error',
        'timestamp' => now()->toISOString(),
        'database' => $dbStatus,
        'memory' => memory_get_usage(true),
    ]);
});
```

Configure load balancer or monitoring service to hit `/health` every 30 seconds.

---

## Performance Tuning

### PHP-FPM Optimization

Edit `/etc/php/8.3/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### MySQL Optimization

Edit `/etc/mysql/my.cnf`:

```ini
[mysqld]
innodb_buffer_pool_size = 1G  # 70-80% of RAM on dedicated DB server
innodb_log_file_size = 256M
query_cache_type = 1
query_cache_size = 128M
max_connections = 100
```

Restart MySQL: `sudo systemctl restart mysql`

### Redis Optimization

```bash
# Configure Redis to persist to disk
sudo nano /etc/redis/redis.conf

# Set:
save 900 1
save 300 10
save 60 10000
maxmemory 256mb
maxmemory-policy allkeys-lru
```

---

**Last Updated**: 2026-04-07

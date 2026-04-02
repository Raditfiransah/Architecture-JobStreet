# Web-Architect Project Technology Stack

## Overview
This document outlines the complete technology stack used in the Web-Architect project to help AI assistants understand the environment and provide more accurate assistance.

## Backend
- **Framework**: Laravel 13.x
- **Language**: PHP 8.3+
- **Key Features**:
  - Eloquent ORM for database operations
  - Blade templating engine (though primarily using Vue via Inertia)
  - Artisan CLI for development tasks
  - Queue system for background jobs
  - Migration and seeding system
  - Built-in authentication scaffolding
  - Testing support with PHPUnit

## Frontend
- **Framework**: Vue 3.x
- **SPA Bridge**: Inertia.js v3.x
- **Build Tool**: Vite v8.x
- **Styling**: Tailwind CSS v4.x
- **Key Features**:
  - Single-page application experience without building an API
  - Server-side rendering capabilities
  - Hot module replacement during development
  - Utility-first CSS approach
  - Component-based architecture

## Database
- **System**: MySQL 8.0
- **Interaction**: Laravel Eloquent ORM & Query Builder
- **Management**: phpMyAdmin (via Docker container)
- **Migration System**: Laravel migrations for schema versioning

## Development Environment
### Containerization (Docker)
- **Orchestration**: Docker Compose
- **Services**:
  1. **app**: PHP-FPM container running Laravel application
     - Base image: php:8.4-fpm
     - Extensions installed: pdo, pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip
     - User: laravel (uid: 1000)
  2. **nginx**: Nginx web server (alpine)
     - Port mapping: 8000:80
     - Custom configuration in docker/nginx/default.conf
  3. **db**: MySQL 8.0 database
     - Persistent volume for data storage
     - Health check enabled
  4. **phpmyadmin**: Database administration interface
     - Port mapping: 8081:80
  5. **node**: Node.js environment for asset compilation
     - Base image: node:20-alpine
     - Runs Vite development server
     - Port mapping: 5173:5173

### Local Development Tools
- **Composer**: PHP dependency management
- **npm/node**: JavaScript package management and build tooling
- **Artisan**: Laravel command-line interface
  - `php artisan serve` - Development server
  - `php artisan queue:listen` - Queue worker
  - `php artisan pail` - Log viewer
  - `php artisan migrate` - Database migrations
  - `php artisan test` - Run tests
- **Concurrently**: Runs multiple processes simultaneously (defined in composer.json scripts)
- **Laravel Pint**: PHP code style fixer
- **Laravel Pennant**: Feature flag management (if used)
- **Laravel Pulse**: Monitoring dashboard (if used)

## Build & Asset Pipeline
- **Vite**: Modern frontend build tool
  - Development server with HMR
  - Production builds via `vite build`
- **Laravel Vite Plugin**: Integration between Laravel and Vite
- **Tailwind CSS**: Utility-first CSS framework
  - Configured via vite.config.js
  - JIT compilation in development
- **PostCSS**: CSS processing (via Tailwind)

## Testing
- **Framework**: PHPUnit
- **Configuration**: phpunit.xml
- **Features**:
  - Test discovery and execution
  - Database testing helpers
  - Mocking capabilities
  - Collision for beautiful error reporting
  - Pest plugin support (if adopted)
- **Commands**:
  - `php artisan test` - Run all tests
  - `vendor/bin/pest` - If using Pest instead of PHPUnit

## Environment Configuration
- **.env**: Environment-specific configuration
- **.env.example**: Template for environment variables
- **Key Variables**:
  - Database connection (DB_*)
  - Application settings (APP_*)
  - Mail configuration (MAIL_*)
  - Queue connection (QUEUE_CONNECTION)
  - Cache driver (CACHE_DRIVER)
  - Session driver (SESSION_DRIVER)

## Project Structure
```
/home/radit/Project/Polinema/Web-Architect
├── app/                 # Laravel application code
├── bootstrap/           # Framework bootstrapping
├── config/              # Configuration files
├── database/            # Migrations, factories, seeders
├── public/              # Publicly accessible assets
├── resources/           # Views, CSS, JavaScript
├── routes/              # Route definitions
├── storage/             # Logs, cached files, uploads
├── tests/               # Automated tests
├── vendor/              # Composer dependencies
├── node_modules/        # NPM dependencies
├── docker/              # Docker configuration
│   ├── nginx/           # Nginx configuration
│   └── php/             # PHP-FPM Dockerfile
├── .env                 # Environment variables
├── composer.json        # PHP dependencies
├── package.json         # JS dependencies & scripts
├── vite.config.js       # Vite configuration
├── docker-compose.yml   # Docker services definition
└── README.md            # Project overview
```

## Development Workflow
1. **Setup**:
   ```bash
   cp .env.example .env
   composer install
   npm install
   php artisan key:generate
   php artisan migrate
   ```

2. **Development**:
   ```bash
   # Using Docker Compose (recommended)
   docker-compose up -d
   
   # Or locally for specific services
   php artisan serve          # Laravel dev server
   npm run dev               # Vite dev server
   php artisan queue:listen  # Queue worker
   ```

3. **Database**:
   ```bash
   php artisan migrate        # Run migrations
   php artisan migrate:rollback # Rollback last migration
   php artisan db:seed       # Run seeders
   php artisan migrate:fresh --seed # Fresh DB with seed data
   ```

4. **Testing**:
   ```bash
   php artisan test          # Run PHPUnit tests
   ```

5. **Production Build**:
   ```bash
   npm run build             # Production asset build
   ```

## Code Standards & Quality
- **PHP**: PSR-12 coding standards (enforced via Laravel Pint)
- **JavaScript/Vue**: Standard Vue 3 composition API patterns
- **CSS**: Tailwind CSS utility classes with custom configurations
- **Git**: Standard Git workflow with meaningful commit messages

## Common Artisan Commands
```
php artisan serve                    # Start development server
php artisan make:model ModelName     # Generate Eloquent model
php artisan make:controller ControllerName # Generate controller
php artisan make:migration migration_name # Generate migration
php artisan make:seeder SeederName   # Generate seeder
php artisan make:factory FactoryName # Generate model factory
php artisan make:request RequestName # Generate form request
php artisan make:middleware MiddlewareName # Generate middleware
php artisan make:policy PolicyName   # Generate authorization policy
php artisan make:event EventName     # Generate event class
php artisan make:listener ListenerName # Generate event listener
php artisan make:job JobName         # Generate job class
php artisan make:component ComponentName # Generate Blade component
php artisan make:view view.path      # Generate Blade view
php artisan make:notification NotificationName # Generate notification
php artisan make:mail MailName       # Generate mail class
php artisan make:channel ChannelName # Generate broadcasting channel
php artisan make:rule RuleName       # Generate validation rule
php artisan make:test TestName       # Generate test class
```

## Troubleshooting Tips
1. **Permission Issues**:
   ```bash
   # Fix storage and bootstrap/cache permissions
   chmod -R 775 storage bootstrap/cache
   chown -R $USER:www-data storage bootstrap/cache
   ```

2. **Cache Issues**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan optimize:clear
   ```

3. **Database Connection**:
   - Verify .env DB_* settings
   - Ensure MySQL container is healthy (docker-compose ps)
   - Check phpmyadmin at http://localhost:8081

4. **Asset Compilation**:
   - Ensure node service is running (docker-compose logs node)
   - Check Vite server at http://localhost:5173
   - Clear browser cache if assets don't update

5. **Queue Processing**:
   - Check if queue worker is running
   - Review failed jobs: `php artisan queue:failed`
   - Retry failed jobs: `php artisan queue:retry all`

## Security Considerations
- Environment variables stored in .env (never committed)
- Laravel's built-in CSRF protection
- XSS protection via Blade/Vue auto-escaping
- Secure password hashing (bcrypt)
- Rate limiting middleware available
- CORS configuration for API routes
- SQL injection prevention via Eloquent/Query Builder
- File upload validation and sanitization guidelines

This stack combines Laravel's robust backend capabilities with modern frontend practices through Inertia.js and Vite, all orchestrated through Docker for consistent development and deployment environments.
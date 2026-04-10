# Web Architect Project

This is a Laravel-based web application that uses Docker for containerization to ensure consistent development environments across teams.

## Prerequisites

- Docker and Docker Compose installed on your machine
- Git installed (for cloning the repository)

## Getting Started

### Option 1: Using the Startup Script (Recommended)

The easiest way to get started is to use the provided startup script:

```bash
git clone <repository-url>
cd Web-Architect
chmod +x start.sh
./start.sh
```

This script will:
1. Check if Docker is installed
2. Build and start all containers
3. Display access URLs and useful commands

### Option 2: Manual Setup with Makefile

Alternatively, you can use the Makefile for more control:

```bash
git clone <repository-url>
cd Web-Architect

# Build and start containers
make up

# Install JavaScript dependencies (PHP deps handled by Docker entrypoint)
make node-install    # npm install in node container

# Application setup is handled by Docker entrypoint:
# - .env file creation from .env.example
# - Composer install
# - Application key generation
# - Database migrations
# - Cache clearing
# - Permission fixing

# Access the application immediately after containers are healthy
```

### Option 3: Direct Docker Commands

If you prefer to use Docker Compose directly:

```bash
git clone <repository-url>
cd Web-Architect

# Build and start containers
docker compose up -d --build

# Install JavaScript dependencies (PHP deps handled by Docker entrypoint)
docker compose exec node npm install

# Application setup is handled by Docker entrypoint (see above)
```

## Access the Application

- **Main Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8081 (use database credentials from .env)
- **Vite Dev Server**: http://localhost:5173 (for HMR during development)

## Useful Commands

```bash
# Using Makefile
make up           # Start containers
make down         # Stop and remove containers
make build        # Rebuild containers
make restart      # Restart containers
make shell        # Enter PHP application container
make logs         # Follow logs of all containers
make migrate      # Run database migrations
make seed         # Run database seeders
make node-install # Run npm install in node container

# Direct Docker Compose commands
docker compose up -d          # Start containers in background
docker compose down           # Stop containers
docker compose logs -f        # View logs
docker compose exec app sh    # Enter app container
docker compose exec node sh   # Enter node container
```

## Important Notes About Docker Entrypoint

The Docker container for the PHP application includes an entrypoint script that automatically handles:
- Creating `.env` from `.env.example` if it doesn't exist
- Running `composer install` to install PHP dependencies
- Generating the application key if not already set
- Clearing configuration and cache
- Running database migrations (`php artisan migrate --force`)
- Fixing permissions for Laravel directories

This means you don't need to manually run `composer install`, `php artisan key:generate`, or `php artisan migrate` after starting the containers - these are handled automatically!

## Database Credentials (from .env)

By default, the database credentials are:
- Database: laravel
- Username: laravel
- Password: secret
- Host: db
- Port: 3306

These can be customized in the `.env` file.

## Notes

- The application will be available at http://localhost:8000 once all services are healthy (typically within 1-2 minutes after startup)
- Initial startup may take a few minutes as Docker images are built and dependencies are installed
- Make sure Docker has sufficient resources allocated (at least 2GB RAM recommended)
- The startup script (`start.sh`) provides the quickest way to get started
- The Makefile offers convenient shortcuts for common development tasks
- Since the Docker entrypoint handles Laravel setup, you can focus on frontend development with `docker compose exec node npm run dev`
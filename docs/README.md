# Web Architect

*A modern recruitment platform connecting architects with companies and clients*

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vue.js)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Web Architect adalah platform rekrutmen khusus untuk industri arsitektur dan desain. Platform ini menghubungkan arsitek, perusahaan konstruksi, dan klien dalam satu ekosistem terintegrasi.

## ✨ Features

- **Multi-role System** - Admin, Client, Perusahaan (Company), Arsitek (Architect)
- **Real-time Messaging** - Inbox system for communication
- **Job Management** - Lowongan (job postings) with applications
- **Portfolio Management** - Arsitek can showcase portfolio
- **Project Management** - Clients can post and manage projects
- **Proposal System** - Arsitek can submit proposals for projects
- **Email Verification** - OTP-based verification
- **Dashboard Analytics** - Role-specific dashboards with stats
- **Responsive Design** - Built with Tailwind CSS

## 📋 Prerequisites

Before installation, ensure you have:

- **PHP 8.3+** with extensions: pdo, pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip
- **Composer 2.x** - PHP package manager
- **Node.js 20+** - JavaScript runtime
- **npm** or **yarn** - Package manager
- **Docker & Docker Compose** (recommended) - For containerized environment
- **MySQL 8.0** - If not using Docker

## 🚀 Quick Start

### Option 1: Docker (Recommended)

```bash
# Clone repository
git clone <repository-url>
cd Web-Architect

# Copy environment file
cp .env.example .env

# Update .env with your settings
# DB_CONNECTION=mysql
# DB_HOST=db
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=laravel
# DB_PASSWORD=your_password

# Start all services
docker-compose up -d

# Install dependencies and build assets
docker-compose exec app composer install
docker-compose exec node npm install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec node npm run build

# Access the application
# - App: http://localhost:8000
# - phpMyAdmin: http://localhost:8081
# - Vite Dev Server: http://localhost:5173
```

### Option 2: Local Development

```bash
# Clone repository
git clone <repository-url>
cd Web-Architect

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=web_architect
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve

# In another terminal, start Vite
npm run dev

# Access the application at http://localhost:8000
```

## 📁 Project Structure

```
Web-Architect/
├── app/                    # Laravel application code
│   ├── Http/
│   │   ├── Controllers/    # Application controllers
│   │   ├── Middleware/     # Custom middleware
│   │   └── Resources/      # API resources
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic services
│   └── View/               # View composers (if any)
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── public/                 # Publicly accessible files
├── resources/
│   ├── js/                 # Frontend JavaScript
│   │   ├── Components/     # Reusable Vue components
│   │   ├── Composables/    # Vue composables
│   │   ├── Layouts/        # Inertia layout components
│   │   ├── Pages/          # Vue page components
│   │   └── lib/            # Frontend libraries
│   ├── css/                # Stylesheets
│   └── views/              # Blade views (fallback)
├── routes/                 # Route definitions
│   ├── web.php             # Web routes
│   ├── auth.php            # Authentication routes
│   ├── admin.php           # Admin dashboard routes
│   ├── client.php          # Client dashboard routes
│   ├── perusahaan.php      # Company dashboard routes
│   └── arsitek.php         # Architect dashboard routes
├── storage/                # Application storage
├── docker/                 # Docker configuration
│   ├── nginx/
│   │   └── default.conf    # Nginx configuration
│   └── php/
│       └── Dockerfile      # PHP-FPM Dockerfile
├── .env.example            # Environment template
├── docker-compose.yml      # Docker services
├── vite.config.js          # Vite configuration
├── tailwind.config.js      # Tailwind configuration
└── README.md               # This file
```

## 🔧 Tech Stack

### Backend
- **Framework**: Laravel 12.x
- **Language**: PHP 8.3+
- **Database**: MySQL 8.0
- **Cache/Queue**: Redis
- **Authentication**: Laravel Breeze with OTP verification
- **API**: OOTB JSON responses (no explicit API routes)

### Frontend
- **Framework**: Vue 3.x (Composition API + `<script setup>`)
- **SPA Bridge**: Inertia.js v3.x
- **Build Tool**: Vite v8.x
- **Styling**: Tailwind CSS v4.x with shadcn/ui components
- **Icons**: Lucide Vue Next & Radix Icons

### Development
- **Containerization**: Docker & Docker Compose
- **Code Style**: Laravel Pint (PSR-12)
- **Testing**: PHPUnit
- **Queue Worker**: Laravel Horizon (not configured yet)
- **Monitoring**: Laravel Pail (log viewer)

## 🛠️ Available Scripts

### Composer Scripts
```bash
composer setup              # Full project setup (install, migrate, build)
composer dev                # Start dev environment (uses concurrently)
composer test               # Run tests
composer pint               # Fix code style
```

### NPM Scripts
```bash
npm run dev                 # Start Vite dev server
npm run build               # Build for production
npm run build:dev           # Build for development
```

### Artisan Commands
```bash
php artisan serve          # Start Laravel dev server
php artisan migrate        # Run migrations
php artisan migrate:fresh  # Reset and run all migrations
php artisan db:seed        # Seed database
php artisan make:model     # Generate model
php artisan make:controller # Generate controller
php artisan route:list     # List all routes
php artisan config:cache   # Cache configuration
php artisan route:cache    # Cache routes
php artisan view:cache     # Cache views
```

## 📚 Documentation

For more detailed documentation, please see:

- **[ARCHITECTURE.md](ARCHITECTURE.md)** - System architecture and design patterns
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - API endpoints and usage
- **[DATABASE.md](DATABASE.md)** - Database schema and migrations
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Deployment and environment setup
- **[DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)** - Development standards and workflow
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - How to contribute to the project
- **[SECURITY.md](SECURITY.md)** - Security policies and best practices
- **[TESTING.md](TESTING.md)** - Testing strategy and guidelines
- **[CHANGELOG.md](CHANGELOG.md)** - Version history
- **[FAQ.md](FAQ.md)** - Frequently asked questions
- **[ONBOARDING.md](ONBOARDING.md)** - Guide for new developers

## 🎯 User Roles

The system supports 4 main user roles:

1. **Admin** - Platform administrator with full access
2. **Client** - Can post projects and manage proposals
3. **Perusahaan** (Company) - Can post job vacancies and manage applicants
4. **Arsitek** (Architect) - Can apply for jobs and submit project proposals

Each role has its own dashboard with specific features and permissions.

## 📊 Database Overview

**Main Tables:**
- `users` - User accounts (with role-based access)
- `arsitek_profiles` - Architect profile details
- `company_profiles` - Company profile details
- `lowongan` - Job vacancies/postings
- `email_verification_codes` - OTP verification codes

Additional standard Laravel tables: `cache`, `jobs`, `sessions`, `personal_access_tokens`.

## 🔐 Authentication

- Multi-auth with role-based redirects
- Email verification with OTP (One-Time Password)
- Password reset via email
- Session-based authentication (SPA via Inertia)
- Sanctum tokens for API (if needed)

## 🤝 Contributing

We welcome contributions! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the pull request process.

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/your-org/Web-Architect/issues)
- **Discussions**: [GitHub Discussions](https://github.com/your-org/Web-Architect/discussions)
- **Email**: support@webarchitect.com

---

**Last Updated**: 2026-04-07

# Changelog

All notable changes to this project will be documented in this file.

Format based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

### Added
- Initial project scaffold with Laravel 12 + Vue 3 + Inertia
- Docker development environment with all services
- Multi-role authentication (Admin, Client, Perusahaan, Arsitek)
- OTP email verification system
- Admin dashboard with moderation features
- Client dashboard for project management
- Company dashboard for job postings
- Architect dashboard for portfolio and applications
- Public job listings (`/lowongan`)
- Public architect directory (`/hire-arsitek`)
- Breadcrumb navigation component
- Responsive UI with Tailwind CSS + shadcn/ui
- Private pages with authenticated layouts
- Basic Vue component library
- Project documentation in `docs/` folder

### Changed
- N/A (initial release)

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [0.1.0] - 2026-04-07

### Initial Release

**This is the first production release of Web Architect.**

**Major Features Implemented**:

1. **Authentication System**
   - User registration with role selection
   - Email OTP verification
   - Login/logout functionality
   - Password reset via email
   - Role-based redirects

2. **Role-Specific Dashboards**
   - Admin: User moderation, content approval, reports
   - Client: Create/manage projects, review proposals
   - Perusahaan: Post jobs, manage applications
   - Arsitek: Portfolio, apply for jobs, submit proposals

3. **Public Pages**
   - Landing page
   - Job listings (`/lowongan`)
   - Architect directory (`/hire-arsitek`)
   - Job and architect detail pages

4. **Core Infrastructure**
   - Docker containerization
   - MySQL 8.0 database
   - Redis for caching/sessions
   - Nginx web server
   - Vite build system
   - Code quality tools (Laravel Pint)

**Known Limitations** (Future Work):

- ✅ *All pages implemented as placeholders (DefaultPublicPage) for:
  - `lowongan.show`
  - `arsitek.profil`
  - `info.*` routes not fully implemented
  - `proyek.*` routes not yet implemented
- ✅ *Portfolio items table not created yet (will be separate table)
- ✅ *Applications table not created yet (job applications)
- ✅ *Proposals table not created yet (project proposals)
- ✅ *Messaging/inbox system partially implemented (routes exist, UI TBD)
- ✅ *Notifications table not created yet
- ✅ *File upload not yet implemented
- ✅ *Real-time features not yet implemented
- ✅ *Search functionality basic only
- ✅ *Unit tests not written yet (target >85% for future)

**Tech Stack**:
- Laravel 12.x
- PHP 8.3+
- Vue 3 (Composition API)
- Inertia.js 3.x
- Vite 8.x
- Tailwind CSS 4.x
- MySQL 8.0
- Redis
- Docker & Docker Compose

**Documentation**:
- Complete `docs/` folder with 12 comprehensive guides
- README with quick start instructions
- Architecture diagrams (Mermaid)
- API documentation (Inertia format)
- Database schema reference
- Deployment guides (Docker + manual)
- Development standards
- Testing strategy
- Security policies
- Contributing guidelines

---

## Versioning Scheme

We use [Semantic Versioning](https://semver.org/):

- **Major** (`X.0.0`) - Breaking changes
- **Minor** (`0.X.0`) - New features, backward compatible
- **Patch** (`0.0.X`) - Bug fixes, documentation

---

## Upgrade Guide

### Upgrading from [Previous Version]

If upgrading from an earlier version:

1. Backup your database and `.env` file
2. Pull latest code: `git pull origin main`
3. Run: `composer install --no-dev --optimize-autoloader`
4. Run: `npm ci && npm run build`
5. Run migrations: `php artisan migrate`
6. Clear caches: `php artisan optimize:clear`
7. Restart services: `docker-compose restart` (if using Docker)

---

**Legend**:

- `Added` for new features
- `Changed` for changes in existing functionality
- `Deprecated` for soon-to-be removed features
- `Removed` for now removed features
- `Fixed` for any bug fixes
- `Security` in case of vulnerabilities

---

[Unreleased]: https://github.com/your-org/Web-Architect/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/your-org/Web-Architect/releases/tag/v0.1.0

# Documentation Index

Welcome to the Web Architect documentation. This index helps you navigate through all available documentation.

---

## Quick Links

### Getting Started
- **[README.md](README.md)** - Project overview, quick start, installation
- **[ONBOARDING.md](ONBOARDING.md)** - Complete guide for new developers

### Architecture & Design
- **[ARCHITECTURE.md](ARCHITECTURE.md)** - System architecture, patterns, data flow
- **[DATABASE.md](DATABASE.md)** - Database schema, migrations, seeding

### Development
- **[DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)** - Coding standards, workflows, best practices
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - All endpoints (Web & Inertia)
- **[TESTING.md](TESTING.md)** - Testing strategy, writing tests, coverage

### Operations
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Docker, manual, CI/CD, monitoring
- **[SECURITY.md](SECURITY.md)** - Security policies, best practices, incident response

### Community
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - How to contribute, PR process, code review
- **[CHANGELOG.md](CHANGELOG.md)** - Version history, release notes
- **[FAQ.md](FAQ.md)** - Common questions and troubleshooting

---

## Documentation by Role

### For New Developers
1. README.md
2. ONBOARDING.md
3. DEVELOPMENT_GUIDE.md
4. ARCHITECTURE.md

### For Backend Developers
1. ARCHITECTURE.md
2. API_DOCUMENTATION.md
3. DATABASE.md
4. TESTING.md

### For Frontend Developers
1. ARCHITECTURE.md (Frontend section)
2. API_DOCUMENTATION.md (Inertia pages)
3. DEVELOPMENT_GUIDE.md (Frontend section)
4. TESTING.md (Vue tests)

### For DevOps/System Admins
1. DEPLOYMENT.md
2. SECURITY.md
3. DATABASE.md (Backup/Restore section)
4. ARCHITECTURE.md (Scalability section)

### For Project Managers
1. README.md
2. CHANGELOG.md
3. ONBOARDING.md (Team processes)
4. CONTRIBUTING.md

---

## Documentation Summary

| File | Purpose | Audience | Last Updated |
|------|---------|----------|--------------|
| [README.md](README.md) | Project overview & quick start | Everyone | 2026-04-07 |
| [ARCHITECTURE.md](ARCHITECTURE.md) | System design & patterns | Devs, Architects | 2026-04-07 |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | All endpoints & requests | Frontend, Backend, API consumers | 2026-04-07 |
| [DATABASE.md](DATABASE.md) | Schema, migrations, seeding | DB Admins, Backend | 2026-04-07 |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Setup, deploy, maintain | DevOps, SysAdmins | 2026-04-07 |
| [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) | Coding standards & workflows | Developers | 2026-04-07 |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Contribution guidelines | Contributors | 2026-04-07 |
| [SECURITY.md](SECURITY.md) | Security policies & practices | Security team, All devs | 2026-04-07 |
| [TESTING.md](TESTING.md) | Testing strategy & examples | QA, Developers | 2026-04-07 |
| [CHANGELOG.md](CHANGELOG.md) | Version history | Users, Contributors | 2026-04-07 |
| [FAQ.md](FAQ.md) | Common questions & answers | Everyone | 2026-04-07 |
| [ONBOARDING.md](ONBOARDING.md) | New developer guide | New hires | 2026-04-07 |

---

## Project Information

**Project Name**: Web Architect  
**Type**: Recruitment Platform for Architecture Industry  
**Tech Stack**: Laravel 12, Vue 3, Inertia.js, MySQL, Docker  
**Version**: 0.1.0 (Initial Release)  
**License**: MIT  
**Last Documentation Update**: 2026-04-07

---

## Quick Reference

### Environment Setup Commands

```bash
# Docker
docker-compose up -d
docker-compose exec app composer install
docker-compose exec node npm install
docker-compose exec app php artisan migrate

# Local
composer install
npm install
php artisan key:generate
php artisan migrate
npm run build
```

### Artisan Commands

```bash
php artisan serve              # Dev server
php artisan migrate            # Run migrations
php artisan db:seed           # Seed database
php artisan test              # Run tests
php artisan route:list        # List routes
php artisan optimize:clear    # Clear all caches
php artisan config:cache       # Cache config
php artisan route:cache        # Cache routes
```

### Docker Commands

```bash
docker-compose up -d          # Start all services
docker-compose ps             # Check status
docker-compose logs -f app    # View logs
docker-compose down           # Stop services
docker-compose down -v        # Stop & remove volumes
docker-compose exec app bash  # SSH into container
```

---

## Need Help?

- Check [FAQ.md](FAQ.md) first for common issues
- Search [GitHub Issues](https://github.com/your-org/Web-Architect/issues)
- Ask in team Slack/Discord
- Contact: support@webarchitect.com

---

## Contributing to Documentation

Found a typo or want to improve docs?

1. Fork the repo
2. Edit markdown files in `docs/`
3. Submit PR following [CONTRIBUTING.md](CONTRIBUTING.md)

Docs are written in **Markdown** with proper headers, lists, and code blocks.

---

**Documentation Version**: 0.1.0  
**Matches Application Version**: 0.1.0  
**Last Synced**: 2026-04-07

# Developer Onboarding Guide

Welcome to the Web Architect team! This guide will help you get started with the project quickly.

---

## First Day Checklist

### Before You Start

✅ **Required Tools Installed**:
- [ ] Docker & Docker Compose
- [ ] Git
- [ ] VS Code or PHPStorm
- [ ] PHP 8.3+ (if not using Docker)
- [ ] Node.js 20+
- [ ] MySQL 8.0 (if not using Docker)

### Account & Access Setup

- [ ] Join GitHub organization: Request invite from team lead
- [ ] Set up SSH keys for GitHub (if not already)
- [ ] Access credentials for staging/production (from DevOps)
- [ ] Slack/Discord invite (team communication)
- [ ] Project management tool access (Jira, Trello, etc.)
- [ ] Email account for notifications

### Clone & Setup

- [ ] Clone repository
  ```bash
  git clone git@github.com:your-org/Web-Architect.git
  cd Web-Architect
  ```
- [ ] Copy `.env.example` to `.env`
- [ ] Start Docker containers: `docker-compose up -d`
- [ ] Install dependencies:
  ```bash
  docker-compose exec app composer install
  docker-compose exec node npm install
  ```
- [ ] Generate app key: `docker-compose exec app php artisan key:generate`
- [ ] Run migrations: `docker-compose exec app php artisan migrate`
- [ ] Build frontend: `docker-compose exec node npm run build`
- [ ] Seed sample data: `docker-compose exec app php artisan db:seed`

### Verify Installation

- [ ] Access app at http://localhost:8000
- [ ] Login with seeded admin account:
  - Email: `admin@webarchitect.com`
  - Password: Check `database/seeders/UserSeeder.php`
- [ ] Test Vite HMR: http://localhost:5173
- [ ] Access phpMyAdmin: http://localhost:8081

---

## Project Structure Overview

```
Root/
├── app/                    # Laravel backend
├── resources/js/           # Vue.js frontend
├── database/               # Migrations & seeders
├── routes/                 # Route definitions
├── docker/                 # Docker configs
├── docs/                   # Documentation
└── public/                 # Public assets
```

Study key directories:
1. **app/Http/Controllers/** - See how controllers organize by role
2. **resources/js/Pages/** - Understand Inertia page structure
3. **routes/** - Learn the routing system
4. **docs/ARCHITECTURE.md** - Read system design

---

## Key Concepts to Understand

### 1. Laravel Fundamentals

- **Eloquent ORM**: Models, relationships (one-to-one, one-to-many)
- **Migrations**: Database schema versioning
- **Middleware**: Route filtering (auth, role-based)
- **Service Container**: Dependency injection

### 2. Inertia.js SPA Pattern

- No traditional API endpoints
- Server returns Vue component name + props
- Client handles routing via Inertia
- Maintains SPA feel without page reloads

Read: https://inertiajs.com/

### 3. Vue 3 Composition API

- `<script setup>` syntax
- `ref()`, `reactive()`, `computed()`, `watch()`
- Props down, events up
- Composables for shared logic

Read: https://vuejs.org/guide/introduction.html

### 4. Role System

Four distinct roles with separate dashboards:
- **admin** - Full control
- **client** - Manages projects
- **perusahaan** - Manages job postings
- **arsitek** - Portfolio & applications

Roles stored in `users.role` column. Middleware: `role:admin`, etc.

---

## Your First Tasks

### Beginner Tasks (Week 1)

1. **Read Documentation**
   - [README.md](README.md)
   - [ARCHITECTURE.md](ARCHITECTURE.md)
   - [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)

2. **Explore the Codebase**
   - Trace one complete user flow (e.g., registration → login → dashboard)
   - Identify where each role's dashboard is defined
   - Find how breadcrumbs are implemented

3. **Fix a Bug**
   - Check [GitHub Issues](https://github.com/your-org/Web-Architect/issues)
   - Look for `good first issue` label
   - Follow contribution guide

4. **Write Tests**
   - Pick a model or controller without tests
   - Write unit tests for a method
   - Aim for >85% coverage

### Intermediate Tasks (Week 2-3)

1. **Add Feature**
   - Create portfolio CRUD (model, migration, controller, views)
   - Implement messaging/inbox system
   - Add search functionality for jobs

2. **Refactor**
   - Extract services from controllers
   - Convert Class components to Composition API
   - Implement Vue composables

3. **Optimize**
   - Fix N+1 queries using Laravel Debugbar
   - Add database indexes
   - Cache frequently accessed data

### Advanced Tasks (Week 4+)

1. **Architecture**
   - Implement event-driven notifications
   - Add queue workers for background jobs
   - Set up Laravel Horizon

2. **Features**
   - Real-time messaging with websockets
   - File upload for portfolios
   - Advanced search with filters
   - Reporting dashboard

3. **DevOps**
   - Configure CI/CD pipeline
   - Set up monitoring (Laravel Pulse, Sentry)
   - Implement load balancing

---

## Learning Resources

### Must-Read
- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Guide](https://vuejs.org/guide/introduction.html)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

### Recommended
- [Laracasts](https://laracasts.com/) - Video tutorials
- [Vue School](https://vueschool.io/) - Vue courses
- [Laravel Beyond CRUD](https://laravel-beyond-crud.com/) - Advanced patterns

### Team-Specific
- See [docs/](docs/) folder for project-specific guides
- Internal wiki (if available)
- Pair programming sessions with senior devs

---

## Daily Workflow

### Morning Routine

```bash
# 1. Pull latest changes
git pull origin main

# 2. Install any new dependencies
docker-compose exec app composer install
docker-compose exec node npm install

# 3. Run any new migrations
docker-compose exec app php artisan migrate

# 4. Clear caches
docker-compose exec app php artisan optimize:clear

# 5. Start dev services
docker-compose up -d
docker-compose exec node npm run dev
```

### During Development

1. **Create a branch**:
   ```bash
   git checkout -b feature/your-feature
   ```

2. **Make changes following standards**:
   - Follow PSR-12 (run pint)
   - Use Vue Composition API
   - Write tests

3. **Check your work**:
   ```bash
   ./vendor/bin/pint
   php artisan test
   ```

4. **Commit** with proper message:
   ```bash
   git add .
   git commit -m "feat(auth): add two-factor auth support"
   ```

5. **Push and PR**:
   ```bash
   git push origin feature/your-feature
   ```
   Then open GitHub PR.

---

## Code Review Process

### What to Expect

1. **Automated checks** (CI):
   - Tests pass
   - Code style (Pint)
   - Type checking (PHPStan if configured)

2. **Human review**:
   - Senior dev reviews your code
   - Comments on specific lines
   - Suggestions for improvement
   - Approve or request changes

3. **Approval and merge**:
   - At least 1 approval required
   - Squash merge to main
   - Delete feature branch

### Giving Code Reviews

- Be constructive, not critical
- Ask questions, don't dictate
- Focus on code quality, not personal preferences
- Consider maintainability
- Suggest alternatives when possible

---

## Communication

### Channels

- **Slack/Discord**: Daily chat, quick questions
- **GitHub Issues**: Bug reports, feature tracking
- **GitHub PRs**: Code reviews
- **Weekly Standup**: Progress updates (Mondays 9 AM)
- **Retrospective**: Lessons learned (Friddays 4 PM)

### Asking Questions

- Search existing docs first
- Check closed issues in GitHub
- Ask in public channels (not DMs) so others can learn
- Be specific: "How does the OTP flow work?" not "How do I do auth?"

---

## Mentorship

### Assigned Mentor

Your team lead will assign a mentor (senior dev) who will:
- Answer your questions
- Review your PRs
- Pair program on complex tasks
- Provide career guidance

Schedule weekly 1:1 with your mentor.

### Buddies

You'll also have a "buddy" (peer developer) for informal questions and pair programming.

---

## Common Pitfalls to Avoid

### ❌ Don't
- Push directly to `main` branch
- Skip writing tests
- Commit `.env` file or secrets
- Use `dd()` or `dump()` in production code
- Modify files outside your responsibility without discussion

### ✅ Do
- Write descriptive commit messages
- Keep branches small and focused
- Ask questions early
- Run tests locally before pushing
- Update documentation when changing functionality
- Review your own PR before requesting review

---

## Glossary

| Term | Definition |
|------|------------|
| **Inertia** | SPA bridge that connects Vue to Laravel |
| **HMR** | Hot Module Replacement - live reload during dev |
| **PINT** | Laravel's code style fixer (PSR-12) |
| **CRUD** | Create, Read, Update, Delete - basic operations |
| **ORM** | Object-Relational Mapping - maps DB tables to PHP objects |
| **Middleware** | Filters HTTP requests before they reach controllers |
| **Composer** | PHP dependency manager |
| **NPM** | JavaScript package manager |
| **Docker** | Containerization platform |
| **Tailwind** | Utility-first CSS framework |
| **shadcn/ui** | Re-usable UI component library |

---

## Support Resources

**Technical Issues**:
- Check [FAQ.md](FAQ.md)
- Search [GitHub Issues](https://github.com/your-org/Web-Architect/issues)
- Ask in #tech-support Slack channel

**HR/Admin**:
- Contact: hr@webarchitect.com
- Slack: #hr

**Escalation**:
If blocked for >2 days, escalate to your mentor or tech lead.

---

## Success Metrics

Your first 30 days:

| Week | Goal |
|------|------|
| 1 | Environment setup, basics understood, first PR merged |
| 2 | Regular PR flow, understands codebase structure |
| 3 | Completes feature independently, helps others |
| 4 | Comfortable in any module, mentors new hires |

---

## Code of Conduct Reminder

Be respectful, inclusive, and professional. Harassment or discrimination will not be tolerated.

See [CONTRIBUTING.md](CONTRIBUTING.md) for full code of conduct.

---

**Welcome aboard! 🎉**

We're excited to have you join the team. Don't hesitate to ask questions and contribute ideas.

---

**Last Updated**: 2026-04-07

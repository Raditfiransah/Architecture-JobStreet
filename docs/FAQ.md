# Frequently Asked Questions

Common questions and answers about the Web Architect project.

---

## General

### What is Web Architect?

Web Architect is a recruitment platform specifically designed for the architecture and design industry. It connects architects (arsitek) with companies (perusahaan) and clients through job postings, project proposals, and portfolio showcases.

### Who is this platform for?

Four main user types:
1. **Arsitek (Architects)** - Can create profiles, build portfolios, apply for jobs
2. **Perusahaan (Companies)** - Can post job vacancies (lowongan), manage applicants
3. **Client** - Can post projects, review proposals from architects
4. **Admin** - Platform administrators who moderate content and users

### Is this project open source?

Yes! Web Architect is open source under the MIT License. Feel free to use, modify, and contribute.

---

## Installation & Setup

### Can I run this without Docker?

Yes, see [DEVELOPMENT_GUIDE.md#option-2-local-development](DEVELOPMENT_GUIDE.md#option-2-local-development) for manual setup instructions. However, Docker is recommended for consistency and ease.

### What if I don't have Docker?

Install PHP 8.3+, Node.js 20+, MySQL 8.0, and Redis manually. Follow the prerequisites in [README.md](README.md).

### Docker containers won't start

Check Docker is running:
```bash
docker --version
docker-compose --version
docker ps
```

Try rebuilding:
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

View logs for errors:
```bash
docker-compose logs -f app
docker-compose logs -f nginx
```

---

## Authentication

### Can't login after registration

- Check your email for OTP verification code
- Complete OTP verification before trying to login
- Check spam folder if email not received
- Verify `.env` mail settings are correct

### OTP email not sending

Check mail configuration in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # For dev
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

For development, use **Mailtrap** (free) or **MailHog**:
```bash
# MailHog via Docker
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```

Then configure:
```env
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_ENCRYPTION=null
```

View emails at http://localhost:8025

---

## Database

### Migration failed

Check:
1. Database is running: `docker-compose ps db`
2. `.env` DB_* credentials are correct
3. No existing tables conflict (use `php artisan migrate:fresh` for fresh start)

### Can't connect to database

Verify `.env` settings:
```env
DB_CONNECTION=mysql
DB_HOST=db              # Docker service name
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

For local (non-Docker):
```env
DB_HOST=127.0.0.1
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## Frontend

### Vue components not rendering / blank page

1. Check browser console (F12) for errors
2. Ensure Vite dev server is running: `npm run dev`
3. Clear browser cache
4. Verify Inertia is properly installed:
   ```bash
   npm ls @inertiajs/vue3
   ```

### Vite HMR not working

- Confirm Vite is running: `npm run dev`
- Check port 5173 is not blocked: `netstat -an | grep 5173`
- Verify `.env` has:
  ```env
  VITE_URL=http://localhost:5173
  ```
- Disable browser extensions that block scripts

### Tailwind classes not applying

Check Tailwind is configured:
```bash
# Build CSS
npm run build

# Watch mode
npm run dev
```

Ensure `tailwind.config.js` includes content paths:
```js
content: [
  './resources/**/*.blade.php',
  './resources/**/*.js',
  './resources/**/*.vue',
],
```

---

## Performance

### Page loads very slowly

Possible causes:
1. **Database queries** - Check with Debugbar or Telescope
2. **N+1 problem** - Use eager loading: `->with('relation')`
3. **Large assets** - Check network tab, compress images
4. **Cache not warmed** - Run `php artisan config:cache`, `php artisan route:cache`
5. **Missing indexes** - Add database indexes on frequently queried columns

### High API response time

- Enable query log to identify slow queries
- Use `EXPLAIN` on slow queries
- Add indexes on `WHERE`, `JOIN`, `ORDER BY` columns
- Consider Redis caching for repeated queries:
  ```php
  $data = Cache::remember('key', 60, fn() => Model::all());
  ```

---

## Testing

### Tests fail randomly (flaky)

This is usually due to:
- Shared database state - ensure `use RefreshDatabase`
- Carbon time - use `Carbon::setTestNow()`
- Static properties - reset between tests

### Coverage too low

Aim for >85%. Identify uncovered code:
```bash
php artisan test --coverage-html coverage/
open coverage/index.html
```

Add tests for uncovered paths. Don't test getters/setters.

---

## Deployment

### 500 Internal Server Error after deployment

Check logs:
```bash
docker-compose logs app
docker-compose logs nginx
docker-compose exec app tail -f storage/logs/laravel.log
```

Common causes:
- **Cache issue**: `php artisan optimize:clear`
- **Missing .env**: Confirm `.env` exists and has correct values
- **Migrations pending**: `php artisan migrate --force`
- **Storage permissions**: `chmod -R 775 storage bootstrap/cache`

### SSL certificate not working

Verify:
1. Certbot ran successfully: `sudo certbot certificates`
2. Nginx config includes SSL `listen 443 ssl;`
3. Certificate files exist in `/etc/letsencrypt/live/yourdomain/`
4. Firewall allows ports 80 and 443

Renewal test:
```bash
sudo certbot renew --dry-run
```

---

## Specific Features

### How to create an admin user?

```bash
# Tinker
php artisan tinker
>>> App\Models\User::factory()->create(['role' => 'admin', 'email' => 'admin@example.com'])
```

Or use seeder:
```php
// database/seeders/UserSeeder.php
User::factory()->create([
    'email' => 'admin@webarchitect.com',
    'role' => 'admin',
    'is_verified' => true
]);
```

### How to reset a user's password?

```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'user@example.com')->first()
>>> $user->update(['password' => bcrypt('newpassword')])
```

Alternatively, use Laravel's password reset feature at `/lupa-password`.

---

## Development

### Linting doesn't work

Make sure Pint is installed:
```bash
composer install
./vendor/bin/pint
```

Format all files:
```bash
./vendor/bin/pint --test  # Dry run
./vendor/bin/pint         # Fix files
```

### Git push rejected (pre-commit hook)

The pre-commit hook runs `./vendor/bin/pint --dirty`. If it fails:

```bash
./vendor/bin/pint --dirty
git add .
git commit -m "Your message"
```

Or skip hook (not recommended):
```bash
git commit -m "Your message" --no-verify
```

### IDE autocomplete not working

Install PHP Intelephense (VS Code) or Laravel plugin (PHPStorm).

Regenerate IDE helper:
```bash
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate
php artisan ide-helper:meta
```

---

## Common Errors

### "Class 'Redis' not found"

Install Redis extension:
```bash
# Ubuntu
sudo apt-get install php-redis

# macOS
pecl install redis

# Then enable in php.ini:
extension=redis.so
```

### "Cannot modify header information"

Session start issue. Check:
- No output before `<?php` tag
- No whitespace after `?>` tag (or omit closing tag)
- Storage directory permissions

### "TokenMismatchException"

CSRF token mismatch. Ensure:
1. `@csrf` in all forms
2. Session driver configured
3. Clear session: `php artisan optimize:clear`

---

## Security

### Found a vulnerability?

**Do NOT create public issue.** Email security@webarchitect.com immediately.

We take security seriously and will respond within 48 hours.

### How to report security issue?

See [SECURITY.md](SECURITY.md) for our security policy and contact information.

---

## Contributing

### How to contribute?

See [CONTRIBUTING.md](CONTRIBUTING.md) for full guidelines.

Quick steps:
1. Fork the repo
2. Create feature branch
3. Make changes
4. Write tests
5. Submit PR

---

## Support

### Getting help

- **Documentation**: Check `docs/` folder
- **Issues**: Search [GitHub Issues](https://github.com/your-org/Web-Architect/issues)
- **Discussions**: Use [GitHub Discussions](https://github.com/your-org/Web-Architect/discussions)
- **Email**: support@webarchitect.com

### Bug report?

Use the bug report template in GitHub Issues with:
- Steps to reproduce
- Expected vs actual behavior
- Screenshots
- Environment details

---

## License

MIT License. See [LICENSE](LICENSE) file.

---

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Vue.js](https://vuejs.org)
- [Inertia.js](https://inertiajs.com)
- [Tailwind CSS](https://tailwindcss.com)
- [shadcn/ui](https://ui.shadcn.com)

---

**Last Updated**: 2026-04-07

# Security Policy

## Security Best Practices

Web Architect follows industry-standard security practices to protect user data and maintain system integrity.

---

## Reporting Security Vulnerabilities

**DO NOT** create public GitHub issues for security vulnerabilities.

**Email**: security@webarchitect.com

Please include:
- Description of vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if available)
- Your contact information

**PGP Public Key** (optional for encrypted reports):
```
[Add PGP key here if used]
```

**Response Timeline**:
- **24-48 hours**: Initial acknowledgment
- **7 days**: Patch or mitigation plan
- **30 days**: Public disclosure (after fix deployed)

---

## Authentication & Authorization

### Multi-Role Authentication

- Laravel's native authentication with session-based auth
- Four roles: `admin`, `client`, `perusahaan`, `arsitek`
- Role-based middleware: `role:{role}` on all dashboard routes
- Role helper methods on User model: `isAdmin()`, `isArsitek()`, etc.

### Email Verification

- OTP (One-Time Password) verification after registration
- 6-digit code expires in 10 minutes (configurable)
- Stored in `email_verification_codes` table
- Rate-limited resend: 3 attempts per minute

```bash
# .env configuration
OTP_LENGTH=6
OTP_EXPIRY_MINUTES=10
```

### Password Security

- Bcrypt hashing (Laravel default)
- Minimum password requirements: 8 characters
- Password reset tokens (expires in 60 minutes)
- Users can change password from settings page

### Session Security

```env
SESSION_DRIVER=redis  # or database
SESSION_SECURE_COOKIE=true  # HTTPS only (production)
SESSION_HTTP_ONLY=true  # Prevent XSS
SESSION_SAME_SITE=lax  # CSRF protection
```

---

## Data Protection

### SQL Injection Prevention

✅ **USE**: Eloquent ORM
```php
$user = User::where('email', $email)->first();
```

✅ **USE**: Query Builder with bindings
```php
DB::select('SELECT * FROM users WHERE email = ?', [$email]);
```

❌ **AVOID**: Raw queries with string concatenation
```php
DB::select("SELECT * FROM users WHERE email = '$email'"); // UNSAFE!
```

### XSS Protection

- All user input is escaped by default in Blade via `{{ $variable }}`
- Vue automatically escapes template interpolations: `{{ message }}`
- **Never** use `v-html` with unsanitized user input
- If `v-html` is necessary, sanitize with DOMPurify:
  ```bash
  npm install dompurify @types/dompurify
  ```

### CSRF Protection

- Laravel includes CSRF tokens in all forms (`@csrf` directive)
- Verify middleware enabled globally in `app/Http/Kernel.php`
- Inertia automatically includes CSRF token in requests

```php
// Verify middleware (always enabled)
\App\Http\Middleware\VerifyCsrfToken::class,
```

### Mass Assignment Protection

All Eloquent models must define `$fillable` or `$guarded`:

```php
class User extends Model
{
    protected $fillable = ['name', 'email']; // ALLOWED FIELDS
    // OR
    protected $guarded = []; // ALL FIELDS GUARDED (rarely used)
}
```

---

## File Upload Security

If implementing file uploads:

1. **Validate file type**:
   ```php
   $request->validate([
       'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
   ]);
   ```

2. **Store outside web root** OR use random filenames:
   ```php
   $path = $request->file('avatar')->store('avatars', 'public');
   // or
   $filename = Str::random(32).'.'.$request->avatar->extension();
   ```

3. **Use signed URLs** for restricted access:
   ```php
   Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5));
   ```

4. **Scan files** for malware (optional, use ClamAV)

---

## Input Validation

All user input must be validated:

### Form Requests (Recommended)

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'name' => ['required', 'string', 'max:100'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
        ];
    }
}
```

### Controller Validation

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed'
    ]);
    
    User::create($validated);
}
```

---

## Dependency Security

### Keep Dependencies Updated

```bash
# Check for known vulnerabilities
composer audit

# Update dependencies
composer update --no-dev
npm update

# npm audit
npm audit
npm audit fix
```

### Enable PHP Security Advisories

```bash
composer require --dev roave/security-advisories:dev-latest
```

This prevents installing packages with known vulnerabilities.

### Subscribe to Security Notices

- [Laravel Security Advisories](https://github.com/laravel/security-advisories)
- [PHP Security Advisories](https://github.com/FriendsOfPHP/security-advisories)
- [GitHub Security Alerts](https://github.com/features/security)

---

## Environment Configuration

### Never Commit Secrets

✅ **In `.env`**:
```env
DB_PASSWORD=secret123
MAIL_PASSWORD=secret456
```

❌ **NEVER in Code**:
```php
// BAD
const API_KEY = 'sk_live_123456';

// GOOD
$apiKey = config('services.stripe.secret');
```

`.env` must be in `.gitignore`:
```
/.env
/.env.local
/.env.*.local
```

### Production Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:generated-key

# Strong passwords
DB_PASSWORD=random-strong-password-32-chars
MAIL_PASSWORD=another-random-password

# HTTPS only
SESSION_SECURE_COOKIE=true
```

---

## HTTPS & SSL/TLS

### Enforce HTTPS

```php
// In AppServiceProvider boot()
if (app()->environment('production')) {
    \URL::forceScheme('https');
}
```

```nginx
# Nginx config
server {
    listen 443 ssl http2;
    server_name webarchitect.com;
    
    ssl_certificate /etc/ssl/certs/webarchitect.crt;
    ssl_certificate_key /etc/ssl/private/webarchitect.key;
    
    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
```

### HSTS (HTTP Strict Transport Security)

Already configured above - ensures browsers only use HTTPS.

---

## Logging & Monitoring

### Sensitive Data in Logs

**NEVER** log sensitive information:
```php
// BAD
Log::info('User login', ['password' => $password]);

// GOOD
Log::info('User login', ['user_id' => $user->id]);
```

### Configure Monolog

```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
    ],
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => env('LOG_LEVEL', 'critical'),
    ],
],
```

Log only necessary information at appropriate levels:
- `debug` - Development only
- `info` - General operations
- `warning` - Recoverable errors
- `error` - Production errors (send alerts)

---

## Rate Limiting

Currently applied on OTP resend endpoint:

```php
Route::post('/verifikasi-email/resend', ...)
    ->middleware('throttle:3,1');  // 3 attempts per minute
```

Apply to other sensitive endpoints:
```php
Route::middleware(['throttle:60,1'])->group(function () {
    // 60 requests per minute
});
```

---

## Access Control

### Route Middleware

All dashboard routes protected by:
```php
Route::middleware(['auth', 'verified', 'role:admin'])->group(...);
```

### Policies (To Be Implemented)

Create policies for resource authorization:

```bash
php artisan make:policy LowonganPolicy --model=Lowongan
```

```php
class LowonganPolicy
{
    public function view(User $user, Lowongan $lowongan): bool
    {
        return $user->id === $lowongan->user_id;
    }
    
    public function delete(User $user, Lowongan $lowongan): bool
    {
        return $user->id === $lowongan->user_id || $user->isAdmin();
    }
}
```

---

## Database Security

### Use Prepared Statements

✅ Eloquent and Query Builder automatically use prepared statements.

### Never Store Plain Text Passwords

Laravel automatically hashes passwords via `$casts`:
```php
protected $casts = [
    'password' => 'hashed',
];
```

### Encrypt Sensitive Data

```php
use Illuminate\Support\Facades\Crypt;

$encrypted = Crypt::encryptString($sensitiveData);
$decrypted = Crypt::decryptString($encrypted);
```

Add to model:
```php
protected $encrypted = ['ssn', 'bank_account'];
```

---

## API Security (Future)

When adding API endpoints:

1. **Use Laravel Sanctum** for token-based auth:
   ```php
   Route::middleware('auth:sanctum')->get('/api/user', ...);
   ```

2. **Rate limit APIs**:
   ```php
   Route::middleware('throttle:60,1')->group(...);
   ```

3. **Validated inputs** always

4. **CORS configuration**:
   ```php
   // config/cors.php
   'allowed_origins' => ['https://your-frontend.com'],
   'allowed_methods' => ['*'],
   ```

---

## Regular Security Tasks

### Weekly
- Review Laravel security advisories
- Check for dependency updates with known vulnerabilities

### Monthly
- Update dependencies: `composer update`, `npm update`
- Review access logs for suspicious activity
- Check failed login attempts

### Quarterly
- Security audit of codebase
- Penetration testing (consider hiring ethical hacker)
- Review and update access controls

---

## Incident Response Plan

If security breach is detected:

1. **Contain** - Isolate affected systems
2. **Assess** - Identify scope and impact
3. **Notify** - Inform stakeholders (users if data breached)
4. **Fix** - Patch vulnerability
5. **Recover** - Restore from clean backups if needed
6. **Document** - Write incident report
7. **Prevent** - Implement measures to prevent recurrence

---

## Key Security Contacts

- **Security Email**: security@webarchitect.com
- **Reports handled by**: CTO / Security Lead
- **PGP Key**: [Link if used]

---

## Additional Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP: The Right Way - Security](https://phptherightway.com/#security)
- [Tailwind CSS Security](https://tailwindcss.com/docs/security)

---

**Last Updated**: 2026-04-07

**Next Review**: 2026-07-07

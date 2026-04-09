# Development Guide

Comprehensive guide for developers working on the Web Architect project.

## Table of Contents

- [Getting Started](#getting-started)
- [Development Environment Setup](#development-environment-setup)
- [Coding Standards](#coding-standards)
- [Git Workflow](#git-workflow)
- [Testing Strategy](#testing-strategy)
- [Frontend Development](#frontend-development)
- [Backend Development](#backend-development)
- [Database Management](#database-management)
- [Debugging Tips](#debugging-tips)
- [Common Issues](#common-issues)

---

## Getting Started

### Prerequisites

- PHP 8.3+ with extensions: pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip
- Composer 2.x
- Node.js 20+
- Docker & Docker Compose (recommended)
- Git
- VS Code or PHPStorm (recommended IDEs)

---

## Development Environment Setup

### Option 1: Docker Development (Recommended)

```bash
# 1. Clone the repository
git clone <repository-url>
cd Web-Architect

# 2. Copy environment file
cp .env.example .env

# 3. Update Docker environment variables if needed
# DB_HOST=db (default)
# REDIS_HOST=redis (default)

# 4. Build and start containers
docker-compose up -d

# 5. Install PHP dependencies (inside container)
docker-compose exec app composer install

# 6. Install Node dependencies
docker-compose exec node npm install

# 7. Generate app key
docker-compose exec app php artisan key:generate

# 8. Run migrations
docker-compose exec app php artisan migrate

# 9. Install Vite dependencies and build
docker-compose exec node npm run dev

# 10. Access application
# Main app: http://localhost:8000
# Vite HMR: http://localhost:5173
# phpMyAdmin: http://localhost:8081

# 11. (Optional) Seed database with sample data
docker-compose exec app php artisan db:seed --class=Database\\Seeders\\UserSeeder
```

### Option 2: Local Development

```bash
# 1. Install PHP dependencies
composer install

# 2. Install Node dependencies
npm install

# 3. Configure .env
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=web_architect
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 5. Create database
mysql -u root -p -e "CREATE DATABASE web_architect;"

# 6. Run migrations
php artisan migrate

# 7. Seed database (optional)
php artisan db:seed

# 8. Build frontend assets
npm run build

# 9. Start development servers
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Access http://localhost:8000
```

---

## Coding Standards

### PHP Standards (Laravel Pint)

This project uses **Laravel Pint** which enforces **PSR-12** coding standards.

```bash
# Check code style
./vendor/bin/pint --test

# Auto-fix issues
./vendor/bin/pint

# Run automatically via Git hook (pre-commit)
./vendor/bin/pint --dirty
```

#### PHP Code Example

✅ **CORRECT**:
```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;

final class LowonganController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $lowongan = Lowongan::all();
        
        return view('lowongan.index', compact('lowongan'));
    }
}
```

❌ **INCORRECT**:
```php
<?php
namespace App\Http\Controllers\Perusahaan;

class lowonganController extends Controller{
    public function index(){
        $lowongan = Lowongan::all();
        return view('lowongan.index', compact('lowongan'));
    }
}
```

### JavaScript/Vue Standards

Based on **Vue Best Practices** skill, use:
- **Composition API** with `<script setup>`
- **TypeScript** (optional but encouraged)
- **ESLint** + **Prettier** for formatting

Create `.eslintrc.js` in project root:

```js
module.exports = {
  root: true,
  env: {
    browser: true,
    es2021: true,
    node: true,
  },
  extends: [
    'eslint:recommended',
    'plugin:vue/vue3-recommended',
    '@vue/typescript',
    '@vue/prettier',
    '@vue/prettier/@typescript-vue',
  ],
  parser: 'vue-eslint-parser',
  parserOptions: {
    ecmaVersion: 2021,
    parser: '@typescript-eslint/parser',
  },
  rules: {
    // Custom rules
    'vue/multi-word-component-names': 'off',
  },
}
```

```bash
# Install ESLint
npm install -D eslint eslint-plugin-vue @typescript-eslint/parser @typescript-eslint/eslint-plugin

# Run lint
npx eslint resources/js --fix
```

### Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| PHP Classes | PascalCase | `UserController`, `ArsitekProfile` |
| PHP Methods | camelCase | `getProfile()`, `store()` |
| PHP Properties | snake_case | `$user_id`, `$created_at` |
| PHP Constants | UPPER_SNAKE_CASE | `STATUS_ACTIVE` |
| Vue Components | PascalCase | `ProfileCard.vue`, `Breadcrumb.vue` |
| Vue Methods | camelCase | `handleSubmit()`, `fetchData()` |
| Database Tables | snake_case (plural) | `users`, `arsitek_profiles`, `lowongan` |
| Database Columns | snake_case | `user_id`, `created_at`, `is_verified` |
| Routes | snake_case (dot notation) | `profile.client`, `lowongan.index` |
| Enums/Status | SCREAMING_SNAKE | `status_pekerjaan` values: ['FT', 'PT'] |
| Config Files | snake_case | `database.php`, `cache.php` |
| Helper Functions | snake_case | `format_currency()`, `send_otp()` |

### File Organization

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Role-specific controllers
│   │   ├── Arsitek/
│   │   ├── Auth/
│   │   ├── Client/
│   │   ├── Perusahaan/
│   │   └── Public/
│   ├── Middleware/
│   └── Resources/
├── Models/
├── Services/               # Business logic services (create as needed)
└── View/                   # View composers (if needed)

resources/js/
├── Components/
│   ├── UI/                # shadcn/ui components
│   ├── Public/            # Components for public pages
│   ├── Private/           # Components for dashboard
│   ├── Forms/             # Reusable form components
│   └── Index.vue          # Component index/barrel
├── Composables/            # Vue composables (to be created)
├── Layouts/
├── Pages/
├── lib/                   # Third-party libraries
└── utils/                 # Utility functions

database/
├── migrations/
├── seeders/
├── factories/
└── migrations_legacy/     # Old migrations (if migrating)
```

---

## Git Workflow

### Branch Strategy

We follow **GitHub Flow** (simplified):

```
main (production-ready)
  ↓
feature/feature-name (for new features)
  ↓
bugfix/issue-description (for fixes)
  ↓
hotfix/critical-issue (for production emergencies)
```

### Commit Convention

Use **Conventional Commits** format:

```
<type>(<scope>): <description>

[body]

[footer]
```

**Types**:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Formatting changes (no code change)
- `refactor`: Code restructuring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples**:
```
feat(auth): add OTP email verification

- Implement otp verification controller
- Add otp field to user registration flow
- Create email verification codes table

Closes #123
```

```
fix(client): correct project status update

Fixed issue where project status wouldn't update
when client clicks "close project" button.

Fixes #456
```

### Pull Request Process

1. Create feature branch from `main`:
   ```bash
   git checkout main
   git pull origin main
   git checkout -b feature/new-feature
   ```

2. Make changes, write tests, ensure code passes lint:
   ```bash
   ./vendor/bin/pint
   npm run lint
   php artisan test
   ```

3. Commit with clear message:
   ```bash
   git add .
   git commit -m "feat(auth): add two-factor authentication"
   ```

4. Push to remote:
   ```bash
   git push origin feature/new-feature
   ```

5. Open Pull Request on GitHub:
   - Fill PR template
   - Link related issue
   - Request code review from team
   - Ensure CI passes

6. Address review comments
7. Squash merge to `main`

### Code Review Checklist

- [ ] Code follows PSR-12 (checked by Pint)
- [ ] No debug code or console.log() left
- [ ] Tests added/updated and passing
- [ ] No sensitive data in code (API keys, passwords)
- [ ] Database queries optimized (N+1 avoided)
- [ ] Migrations included (if schema changes)
- [ ] Routes properly named and documented
- [ ] User input validated
- [ ] Authorization checks in place
- [ ] No breaking changes without migration path

---

## Testing Strategy

### Test Types

| Type | Framework | Location | Purpose |
|------|-----------|----------|---------|
| Unit Tests | PHPUnit | `tests/Unit/` | Test individual classes/methods |
| Feature Tests | PHPUnit | `tests/Feature/` | Test HTTP endpoints |
| Browser Tests | Dusk (optional) | `tests/Browser/` | E2E with real browser |
| Frontend Tests | Vitest/Cypress | `resources/js/tests/` | Vue component tests |

### Running Tests

```bash
# All tests
php artisan test

# With coverage
php artisan test --coverage

# Specific test file
php artisan test tests/Feature/AuthTest.php

# Specific test method
php artisan test --filter=test_user_can_login

# Only unit tests
php artisan test --testsuite=Unit

# Only feature tests
php artisan test --testsuite=Feature

# Parallel testing (if configured)
php artisan test --parallel
```

### Test Database

Tests use SQLite in-memory database by default (configure in `phpunit.xml`):

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### Writing Tests

#### Feature Test Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LowonganTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_lowongan_index(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('lowongan.index'));
        
        $response->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Public/Lowongan/Index')
            );
    }
}
```

#### Unit Test Example

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /** @test */
    public function user_can_check_if_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($client->isAdmin());
    }
}
```

#### Vue Component Test (Vitest)

```javascript
import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import PrimaryButton from '@/Components/UI/ui/button/Button.vue'

describe('PrimaryButton', () => {
  it('renders correctly', () => {
    const wrapper = mount(PrimaryButton, {
      slots: {
        default: 'Click me'
      }
    })
    
    expect(wrapper.text()).toBe('Click me')
    expect(wrapper.classes()).toContain('bg-primary')
  })
})
```

---

## Frontend Development

### Vue Component Structure

Always use **Composition API** with `<script setup>`:

```vue
<template>
  <div class="space-y-4">
    <h1>{{ title }}</h1>
    <button @click="handleSubmit">Submit</button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/Components/UI/ui/button'

// Props
const props = defineProps({
  title: {
    type: String,
    required: true
  }
})

// Emits
const emit = defineEmits(['submitted'])

// Reactive state
const form = useForm({
  name: '',
  email: ''
})

// Computed
const isSubmitting = computed(() => form.processing)

// Methods
const handleSubmit = () => {
  form.post(route('users.store'), {
    onSuccess: () => {
      emit('submitted')
      form.reset()
    }
  })
}
</script>

<style scoped>
h1 {
  @apply text-2xl font-bold text-gray-900;
}
</style>
```

### Component Design Guidelines

1. **Keep components small** - One responsibility per component
2. **Use slots** for flexible content injection
3. **Props down, events up** for parent-child communication
4. **Composables** for shared logic (to be implemented)
5. **TypeScript** for better type safety (optional but recommended)

### Styling with Tailwind

```html
<!-- Good -->
<button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
  Submit
</button>

<!-- Bad (don't use custom classes unless essential) -->
<button class="my-custom-button">Submit</button>
```

Use Tailwind's `@apply` for reusable patterns:
```css
.btn-primary {
  @apply px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90;
}
```

### Inertia Page Structure

```vue
<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  user: Object,
  stats: Object
})
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Dashboard" />
    
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <!-- Page content -->
    </div>
  </AuthenticatedLayout>
</template>
```

---

## Backend Development

### Controller Structure

```php
<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lowongan;
use App\Http\Requests\LowonganRequest;

final class LowonganController extends Controller
{
    public function __construct(
        private readonly Lowongan $lowongan
    ) {}
    
    public function index(): \Illuminate\View\View
    {
        $lowongan = $this->lowongan
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return inertia('Perusahaan/Lowongan/Index', [
            'lowongan' => $lowongan
        ]);
    }
    
    public function create(): \Illuminate\View\View
    {
        return inertia('Perusahaan/Lowongan/Create');
    }
    
    public function store(LowonganRequest $request): \Illuminate\Http\RedirectResponse
    {
        $lowongan = $this->lowongan->create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);
        
        return redirect()
            ->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan berhasil dibuat');
    }
}
```

### Form Requests for Validation

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LowonganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'posisi' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:Full Time,Part Time,Freelance,Contract,Internship'],
            'gaji' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'syarat' => ['required', 'array'],
            'tanggung_jawab' => ['required', 'array'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'posisi.required' => 'Posisi harus diisi',
            // ...
        ];
    }
}
```

### Service Classes (Business Logic)

Create `app/Services/` directory for complex logic:

```php
<?php

namespace App\Services;

use App\Models\Lowongan;
use App\Notifications\LowonganPosted;

final class LowonganService
{
    public function __construct(
        private readonly Lowongan $lowongan
    ) {}
    
    public function createLowongan(array $data, int $userId): Lowongan
    {
        $lowongan = $this->lowongan->create([
            ...$data,
            'user_id' => $userId
        ]);
        
        // Dispatch notification
        // $lowongan->user->notify(new LowonganPosted($lowongan));
        
        return $lowongan;
    }
    
    public function updateStatus(int $id, string $status): bool
    {
        return $this->lowongan->where('id', $id)->update([
            'status' => $status,
            'status_updated_at' => now()
        ]);
    }
}
```

---

## Database Management

### Creating Migrations

```bash
php artisan make:migration create_table_name_table
php artisan make:migration add_column_to_table_name_table --table=table_name
```

Migration structure:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('created_at');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

### Seeding Data

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lowongan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call other seeders
        $this->call([
            UserSeeder::class,
            LowonganSeeder::class,
        ]);
    }
}
```

Individual seeder:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        
        // Create admin
        User::factory()->create([
            'email' => 'admin@webarchitect.com',
            'role' => 'admin',
            'is_verified' => true
        ]);
    }
}
```

---

## Debugging Tips

### Laravel Debugging

1. **Enable Debug Mode** (development only):
   ```env
   APP_DEBUG=true
   ```

2. **Laravel Telescope** (install for detailed query/monitoring):
   ```bash
   composer require laravel/telescope
   php artisan telescope:install
   php artisan migrate
   ```

3. **Debug queries**:
   ```php
   // In controller/tinker
   DB::listen(function ($query) {
       logger($query->sql, $query->bindings, $query->time);
   });
   ```

4. **Dump and die**:
   ```php
   dd($variable);
   dump($variable); // Continue execution
   ```

5. **Query log**:
   ```php
   DB::enableQueryLog();
   // Run queries...
   dd(DB::getQueryLog());
   ```

### Frontend Debugging

1. **Vue DevTools** - Install browser extension
2. **Inertia Page Inspector** - Use browser console:
   ```javascript
   // Inspect current page props
   console.log(usePage().props)
   ```
3. **Vite HMR** - Hot Module Replacement for instant updates
4. **Network tab** - Check X-Inertia requests

### Docker Debugging

```bash
# Check container status
docker-compose ps

# View logs
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db

# SSH into container
docker-compose exec app bash

# Restart services
docker-compose restart app
docker-compose restart nginx

# Clear all and rebuild
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

### Common Debug Scenarios

**Issue**: 500 Internal Server Error, no details
```bash
# Check Laravel log
tail -f storage/logs/laravel.log

# Or with Pail
php artisan pail
```

**Issue**: 404 Not Found on refresh (SPA routes)
```nginx
# Ensure nginx config has try_files
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**Issue**: Asset not loading
```bash
# Rebuild assets
npm run build

# Or dev server
npm run dev

# Clear Laravel caches
php artisan optimize:clear
```

---

## Common Issues

### 1. "Class 'Redis' not found"

**Solution**:
```bash
# Install PHP Redis extension
sudo apt-get install php-redis
# or
pecl install redis

# Enable in php.ini
extension=redis.so

# Restart PHP-FPM
```

### 2. Migration already exists

**Solution**:
```bash
# Check migration status
php artisan migrate:status

# If migration was run but file deleted, manually mark as migrated
# OR rollback and re-run if safe
```

### 3. Vite HMR not working

**Solution**:
```bash
# Ensure Vite dev server is running
npm run dev

# Check Vite is accessible at http://localhost:5173
# In .env:
VITE_URL=http://localhost:5173

# Clear browser cache and reload
```

### 4. Permission errors on storage/

**Solution**:
```bash
# Set correct permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Ubuntu
# or
chown -R $USER:www-data storage bootstrap/cache

# Docker
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### 5. Queue jobs not processing

**Solution**:
```bash
# Start queue worker
php artisan queue:work

# Check failed jobs
php artisan queue:failed
php artisan queue:retry all

# Using horizon (if installed)
php artisan horizon
```

### 6. "TokenMismatchException" on form submit

**Solution**:
- Ensure `@csrf` directive in all forms
- Check session driver is configured properly
- Clear session: `php artisan optimize:clear`

---

## Code Completion Checklist

When completing a feature, verify:

- [ ] Feature works as expected (test manually)
- [ ] Code follows PSR-12 (run pint)
- [ ] Frontend lint passes (run eslint if configured)
- [ ] Tests added/updated (target >85% coverage)
- [ ] Migrations included (if schema changed)
- [ ] Database seeders updated (if needed)
- [ ] Documentation updated (README, API docs)
- [ ] No sensitive data in code
- [ ] Git commit messages follow convention
- [ ] Pull request created with clear description

---

## Key Contacts & Resources

- **Team Lead**: [Name, Email, Slack]
- **Frontend Expert**: [Name]
- **Backend Expert**: [Name]
- **DevOps Support**: [Name]
- **Project Management**: [Tool name, link]

### Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Guide](https://vuejs.org/guide/introduction.html)
- [Inertia.js Docs](https://inertiajs.com/)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [PHPStan](https://phpstan.org/) - Static analysis

---

**Last Updated**: 2026-04-07

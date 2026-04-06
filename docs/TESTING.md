# Testing Guide

Comprehensive guide for testing the Web Architect application.

---

## Table of Contents

- [Testing Strategy](#testing-strategy)
- [Test Types](#test-types)
- [Running Tests](#running-tests)
- [Writing Tests](#writing-tests)
- [Code Coverage](#code-coverage)
- [Mocking & Factories](#mocking--factories)
- [Browser/Feature Testing](#browserfeature-testing)
- [Performance Testing](#performance-testing)
- [Continuous Integration](#continuous-integration)
- [Troubleshooting](#troubleshooting)

---

## Testing Strategy

We follow the **Testing Pyramid**:

```
                    [E2E Tests]        (~5%)
                       /    \
         [Integration]         [Feature Tests]    (~25%)
               /                      \
 [Unit Tests]------------------[Browser Tests] (~70%)
```

- **Unit Tests (70%)** - Test individual functions/methods in isolation
- **Feature Tests (25%)** - Test HTTP endpoints and user workflows
- **E2E/Browser Tests (5%)** - Test complete user journeys (optional)

---

## Test Types

### 1. Unit Tests

Location: `tests/Unit/`

Test single classes or methods in isolation without external dependencies.

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /** @test */
    public function user_can_check_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($client->isAdmin());
    }
    
    /** @test */
    public function user_can_get_dashboard_route_based_on_role(): void
    {
        $user = new User(['role' => 'arsitek']);
        $this->assertEquals('arsitek.profile', $user->dashboardRoute());
    }
}
```

### 2. Feature Tests

Location: `tests/Feature/`

Test HTTP endpoints, database interactions, and middleware.

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        
        $response->assertRedirect('/profile/client'); // Redirects to dashboard
        $this->assertAuthenticated();
    }
    
    /** @test */
    public function user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
```

### 3. Inertia Response Testing

Test that Inertia pages render correctly:

```php
/** @test */
public function lowongan_index_returns_inertia_page(): void
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->get(route('lowongan.index'));
    
    $response->assertInertia(fn ($page) => $page
        ->component('Public/Lowongan/Index')
        ->has('lowongan') // Check props
    );
}
```

---

## Running Tests

### All Tests

```bash
php artisan test
```

### With Coverage

```bash
php artisan test --coverage
```

Output shows coverage percentage by file. Minimum requirement: **>85%** for new code.

### Specific Test Suite

```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

### Specific Test File

```bash
php artisan test tests/Feature/AuthTest.php
```

### Specific Test Method

```bash
php artisan test --filter=test_user_can_login
```

### Parallel Testing

```bash
php artisan test --parallel
```

Configure parallel processes in `phpunit.xml`:
```xml
<php>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

---

## Writing Tests

### Test Case Structure

All tests extend `Tests\TestCase` (base class with helpers):

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // Fresh DB for each test
    
    /** @test */
    public function it_does_something(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act
        $response = $this->actingAs($user)
            ->get(route('home'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }
}
```

### Traits for Test Setup

| Trait | Purpose |
|-------|---------|
| `RefreshDatabase` | Migrates fresh DB for each test |
| `DatabaseMigrations` | Runs migrate:rollback before each test |
| `WithoutMiddleware` | Disables middleware for testing |
| `WithFaker` | Provides `$this->faker` instance |
| `InteractsWithViews` | Test view rendering |

### Database Testing

Use `RefreshDatabase` trait for clean DB state:

```php
use RefreshDatabase;

/** @test */
public function it_creates_a_user(): void
{
    $count = User::count();
    
    User::factory()->create();
    
    $this->assertDatabaseCount('users', $count + 1);
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com'
    ]);
}
```

---

## Code Coverage

### Requirements

- **New code must have >85% coverage**
- Critical security code: 100% coverage
- Focus on business logic, not getters/setters

### Viewing Coverage

```bash
php artisan test --coverage
```

Detailed HTML report:
```bash
php artisan test --coverage-html coverage-report/
open coverage-report/index.html
```

### Excluding Files from Coverage

Create `phpunit.xml` filter:
```xml
<filter>
    <whitelist>
        <directory suffix=".php">./app</directory>
        <exclude>
            <directory suffix=".php">./app/Models</directory>
            <file>./app/helpers.php</file>
        </exclude>
    </whitelist>
</filter>
```

---

## Mocking & Factories

### Model Factories

Located in `database/factories/`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password123'),
            'role' => $this->faker->randomElement(['client', 'perusahaan', 'arsitek']),
            'is_verified' => true,
            'avatar_url' => null,
        ];
    }
    
    // State modifiers
    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
    
    public function unverified(): static
    {
        return $this->state(['is_verified' => false]);
    }
}
```

### Using Factories

```php
// Create single
$user = User::factory()->create();

// Create with specific attributes
$admin = User::factory()->create(['role' => 'admin']);

// Create multiple
$users = User::factory(10)->create();

// With relationships
$user = User::factory()
    ->has(ArsitekProfile::factory())
    ->create();

// States
$user = User::factory()->unverified()->create();
$admin = User::factory()->admin()->create();
```

### Mocking External Services

```php
use Illuminate\Support\Facades\Http;

/** @test */
public function it_calls_external_api(): void
{
    Http::fake([
        'api.example.com/*' => Http::response(['data' => 'mocked'], 200)
    ]);
    
    // Your code that calls external API
    $response = SomeService::callApi();
    
    $this->assertEquals('mocked', $response['data']);
}
```

---

## Browser/Feature Testing (Optional)

### Laravel Dusk

For full browser E2E tests:

```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

Example Dusk test:

```php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/profile/client')
                ->assertAuthenticated();
        });
    }
}
```

Run Dusk tests:
```bash
php artisan dusk
```

---

## Performance Testing

### Query Counting

Ensure no N+1 queries:

```php
/** @test */
public function it_loads_without_n_plus_one_queries(): void
{
    $lowongan = Lowongan::factory(10)->create();
    
    \DB::enableQueryLog();
    
    $response = $this->get(route('lowongan.index'));
    
    $queries = \DB::getQueryLog();
    $this->assertLessThan(5, count($queries)); // Should be < 5 queries
}
```

### Laravel Debugbar (Development)

```bash
composer require --dev barryvdh/laravel-debugbar
```

Shows query count, execution time, and memory usage.

---

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  php-tests:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: testing
        options: --health-cmd="mysqladmin ping" --health-interval=10s
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: pdo_mysql
          
      - name: Install Composer dependencies
        run: composer install --no-interaction --prefer-dist
        
      - name: Copy .env
        run: cp .env.example .env
        
      - name: Generate key
        run: php artisan key:generate
        
      - name: Migrate database
        run: php artisan migrate --force
        
      - name: Run tests
        run: vendor/bin/phpunit --coverage-text
        
      - name: Upload coverage to Codecov
        uses: codecov/codecov-action@v2
```

---

## Performance Testing Tips

### 1. Use Clock Mocking

```php
use Carbon\Carbon;

/** @test */
public function it_checks_expiration_date(): void
{
    Carbon::setTestNow('2026-04-07 10:00:00');
    
    // Your test logic
    
    Carbon::setTestNow(); // Reset
}
```

### 2. Test Large Datasets

```php
/** @test */
public function it_handles_large_dataset(): void
{
    Lowongan::factory(1000)->create();
    
    $response = $this->get(route('lowongan.index'));
    $response->assertStatus(200);
}
```

### 3. Memory Usage

```php
/** @test */
public function it_does_not_leak_memory(): void
{
    $initial = memory_get_usage();
    
    // Run code multiple times
    for ($i = 0; $i < 100; $i++) {
        $user = User::factory()->create();
    }
    
    $final = memory_get_usage();
    $this->assertLessThan($initial * 3, $final);
}
```

---

## Troubleshooting

### Tests fail randomly (flaky tests)

**Cause**: Shared state between tests, timing issues

**Solution**: Ensure `RefreshDatabase` or `DatabaseMigrations` trait, avoid static properties

### Tests too slow

**Cause**: Too many factory creates, no database transactions

**Solution**:
- Use `RefreshDatabase` (uses transactions)
- Reuse model instances when possible
- Use `withoutMiddleware()` if middleware not needed

### "No connection could be made because the target machine actively refused it"

**Cause**: Database not running

**Solution**: Start MySQL/Docker, check `.env` DB_* settings

### "Class not found" errors

**Solution**:
```bash
composer dump-autoload
```

### Coverage report not generating

**Solution**: Install Xdebug or PCOV:
```bash
pecl install xdebug
# or
pecl install pcov
```

Then enable in `php.ini`:
```ini
xdebug.mode=coverage
```

---

## Test Data Management

### Seeders for Tests

Create specific seeders for test data:

```php
// database/seeders/TestUserSeeder.php
class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create();
        User::factory()->count(5)->create();
    }
}
```

In tests:
```php
/** @test */
public function test_with_prerecorded_data(): void
{
    $this->seed(TestUserSeeder::class);
    
    $this->assertDatabaseCount('users', 6);
}
```

---

## Best Practices

1. **One assertion per test** (roughly)
2. **Test names should be descriptive**: `test_authenticated_user_can_view_dashboard`
3. **Use factories, not manual arrays** - Keeps data consistent
4. **Test behavior, not implementation** - Focus on outcomes, not internal details
5. **Arrange-Act-Assert** pattern:
   ```php
   // Arrange
   $user = User::factory()->create();
   
   // Act
   $response = $this->actingAs($user)->get('/dashboard');
   
   // Assert
   $response->assertStatus(200);
   ```
6. **Use helpers**: `assertDatabaseHas()`, `assertRedirect()`, `assertInvalid()`
7. **Clean up after tests** - Use traits (`RefreshDatabase`) automatically

---

## Checklist Before Committing

- [ ] All tests pass: `php artisan test`
- [ ] Coverage requirement met: `php artisan test --coverage`
- [ ] No failing tests: `./vendor/bin/pint --test`
- [ ] Lint passes (if configured): `npm run lint`
- [ ] Tests added for new features
- [ ] Tests updated for changed behavior

---

## Common Test Helpers

Create `tests/Support/Helpers.php`:

```php
<?php

namespace Tests\Support;

function create_user_with_role(string $role): User
{
    return User::factory()->create(['role' => $role]);
}

function assert_inertia_component($response, string $component): void
{
    $response->assertInertia(fn ($page) => $page
        ->component($component)
    );
}
```

---

**Last Updated**: 2026-04-07

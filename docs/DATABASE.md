# Database Documentation

## Database System

- **Type**: MySQL 8.0
- **Charset**: utf8mb4
- **Collation**: utf8mb4_unicode_ci
- **Connection**: Through Docker (recommended) or direct TCP

## Connection Configuration

### Environment Variables (.env)

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=db              # Use "127.0.0.1" for local (non-Docker)
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

For Docker setup, ensure the `db` service is running:
```bash
docker-compose up -d db
```

### Testing Database

```env
DB_CONNECTION=mysql
DB_DATABASE=testing
```

## Database Schema

### Complete ERD

```mermaid
erDiagram
    users ||--o{ arsitek_profiles : "has one"
    users ||--o{ company_profiles : "has one"
    users ||--o{ email_verification_codes : "has many"
    users ||--o{ password_reset_tokens : "has one"
    users ||--o{ sessions : "has many"
    
    users {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string role
        boolean is_active
        boolean is_verified
        string avatar_url
        string location
        string phone
        string remember_token
        timestamps
    }
    
    arsitek_profiles {
        bigint id PK
        bigint user_id FK
        string first_name
        string last_name
        string status_pekerjaan
        boolean is_student
        string location
        string school
        string degree_type
        json preferences
        string resume_url
        string portfolio_url
        timestamps
    }
    
    company_profiles {
        bigint id PK
        bigint user_id FK
        string company_name
        string company_website
        string company_logo_url
        text company_desc
        timestamp verified_at
        string industry
        string company_size
        string location
        timestamps
    }
    
    lowongan {
        bigint id PK
        string posisi
        string perusahaan
        string kota
        enum tipe
        string gaji
        string inisial
        decimal rating
        text deskripsi
        json syarat
        json tanggung_jawab
        timestamps
    }
    
    email_verification_codes {
        bigint id PK
        bigint user_id FK
        string code
        boolean is_used
        timestamp used_at
        timestamp expired_at
        timestamps
    }
    
    password_reset_tokens {
        string email PK
        string token
        timestamp created_at
    }
    
    sessions {
        string id PK
        bigint user_id FK
        string ip_address
        text user_agent
        longtext payload
        integer last_activity
    }
    
    cache {
        string key PK
        mediumtext value
        bigint expiration
    }
    
    cache_locks {
        string key PK
        string owner
        bigint expiration
    }
    
    jobs {
        bigint id PK
        string queue
        longtext payload
        unsigned tinyint attempts
        unsigned int reserved_at
        unsigned int available_at
        unsigned int created_at
    }
    
    job_batches {
        string id PK
        string name
        integer total_jobs
        integer pending_jobs
        integer failed_jobs
        longtext failed_job_ids
        mediumtext options
        integer cancelled_at
        integer created_at
        integer finished_at
    }
    
    failed_jobs {
        bigint id PK
        string uuid UK
        text connection
        text queue
        longtext payload
        longtext exception
        timestamp failed_at
    }

    users ||--o{ password_reset_tokens : "has one"
    users ||--|| arsitek_profiles : "has one"
    users ||--|| company_profiles : "has one"
```

---

## Tables Reference

### 1. `users`

Core user authentication and basic profile data.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AI | User ID |
| name | VARCHAR(100) | NOT NULL | Full name |
| email | VARCHAR(150) | UNIQUE, NOT NULL | Email address |
| email_verified_at | TIMESTAMP | NULLABLE | Verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| role | ENUM('admin','client','perusahaan','arsitek') | DEFAULT 'client' | User role |
| is_active | BOOLEAN | DEFAULT true | Account status |
| is_verified | BOOLEAN | DEFAULT false | Email verified flag |
| avatar_url | VARCHAR(500) | NULLABLE | Profile picture URL |
| location | VARCHAR(255) | NULLABLE | User location |
| phone | VARCHAR(20) | NULLABLE | Phone number |
| remember_token | VARCHAR(100) | NULLABLE | "Remember me" token |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Record creation |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last update |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `email`
- INDEX: `email_verified_at`

**Relationships:**
- Has One: `arsitek_profile`
- Has One: `company_profile`
- Has Many: `email_verification_codes`
- Has One: `password_reset_token`
- Has Many: `sessions`

---

### 2. `arsitek_profiles`

Extended profile for architects.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AI | Profile ID |
| user_id | BIGINT | FK→users.id, ON DELETE CASCADE | Related user |
| first_name | VARCHAR(100) | NULLABLE | First name |
| last_name | VARCHAR(100) | NULLABLE | Last name |
| status_pekerjaan | VARCHAR(100) | NULLABLE | Employment status |
| is_student | BOOLEAN | DEFAULT false | Student flag |
| location | VARCHAR(200) | NULLABLE | Location |
| school | VARCHAR(200) | NULLABLE | School/university |
| degree_type | VARCHAR(100) | NULLABLE | Degree/certification |
| preferences | JSON | NULLABLE | Job preferences |
| resume_url | VARCHAR(500) | NULLABLE | Resume file URL |
| portfolio_url | VARCHAR(500) | NULLABLE | Portfolio URL |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Record creation |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last update |

**Indexes:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `users(id)` ON DELETE CASCADE
- INDEX: `user_id`

---

### 3. `company_profiles`

Extended profile for companies.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AI | Profile ID |
| user_id | BIGINT | FK→users.id, ON DELETE CASCADE | Related user |
| company_name | VARCHAR(150) | NOT NULL | Company name |
| company_website | VARCHAR(500) | NULLABLE | Company website |
| company_logo_url | VARCHAR(500) | NULLABLE | Logo URL |
| company_desc | TEXT | NULLABLE | Company description |
| verified_at | TIMESTAMP | NULLABLE | Verification timestamp |
| industry | VARCHAR(100) | NULLABLE | Industry type |
| company_size | VARCHAR(50) | NULLABLE | Company size (emp count) |
| location | VARCHAR(255) | NULLABLE | Company location |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Record creation |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last update |

**Indexes:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `users(id)` ON DELETE CASCADE
- INDEX: `user_id`

---

### 4. `lowongan`

Job vacancies posted by companies.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AI | Job ID |
| posisi | VARCHAR(100) | NOT NULL | Job position/title |
| perusahaan | VARCHAR(150) | NOT NULL | Company name |
| kota | VARCHAR(100) | NOT NULL | Job location (city) |
| tipe | ENUM('Full Time','Part Time','Freelance','Contract','Internship') | DEFAULT 'Full Time' | Job type |
| gaji | VARCHAR(100) | NULLABLE | Salary range (string format) |
| inisial | VARCHAR(5) | NOT NULL | Company initials for display |
| rating | DECIMAL(2,1) | DEFAULT 0.0 | Company rating |
| deskripsi | TEXT | NOT NULL | Job description |
| syarat | JSON | NOT NULL | Job requirements (array) |
| tanggung_jawab | JSON | NOT NULL | Job responsibilities (array) |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Posting date |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last update |

**Indexes:**
- PRIMARY KEY: `id`
- INDEX: `perusahaan`
- INDEX: `kota`
- INDEX: `tipe`
- INDEX: `created_at`

**Notes:**
- `inisial` is typically 2-3 letter abbreviation (e.g., "PT", "ABC")
- `syarat` and `tanggung_jawab` store arrays as JSON

---

### 5. `email_verification_codes`

OTP (One-Time Password) codes for email verification.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AI | Code ID |
| user_id | BIGINT | FK→users.id, ON DELETE CASCADE | User to verify |
| code | VARCHAR(6) | UNIQUE, NOT NULL | 6-digit OTP |
| is_used | BOOLEAN | DEFAULT false | Usage status |
| used_at | TIMESTAMP | NULLABLE | Time when used |
| expired_at | TIMESTAMP | NOT NULL | Expiration time |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last update |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE: `code`
- FOREIGN KEY: `user_id` → `users(id)` ON DELETE CASCADE
- INDEX: `expired_at` (for cleanup)

**Business Rules:**
- Code expires in 10 minutes (configurable)
- Code can be used only once (`is_used` flag)
- Multiple codes can exist per user (only latest should be used)

---

### 6. Standard Laravel Tables

#### `password_reset_tokens`
| Column | Type | Description |
|--------|------|-------------|
| email | VARCHAR(255) | PK, User email |
| token | VARCHAR(255) | Reset token hash |
| created_at | TIMESTAMP | Token creation time |

#### `sessions`
| Column | Type | Description |
|--------|------|-------------|
| id | VARCHAR(255) | PK, Session ID |
| user_id | BIGINT | FK→users.id (nullable) |
| ip_address | VARCHAR(45) | Client IP (IPv4/IPv6) |
| user_agent | TEXT | Browser user agent |
| payload | LONGTEXT | Session data (encrypted) |
| last_activity | INT | Unix timestamp |

#### `cache`
| Column | Type | Description |
|--------|------|-------------|
| key | VARCHAR(255) | PK, Cache key |
| value | MEDIUMTEXT | Serialized cache value |
| expiration | BIGINT | Unix timestamp |

#### `cache_locks`
| Column | Attype | Description |
|--------|------|-------------|
| key | VARCHAR(255) | PK, Lock key |
| owner | VARCHAR(255) | Lock owner identifier |
| expiration | BIGINT | Unix timestamp |

#### `jobs` (Queue)
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | PK, AI |
| queue | VARCHAR(255) | Queue name |
| payload | LONGTEXT | Serialized job data |
| attempts | TINYINT | Retry attempts |
| reserved_at | INT | Reservation timestamp |
| available_at | INT | When job becomes available |
| created_at | INT | Job creation timestamp |

#### `job_batches`
| Column | Type | Description |
|--------|------|-------------|
| id | VARCHAR(255) | PK, Batch ID |
| name | VARCHAR(255) | Batch name |
| total_jobs | INT | Total jobs in batch |
| pending_jobs | INT | Jobs remaining |
| failed_jobs | INT | Failed count |
| failed_job_ids | LONGTEXT | Failed job IDs |
| options | MEDIUMTEXT | Batch options |
| cancelled_at | INT | Cancellation timestamp |
| created_at | INT | Batch creation |
| finished_at | INT | Completion timestamp |

#### `failed_jobs`
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | PK, AI |
| uuid | VARCHAR(255) | Unique identifier |
| connection | TEXT | Queue connection name |
| queue | TEXT | Queue name |
| payload | LONGTEXT | Job data |
| exception | LONGTEXT | Exception message |
| failed_at | TIMESTAMP | Failure timestamp |

---

## Migrations

### Migration Order

1. `0001_01_01_000000_create_users_table.php` - Creates users + password_reset_tokens + sessions
2. `0001_01_01_000001_create_cache_table.php` - Creates cache + cache_locks
3. `0001_01_01_000002_create_jobs_table.php` - Creates jobs + job_batches + failed_jobs
4. `2024_01_01_000001_create_company_profiles_table.php` - Creates company_profiles
5. `2026_04_03_101617_create_email_verification_codes_table.php` - Creates email_verification_codes
6. `2026_04_04_175039_create_lowongan_table.php` - Creates lowongan
7. `2026_04_05_112651_create_arsitek_profiles_table.php` - Creates arsitek_profiles
8. `2026_04_06_130737_add_profile_fields_to_users_and_companies_table.php` - Adds additional fields

### Running Migrations

```bash
# Run all migrations
php artisan migrate

# Run specific migration
php artisan migrate --path=/database/migrations/2026_04_05_112651_create_arsitek_profiles_table.php

# Rollback last batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Fresh migration (drop all and re-run)
php artisan migrate:fresh

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

---

## Seeding Data

### Available Seeders

- `Database\Seeders\DatabaseSeeder.php` - Main seeder
- `Database\Seeders\UserSeeder.php` (recommended to create)
- `Database\Seeders\LowonganSeeder.php` (recommended to create)
- `Database\Seeders\CompanyProfileSeeder.php` (recommended to create)
- `Database\Seeders\ArsitekProfileSeeder.php` (recommended to create)

### Running Seeders

```bash
# Run all seeders after fresh migration
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Run seeder with specific role
php artisan db:seed --class=CompanyProfileSeeder
```

### Recommended Seed Data

Create seeders for:

1. **Admin User**
   - Role: `admin`
   - Email: `admin@webarchitect.com`
   - Password: Random secure string

2. **Sample Companies** (5-10)
   - Role: `perusahaan`
   - Complete `company_profiles`
   - Sample `lowongan` per company

3. **Sample Architects** (10-20)
   - Role: `arsitek`
   - Complete `arsitek_profiles`
   - Portfolio items (future table)

4. **Sample Client** (2-3)
   - Role: `client`
   - Basic profile

5. **Lowongan** (20-30)
   - Various types, locations, salary ranges

---

## Factory Patterns

### Model Factories

Located in `database/factories/`:

1. `UserFactory.php` - Creates users with roles
2. `ArsitekProfileFactory.php` - Creates architect profiles
3. `CompanyProfileFactory.php` - Creates company profiles
4. `LowonganFactory.php` - Creates job listings

### Using Factories

```php
// In tests or seeders
$user = User::factory()->create([
    'role' => 'arsitek'
]);

$profile = ArsitekProfile::factory()->create([
    'user_id' => $user->id
]);

// With relationships
$userWithProfile = User::factory()
    ->has(ArsitekProfile::factory())
    ->create();
```

---

## Database Maintenance

### Backup

```bash
# Manual backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Using Laravel backup package (recommended for production)
composer require spatie/laravel-backup
php artisan backup:run
```

### Restore

```bash
mysql -u username -p database_name < backup_file.sql
```

### Optimization

```sql
-- Optimize tables (run periodically)
OPTIMIZE TABLE users, arsitek_profiles, company_profiles, lowongan;

-- Analyze tables for query optimizer
ANALYZE TABLE users, lowongan;

-- Check table size
SELECT table_name, data_length, index_length 
FROM information_schema.tables 
WHERE table_schema = 'laravel';
```

### Monitoring

Enable slow query log in MySQL:
```sql
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2; -- seconds
```

---

## Future Tables (Not Yet Implemented)

Based on current business logic, these tables are anticipated:

1. `applications` - Job applications
   - user_id (arsitek)
   - lowongan_id
   - status (pending, accepted, rejected)
   - cover_letter
   - resume_url

2. `proyek` - Client projects
   - client_id
   - title
   - description
   - budget
   - deadline
   - status

3. `proposals` - Architect proposals for projects
   - arsitek_id
   - proyek_id
   - proposal_text
   - attachments
   - status

4. `portofolio_items` - Architect portfolio items
   - arsitek_id
   - title
   - description
   - image_urls (JSON)
   - project_type

5. `messages` / `inbox` - Messaging system
   - sender_id
   - receiver_id
   - subject
   - body
   - thread_id
   - is_read

6. `notifikasi` - Notifications (already referenced in routes)
   - user_id
   - type
   - data (JSON)
   - is_read
   - created_at

7. `reports` / `laporan` - Content reporting
   - reporter_id
   - reported_user_id (nullable)
   - reported_lowongan_id (nullable)
   - reason
   - status

8. `skills` / `specializations` - Architect skills
   - name
   - description

9. `arsitek_skill` - Pivot table for many-to-many
   - arsitek_id
   - skill_id

---

## Data Integrity Constraints

### Foreign Key Cascades

- `arsitek_profiles.user_id` → `users.id` ON DELETE CASCADE
- `company_profiles.user_id` → `users.id` ON DELETE CASCADE
- `email_verification_codes.user_id` → `users.id` ON DELETE CASCADE

### Ensured by Application Logic

- Role-based data isolation (users can only see their role's data)
- Soft deletes not implemented yet (users are hard deleted)
- No circular dependencies designed

---

## Character Set & Collation

All tables use:
- **Charset**: `utf8mb4`
- **Collation**: `utf8mb4_unicode_ci`

Supports:
- Complete Unicode (emojis, all languages)
- Proper sorting and comparisons

---

**Last Updated**: 2026-04-07

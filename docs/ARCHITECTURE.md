# System Architecture

## Overview

Web Architect adalah platform recruitment untuk industri arsitektur menggunakan arsitektur **Laravel + Vue.js + Inertia**. Sistem ini mengadopsi pola **Server-Side Rendering (SSR)** dengan Inertia untuk memberikan pengalaman SPA tanpa membangun API terpisah.

```
┌─────────────────────────────────────────────────────────────────┐
│                          Client (Browser)                        │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Vue 3 Components (resources/js/Pages & Components)     │   │
│  │  - Composition API + <script setup>                     │   │
│  │  - Tailwind CSS                                         │   │
│  └────────────────────┬───────────────────────────────────┘   │
│                       │ Inertia.js Requests                   │
└───────────────────────┼───────────────────────────────────────┘
                        │
┌───────────────────────▼───────────────────────────────────────┐
│                   Laravel Application                           │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  Routes (routes/*.php)                                 │  │
│  │  - web.php: Public routes                              │  │
│  │  - auth.php: Authentication routes                     │  │
│  │  - admin.php, client.php, perusahaan.php, arsitek.php  │  │
│  └────────────────────────┬──────────────────────────────┘  │
│                           │                                  │
│  ┌────────────────────────▼──────────────────────────────┐  │
│  │  Controllers (app/Http/Controllers/)                  │  │
│  │  - Handles business logic                            │  │
│  │  - Returns Inertia::render() responses               │  │
│  └────────────────────────┬──────────────────────────────┘  │
│                           │                                  │
│  ┌────────────────────────▼──────────────────────────────┐  │
│  │  Eloquent ORM (app/Models/)                          │  │
│  │  - User, ArsitekProfile, CompanyProfile, Lowongan    │  │
│  └────────────────────────┬──────────────────────────────┘  │
│                           │                                  │
│  ┌────────────────────────▼──────────────────────────────┐  │
│  │  Database (MySQL 8.0)                                 │  │
│  └─────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
```

## Design Patterns

### 1. **Service Container & Dependency Injection**
- Laravel's service container is heavily used for dependency management
- Controllers receive dependencies via constructor injection

### 2. **Eloquent ORM Relationships**
- One-to-One: User → ArsitekProfile / CompanyProfile
- One-to-Many: User → EmailVerificationCodes
- Polymorphic relationships prepared (profiles for different roles)

### 3. **Role-Based Access Control (RBAC)**
- Uses middleware: `role:admin`, `role:client`, etc.
- Role-specific dashboard prefixes (e.g., `/profile/client`)
- User model has helper methods: `isAdmin()`, `isArsitek()`, etc.

### 4. **Repository Pattern (Implicit)**
- Eloquent models act as repositories
- Business logic in models or dedicated services folder (if created)

### 5. **Frontend Composition API**
- Vue 3 Composition API with `<script setup>`
- Reusable composables for shared logic
- Component-based architecture with props down, events up

### 6. **SPA via Inertia.js**
- No traditional REST API for frontend
- Server returns page component names + props
- Inertia handles client-side routing without page reloads

## Folder Structure Explained

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Admin-specific controllers
│   │   ├── Arsitek/         # Architect-specific controllers
│   │   ├── Auth/            # Authentication controllers
│   │   ├── Client/          # Client-specific controllers
│   │   ├── Perusahaan/      # Company-specific controllers
│   │   └── Public/          # Public-facing controllers
├── Models/
│   ├── User.php            # Main user model with role methods
│   ├── ArsitekProfile.php  # Architect profile extension
│   ├── CompanyProfile.php  # Company profile extension
│   └── Lowongan.php        # Job vacancy model
├── Middleware/
│   └── EnsureRole.php      # Role-based middleware
├── Services/               # Business logic services (to be created)
└── View/                   # View composers (optional)

database/
├── migrations/             # Schema migrations in chronological order
├── seeders/                # Database seeders
└── factories/              # Model factories for testing

resources/js/
├── Components/
│   ├── UI/                 # shadcn/ui components
│   ├── Public/             # Public-specific components
│   ├── Sidebar.vue         # Role-based sidebar
│   ├── Topbar.vue          # Top navigation bar
│   └── Breadcrumb.vue      # Breadcrumb navigation
├── Layouts/
│   ├── PublicLayout.vue    # Layout for public pages
│   ├── AuthenticatedLayout.vue # Layout for all dashboards
│   └── ProfileLayout.vue   # Layout for profile pages
├── Pages/
│   ├── Auth/               # Login, Register, Password Reset
│   ├── Dashboard/          # Admin, Client, Perusahaan, Arsitek dashboards
│   ├── Public/             # Public pages (Lowongan, Arsitek)
│   └── Landing.vue         # Homepage
└── Composables/            # Vue composables (to be created)

routes/
├── web.php                 # Main routing file
├── auth.php                # Authentication & public feature routes
├── admin.php               # Admin dashboard routes (prefix: dashboard/admin)
├── client.php              # Client dashboard routes (prefix: profile/client)
├── perusahaan.php          # Company dashboard routes (prefix:/profile/perusahaan)
└── arsitek.php             # Architect dashboard routes (prefix: /profile/arsitek)

docker/
├── nginx/
│   └── default.conf        # Nginx server configuration
└── php/
    └── Dockerfile          # PHP-FPM with Laravel-required extensions
```

## Database Schema

### Entity Relationship Diagram

```mermaid
erDiagram
    users ||--o{ arsitek_profiles : "has one"
    users ||--o{ company_profiles : "has one"
    users ||--o{ email_verification_codes : "has many"
    users {
        bigint id PK
        string name
        string email UK
        string password
        enum role
        boolean is_active
        boolean is_verified
        string avatar_url
        string location
        string phone
        timestamp email_verified_at
        timestamps
    }
    arsitek_profiles {
        bigint id PK
        bigint user_id FK
        string first_name
        string last_name
        enum status_pekerjaan
        boolean is_student
        string location
        string school
        enum degree_type
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
        string industry
        string company_size
        string location
        timestamp verified_at
        timestamps
    }
    email_verification_codes {
        bigint id PK
        bigint user_id FK
        string email
        string token
        timestamp expires_at
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
```

### Detailed Table Structures

See [DATABASE.md](DATABASE.md) for complete schema details.

## Data Flow

### 1. **Authentication Flow**
```
1. User submits login form → POST /login
2. LoginController validates credentials
3. If OTP not verified → redirect to /verifikasi-email
4. OtpVerificationController validates OTP
5. User redirected to dashboard based on role
```

### 2. **Job Application Flow**
```
1. Authenticated arsitek views lowongan detail
2. Clicks "Apply" → POST /lowongan/{id}/lamar
3. System creates application record (to be implemented)
4. Email notification sent to company (optional)
6. Company can view applicants in their dashboard
```

### 3. **Project Proposal Flow**
```
1. Client creates project (POST /proyek)
2. Project is stored in database
3. Arsitek browses projects at /proyek
4. Arsitek submits proposal (POST /proyek/{id}/proposal)
5. Client receives proposal in inbox/dashboard
6. Client accepts/rejects proposal
```

## Security Architecture

### Authentication
- **Session-based** using Laravel's native auth
- **OTP verification** via `email_verification_codes` table
- **Password hashing** using Laravel's bcrypt
- **CSRF protection** enabled on all forms via `@csrf`
- **Rate limiting** on OTP resend (3 attempts per minute)

### Authorization
- **Role-based middleware** on all dashboard routes
- **Route model binding** for resource authorization
- **Gate policies** to be implemented for fine-grained control

### Data Protection
- **Mass assignment protection** via `$fillable` on models
- **SQL injection prevention** through Eloquent ORM
- **XSS protection** via Vue's auto-escaping
- **Input validation** in FormRequest classes (to be implemented)
- **File uploads** validated and stored securely

## Scalability Considerations

### Current Implementation
- Monolithic Laravel application
- All features in single codebase
- Single database connection

### Future Improvements
- **Queue System**: Redis-based job queues for async processing
- **Caching**: Redis for session and cache driver
- **Database**: Ready for read replicas if needed
- **Load Balancing**: Docker-compatible for multiple app instances
- **CDN**: Asset delivery via Vite + CDN integration
- **API Splitting**: Could extract API layer for mobile apps

### Performance Optimizations
- Database query optimization (eager loading)
- Route caching: `php artisan route:cache`
- Config caching: `php artisan config:cache`
- View caching: `php artisan view:cache`
- Asset bundling via Vite

## Third-Party Integrations

### Current Integrations
- **Resend** - Transactional email service (for password resets)
- **Breeze** - Authentication scaffolding
- **Inertia.js** - SPA bridge
- **Tailwind CSS** + **Radix Vue** - UI components
- **Lucide Icons** - Icon library

### Potential Integrations
- **File Storage**: AWS S3 or Laravel Vapor (for portfolio uploads)
- **Notifications**: Database, mail, broadcast channels
- **Payments**: Midtrans, xendit (for premium features)
- **WebSockets**: Laravel WebSockets for real-time messaging
- **Search**: Algolia or Meilisearch for job/architect search

## Cross-Origin Resource Sharing (CORS)

Currently not explicitly configured as frontend and backend are served from same origin via Inertia. If exposing API for mobile apps:

```php
// config/cors.php to be configured
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['https://your-mobile-app.com'],
```

## Environment Segregation

- **.env** - Local development configuration
- **.env.example** - Template for new developers
- Docker containers provide consistent environment across all stages

Different environments (staging, production) should use different `.env` files with appropriate database, cache, and queue configurations.

## Monitoring & Observability

- **Laravel Pail** - Log viewer for development
- **Database queries** - Query logging in `.env` (`DB_DEBUG=true`)
- **Error tracking** - To be integrated (e.g., Sentry, Bugsnag)
- **Uptime monitoring** - External service recommended

## Browser Support

Modern browsers (last 2 versions):
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

Due to Vue 3 and modern JavaScript features, older browsers (IE11) are not supported.

---

**Last Updated**: 2026-04-07

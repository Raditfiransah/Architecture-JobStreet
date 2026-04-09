# API Documentation

**Important Note**: This application primarily uses **Inertia.js** for server-side rendering with a SPA-like experience. There is **no separate REST API** for the frontend. All communication between client and server happens through Inertia's special request format (X-Inertia headers).

However, some endpoints return JSON responses for AJAX calls or could be used for future mobile API development.

## Base URL

```
Production: https://yourdomain.com
Staging:   https://staging.yourdomain.com
Local:     http://localhost:8000 (artisan serve) or http://localhost (Docker nginx)
```

## Authentication

### Current Implementation
- **Session-based** authentication via Laravel's built-in system
- **OTP Email Verification** required after registration
- Inertia automatically handles session cookies

### Future API Authentication (if needed)
For potential mobile app or external API integration, consider using **Laravel Sanctum**:
```php
// Add Sanctum middleware to api routes
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
]
```

## Endpoints Reference

### Public Routes

#### GET `/` - Home Page
Renders the landing page.

**Response**: Inertia page render → `Landing.vue`

---

#### GET `/hire-arsitek` - Architect Directory
Public directory listing all architects.

**Route Name**: `arsitek.index`

**Controller**: `Public\ArsitekController@index`

**Query Parameters**:
- `q` (string) - Search query
- `specialization` (string) - Filter by specialization
- `location` (string) - Filter by location

**Response**: Inertia page render → `Public/Arsitek/Index.vue`

---

#### GET `/lowongan` - Job Listings
Public listing of job vacancies.

**Route Name**: `lowongan.index`

**Controller**: `Public\LowonganController@index`

**Query Parameters**:
- `q` (string) - Search keyword
- `category` (string) - Filter by category
- `location` (string) - Filter by location
- `type` (string) - Full-time/part-time/etc
- `salary_min` (integer) - Minimum salary
- `page` (integer) - Pagination page

**Response**: Inertia page render → `Public/Lowongan/Index.vue`

---

#### GET `/lowongan/{id}` - Job Detail
Detailed view of a specific job posting.

**Route Name**: `lowongan.show`

**URL Parameters**:
- `id` (integer) - Job ID

**Response**: Inertia page render → `Public/Lowongan/Index.vue` (currently placeholder)

---

### Authentication Routes

#### GET `/login` - Login Form
**Route Name**: `login`

**Middleware**: `guest`

**Response**: Inertia page render → `Auth/Login.vue`

#### POST `/login` - Process Login
**Route Name**: (unnamed)

**Request Body**:
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response**:
- Success: Redirect to dashboard (302)
- Failure: Redirect back with errors (422)

---

#### GET `/register` - Registration Form
**Route Name**: `register`

**Middleware**: `guest`

**Response**: Inertia page render → `Auth/Register.vue`

#### POST `/register` - Process Registration
**Route Name**: (unnamed)

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "arsitek" // or "client", "perusahaan"
}
```

**Response**:
- Success: Redirect to `/verifikasi-email` (302)
- Failure: Redirect back with errors (422)

---

#### GET `/verifikasi-email` - OTP Verification Form
**Route Name**: `verification.notice`

**Middleware**: `auth`

**Response**: Inertia page render → `Auth/VerifyEmail.vue`

#### POST `/verifikasi-email` - Verify OTP
**Route Name**: `otp.verify`

**Request Body**:
```json
{
  "otp": "123456"
}
```

**Response**:
- Success: Redirect to dashboard (302)
- Failure: Redirect back with errors (422)

#### POST `/verifikasi-email/resend` - Resend OTP
**Route Name**: `otp.resend`

**Middleware**: `throttle:3,1` (3 attempts per minute)

**Response**:
```json
{
  "message": "OTP has been resent to your email"
}
```

---

#### GET `/lupa-password` - Forgot Password Form
**Route Name**: `password.request`

**Middleware**: `guest`

**Response**: Inertia page render → `Auth/ForgotPassword.vue`

#### POST `/lupa-password` - Send Reset Link
**Route Name**: `password.email`

**Request Body**:
```json
{
  "email": "user@example.com"
}
```

**Response**:
- Success: Redirect back with status (302)
- Failure: Redirect back with errors (422)

---

#### GET `/reset-password/{token}` - Reset Password Form
**Route Name**: `password.reset`

**Middleware**: `guest`

**URL Parameters**:
- `token` (string) - Password reset token

**Response**: Inertia page render → `Auth/ResetPassword.vue`

#### POST `/reset-password` - Process Password Reset
**Request Body**:
```json
{
  "token": "reset_token_here",
  "email": "user@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response**:
- Success: Redirect to login (302)
- Failure: Redirect back with errors (422)

---

#### POST `/logout` - Logout User
**Route Name**: `logout`

**Middleware**: `auth`

**Response**: Redirect to home (302)

---

### Authenticated User Routes

#### GET `/proyek` - My Projects (for Client)
**Route Name**: `proyek.index`

**Middleware**: `auth`

**Response**: Inertia page render → `Public/DefaultPublicPage.vue` (placeholder)

#### GET `/proyek/{id}` - Project Detail
**Route Name**: `proyek.show`

**URL Parameters**:
- `id` (integer) - Project ID

---

#### GET `/arsitek` - Architects Directory (for logged-in users)
**Route Name**: `arsitek.direktori`

**Middleware**: `auth`

---

#### GET `/arsitek/{username}` - Architect Profile
**Route Name**: `arsitek.profil`

**URL Parameters**:
- `username` (string) - Architect's username

---

#### GET `/info` - Info Hub
**Route Name**: `info.index`

**Middleware**: `auth`

---

#### GET `/info/{slug}` - Info Article
**Route Name**: `info.show`

**URL Parameters**:
- `slug` (string) - Article slug

---

### Admin Routes (prefix: `/dashboard/admin`)

| Route Name | Method | URL | Description |
|------------|--------|-----|-------------|
| `admin.dashboard` | GET | `/dashboard/admin` | Admin dashboard with stats |
| `admin.antrian` | GET | `/dashboard/admin/antrian` | Queue/antri dashboard |
| `admin.lowongan.index` | GET | `/dashboard/admin/lowongan` | List all job postings |
| `admin.lowongan.show` | GET | `/dashboard/admin/lowongan/{id}` | Job detail |
| `admin.lowongan.setujui` | POST | `/dashboard/admin/lowongan/{id}/setujui` | Approve job |
| `admin.lowongan.tolak` | POST | `/dashboard/admin/lowongan/{id}/tolak` | Reject job |
| `admin.lowongan.tutup` | POST | `/dashboard/admin/lowongan/{id}/tutup` | Close job |
| `admin.proyek.index` | GET | `/dashboard/admin/proyek` | List all projects |
| `admin.proyek.show` | GET | `/dashboard/admin/proyek/{id}` | Project detail |
| `admin.proyek.setujui` | POST | `/dashboard/admin/proyek/{id}/setujui` | Approve project |
| `admin.proyek.tolak` | POST | `/dashboard/admin/proyek/{id}/tolak` | Reject project |
| `admin.users.index` | GET | `/dashboard/admin/users` | List all users |
| `admin.users.show` | GET | `/dashboard/admin/users/{id}` | User detail |
| `admin.users.verifikasi` | POST | `/dashboard/admin/users/{id}/verifikasi` | Verify user |
| `admin.users.suspend` | POST | `/dashboard/admin/users/{id}/suspend` | Suspend user |
| `admin.users.aktifkan` | POST | `/dashboard/admin/users/{id}/aktifkan` | Activate user |
| `admin.info.index` | GET | `/dashboard/admin/info` | Info articles |
| `admin.info.setujui` | POST | `/dashboard/admin/info/{id}/setujui` | Approve article |
| `admin.info.tolak` | POST | `/dashboard/admin/info/{id}/tolak` | Reject article |
| `admin.laporan.index` | GET | `/dashboard/admin/laporan` | Reports/flagged content |
| `admin.laporan.tindak` | POST | `/dashboard/admin/laporan/{id}/tindak` | Take action on report |

---

### Client Routes (prefix: `/profile/client`)

| Route Name | Method | URL | Description |
|------------|--------|-----|-------------|
| `client.profile` | GET | `/profile/client` | Client dashboard |
| `client.profile.update` | PUT | `/profile/client/profile` | Update profile |
| `client.proyek.index` | GET | `/profile/client/proyek` | My projects |
| `client.proyek.create` | GET | `/profile/client/proyek/buat` | Create project form |
| `client.proyek.store` | POST | `/profile/client/proyek` | Store project |
| `client.proyek.show` | GET | `/profile/client/proyek/{id}` | Project detail |
| `client.proyek.edit` | GET | `/profile/client/proyek/{id}/edit` | Edit project |
| `client.proyek.update` | PUT | `/profile/client/proyek/{id}` | Update project |
| `client.proyek.tutup` | PUT | `/profile/client/proyek/{id}/tutup` | Close project |
| `client.proyek.destroy` | DELETE | `/profile/client/proyek/{id}` | Delete project |
| `client.proposal.index` | GET | `/profile/client/proyek/{id}/proposal` | Proposals for project |
| `client.proposal.show` | GET | `/profile/client/proyek/{id}/proposal/{propId}` | Proposal detail |
| `client.proposal.terima` | POST | `/profile/client/proposal/{propId}/terima` | Accept proposal |
| `client.proposal.tolak` | POST | `/profile/client/proposal/{propId}/tolak` | Reject proposal |
| `client.inbox.index` | GET | `/profile/client/inbox` | Inbox/messages |
| `client.inbox.show` | GET | `/profile/client/inbox/{thread}` | Thread detail |
| `client.inbox.reply` | POST | `/profile/client/inbox/{thread}` | Reply to thread |
| `client.pengaturan.index` | GET | `/profile/client/pengaturan` | Settings |
| `client.pengaturan.password` | PUT | `/profile/client/pengaturan/password` | Change password |

---

### Perusahaan (Company) Routes (prefix: `/profile/perusahaan`)

| Route Name | Method | URL | Description |
|------------|--------|-----|-------------|
| `perusahaan.profile` | GET | `/profile/perusahaan` | Company dashboard |
| `perusahaan.profil.edit` | GET | `/profile/perusahaan/profil` | Edit profile form |
| `perusahaan.profil.update` | PUT | `/profile/perusahaan/profil` | Update profile |
| `perusahaan.lowongan.index` | GET | `/profile/perusahaan/lowongan` | My job postings |
| `perusahaan.lowongan.create` | GET | `/profile/perusahaan/lowongan/buat` | Create job form |
| `perusahaan.lowongan.store` | POST | `/profile/perusahaan/lowongan` | Store job posting |
| `perusahaan.lowongan.edit` | GET | `/profile/perusahaan/lowongan/{id}/edit` | Edit job |
| `perusahaan.lowongan.update` | PUT | `/profile/perusahaan/lowongan/{id}` | Update job |
| `perusahaan.lowongan.tutup` | PUT | `/profile/perusahaan/lowongan/{id}/tutup` | Close job |
| `perusahaan.lowongan.perpanjang` | PUT | `/profile/perusahaan/lowongan/{id}/perpanjang` | Extend job |
| `perusahaan.lowongan.destroy` | DELETE | `/profile/perusahaan/lowongan/{id}` | Delete job |
| `perusahaan.pelamar.index` | GET | `/profile/perusahaan/lowongan/{id}/pelamar` | Applicants for job |
| `perusahaan.pelamar.show` | GET | `/profile/perusahaan/lowongan/{id}/pelamar/{appId}` | Applicant detail |
| `perusahaan.lamaran.status` | PUT | `/profile/perusahaan/lamaran/{appId}/status` | Update application status |
| `perusahaan.lamaran.shortlist` | POST | `/profile/perusahaan/lamaran/{appId}/shortlist` | Shortlist applicant |
| `perusahaan.inbox.index` | GET | `/profile/perusahaan/inbox` | Inbox/messages |
| `perusahaan.inbox.show` | GET | `/profile/perusahaan/inbox/{thread}` | Thread detail |
| `perusahaan.inbox.reply` | POST | `/profile/perusahaan/inbox/{thread}` | Reply to thread |
| `perusahaan.pengaturan.index` | GET | `/profile/perusahaan/pengaturan` | Settings |
| `perusahaan.pengaturan.password` | PUT | `/profile/perusahaan/pengaturan/password` | Change password |

---

### Arsitek (Architect) Routes (prefix: `/profile/arsitek`)

| Route Name | Method | URL | Description |
|------------|--------|-----|-------------|
| `arsitek.profile` | GET | `/profile/arsitek` | Architect dashboard |
| `arsitek.profil.edit` | GET | `/profile/arsitek/profil` | Edit profile form |
| `arsitek.profil.update` | PUT | `/profile/arsitek/profil` | Update profile |
| `arsitek.profil.preview` | GET | `/profile/arsitek/profil/preview` | Preview public profile |
| `arsitek.profil.avatar` | PUT | `/profile/arsitek/avatar` | Update avatar |
| `arsitek.portofolio.index` | GET | `/profile/arsitek/portofolio` | Portfolio list |
| `arsitek.portofolio.create` | GET | `/profile/arsitek/portofolio/tambah` | Add portfolio item |
| `arsitek.portofolio.store` | POST | `/profile/arsitek/portofolio` | Store portfolio |
| `arsitek.portofolio.edit` | GET | `/profile/arsitek/portofolio/{id}/edit` | Edit portfolio item |
| `arsitek.portofolio.update` | PUT | `/profile/arsitek/portofolio/{id}` | Update portfolio item |
| `arsitek.portofolio.destroy` | DELETE | `/profile/arsitek/portofolio/{id}` | Delete portfolio item |
| `arsitek.portofolio.reorder` | POST | `/profile/arsitek/portofolio/reorder` | Reorder portfolio items |
| `arsitek.lamaran.index` | GET | `/profile/arsitek/lamaran` | My job applications |
| `arsitek.lamaran.show` | GET | `/profile/arsitek/lamaran/{id}` | Application detail |
| `arsitek.lamaran.withdraw` | DELETE | `/profile/arsitek/lamaran/{id}` | Withdraw application |
| `arsitek.proposal.index` | GET | `/profile/arsitek/proposal` | My project proposals |
| `arsitek.proposal.show` | GET | `/profile/arsitek/proposal/{id}` | Proposal detail |
| `arsitek.proposal.update` | PUT | `/profile/arsitek/proposal/{id}` | Update proposal |
| `arsitek.inbox.index` | GET | `/profile/arsitek/inbox` | Inbox/messages |
| `arsitek.inbox.show` | GET | `/profile/arsitek/inbox/{thread}` | Thread detail |
| `arsitek.inbox.reply` | POST | `/profile/arsitek/inbox/{thread}` | Reply to thread |
| `arsitek.notifikasi.index` | GET | `/profile/arsitek/notifikasi` | Notifications |
| `arsitek.notifikasi.readAll` | POST | `/profile/arsitek/notifikasi/baca-semua` | Mark all as read |
| `arsitek.pengaturan.index` | GET | `/profile/arsitek/pengaturan` | Settings |
| `arsitek.pengaturan.password` | PUT | `/profile/arsitek/pengaturan/password` | Change password |
| `arsitek.pengaturan.notifikasi` | PUT | `/profile/arsitek/pengaturan/notifikasi` | Notification settings |
| `arsitek.pengaturan.delete` | DELETE | `/profile/arsitek/pengaturan/akun` | Delete account |
| `arsitek.lamaran.store` | POST | `/lowongan/{id}/lamar` | Apply for job (public page) |
| `arsitek.proposal.store` | POST | `/proyek/{id}/proposal` | Submit project proposal (public page) |

---

## Inertia Request Format

All page loads from Inertia follow this format:

**Request Headers**:
```
X-Inertia: true
X-Inertia-Version: <generated version hash>
Accept: text/html, application/xhtml+xml
```

**Response Headers**:
```
X-Inertia: true
X-Inertia-Version: <version hash>
```

**Response Body (JSON)**:
```json
{
  "component": "Pages/SomePage",
  "props": {
    "page": {
      "name": "SomePage",
      "props": { ... }
    }
  },
  "url": "/some-url",
  "version": "<hash>"
}
```

Or if it's a redirect:
```json
{
  "component": "Redirect",
  "props": {
    "destination": "/target-url"
  }
}
```

## Error Handling

### Standard Validation Errors (422 Unprocessable Entity)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Authentication Errors (401/403)
- Session expired → redirect to `/login`
- Insufficient permissions → redirect to home with error message

### Not Found (404)
- Route not found → redirect to home (or 404 page if implemented)
- Model not found → abort(404, 'Not Found')

### Server Errors (500)
- Laravel's default error page in production
- Detailed error in development (APP_DEBUG=true)

## Pagination

Laravel's pagination is used for lists. Inertia automatically serializes paginator objects:

```json
{
  "current_page": 1,
  "data": [ ... ],
  "first_page_url": "?page=1",
  "from": 1,
  "last_page": 5,
  "last_page_url": "?page=5",
  "next_page_url": "?page=2",
  "per_page": 15,
  "prev_page_url": null,
  "to": 15,
  "total": 75
}
```

Frontend can use vueuse/usePagination or manual pagination component.

## Rate Limiting

Currently applied only on:
- OTP resend: `throttle:3,1` (3 attempts per minute)

Future rate limits should be applied via middleware on sensitive endpoints.

## Status Codes

- `200` - Success (GET, PUT, PATCH)
- `201` - Created (POST)
- `302` - Redirect (after POST for PRG pattern)
- `422` - Validation error
- `403` - Forbidden (insufficient permissions)
- `404` - Not found
- `419` - CSRF token mismatch
- `429` - Too many requests (rate limited)
- `500` - Server error

## Webhooks

No webhooks currently implemented. For future features:
- Job posted notification to followers
- Proposal submitted notification
- Message received notification
- Consider using Laravel Webhooks package or custom webhook dispatcher

## API Versioning

Not applicable currently. Future API versioning can be done via URL prefix:
```
/api/v1/...
```

---

**Last Updated**: 2026-04-07

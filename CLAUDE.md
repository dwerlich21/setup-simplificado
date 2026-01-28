# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Full-stack admin dashboard ("Libertas") with Vue 3 frontend and Laravel 12 backend. Cookie-based authentication using HttpOnly cookies (Laravel Sanctum). Primary language is Portuguese (Brazilian).

## Development Commands

### Frontend (`front/`)
```bash
yarn install          # Install dependencies (use yarn, not npm)
yarn dev              # Dev server on http://localhost:8080
yarn build            # Production build
yarn lint             # ESLint with auto-fix
```

### Backend (`api/`)
```bash
composer install                # Install dependencies
php artisan serve               # API server on http://localhost:8000
php artisan migrate             # Run database migrations
php artisan test                # Run PHPUnit tests
php artisan test --filter=Name  # Run a single test
composer dev                    # Run Laravel server + queue + logs concurrently
```

### Code Generation
```bash
cd front && node makeCrud.js <modelName>  # Scaffold CRUD (constants, view, session, route)
```

## Architecture

### Two-App Structure
- `api/` — Laravel 12 REST API (PHP 8.2+, MySQL, Sanctum auth)
- `front/` — Vue 3 SPA (Vite, Pinia, Bootstrap Vue 3, Axios)

### Authentication Flow
1. Login sends credentials to API; backend sets HttpOnly cookies (`access_token`, `refresh_token`)
2. Frontend never touches tokens — `withCredentials: true` in Axios sends cookies automatically
3. Backend `CookieToTokenMiddleware` converts cookie to Authorization header before Sanctum validates
4. Token refresh is handled transparently on the backend
5. Route guards in Vue Router check `authStore.loggedIn`

### Frontend Architecture

**Service Layer** — `front/src/services/BaseService.js` provides `index()`, `getById()`, `save()`, `delete()`, `bulkDelete()`, `bulkChangeActive()`. Entity services extend it.

**Composables** — `front/src/composables/`:
- `useCrud.js` — Complete CRUD state: form data, loading, pagination, filters, save/delete actions
- `messages.js` — `notifySuccess()`, `notifyError()` via Notivue
- `setSessions.js` — LocalStorage session/filter persistence
- `functions.js` — Utility helpers (encoding, validation)

**Pinia Stores** — `front/src/stores/`:
- `auth.js` — User, permissions, enums, login/logout/refresh
- `layout.js` — Sidebar, theme state
- `notification.js` — Notification management

**Base Components** — `front/src/components/base/`: `Crud.vue`, `TableLists.vue`, `ModalForm.vue`, `Filter.vue`, `Actions.vue`, `ChangeStatus.vue`. These compose together for standard CRUD pages.

**HTTP Client** — `front/src/http/index.js`: Axios instance with `withCredentials: true`, interceptors handle 401 (redirect to login) and 403.

**Path alias**: `@` resolves to `src/` (configured in `vite.config.js` and `jsconfig.json`).

### Backend Architecture

**Middleware Stack** (applied to protected routes):
`cookie.to.token` → `auth:sanctum` → `is.active` → `permission`

**Layered Structure**:
- `Controllers/Api/` — RESTful controllers (LoginController, UserController, GoalController, AuditController, NotificationController, ReportController)
- `Services/` — Business logic; `BaseService` handles pagination, filtering, search, CRUD, file uploads
- `Repositories/` — Data access layer
- `Models/` — Eloquent models (User, Permission, AuditLog, Notification)
- `Jobs/` — Background queue jobs
- `Notifications/` — Email notifications

**API Routes** (`api/routes/api.php`): Versioned under `/api/v1/`. Public: login, forgot-password, enums. Protected: user CRUD, goals (with kanban), notifications, audit logs, reports (PDF/Excel export via DOMPDF and PHPSpreadsheet).

**Audit Logging**: Observer pattern tracks model changes in `audit_logs` table.

### CRUD Page Pattern
A typical CRUD page combines these pieces:
1. Constants file defining table columns, form defaults, filters, and action types
2. View using `Filter`, `TableLists`, `ModalForm`, `Actions`, `ChangeStatus` base components
3. Session config in `setSessions.js` for filter persistence
4. Route entry with `authRequired: true` meta

### Permission System
- Backend: `permission` middleware checks user permissions per route
- Frontend: `v-permission` directive controls element visibility based on `authStore` permissions

## Key Configuration

| File | Purpose |
|------|---------|
| `front/src/env.js` | API URLs, app name (copy from `env.example.js`) |
| `api/.env` | DB, mail, cache, session config (copy from `.env.example`) |
| `api/config/cors.php` | CORS with credentials support |
| `api/config/sanctum.php` | Token/cookie configuration |
| `front/.eslintrc.cjs` | ESLint rules (Vue 3 recommended) |

# API — Setup Simplificado

**[Portugues](#portugues) | [English](#english)**

---

## Portugues

API REST construida com Laravel 12 e autenticacao via cookies HttpOnly (Laravel Sanctum). Segue arquitetura em tres camadas: Controller → Service → Repository.

### Stack

| Camada | Tecnologia |
|--------|-----------|
| Framework | Laravel 12, PHP 8.2+ |
| Autenticacao | Laravel Sanctum (cookies HttpOnly) |
| Banco de dados | MySQL |
| Relatorios | DOMPDF (PDF), PHPSpreadsheet (Excel) |
| Testes | PHPUnit 11 |
| Filas | Laravel Queue (jobs assincronos) |

### Estrutura do projeto

```
api/
├── app/
│   ├── Enums/                      # UserRole, GoalStatus, GoalPriority
│   ├── Exceptions/                 # Hierarquia de excecoes tipadas
│   │   ├── ApiException.php        #   Base
│   │   ├── BusinessException.php   #   400
│   │   ├── ValidationException.php #   422
│   │   ├── UnauthorizedException.php # 401
│   │   ├── ForbiddenException.php  #   403
│   │   ├── NotFoundException.php   #   404
│   │   ├── IntegrationException.php
│   │   └── ServerException.php     #   500
│   ├── Guards/
│   │   └── CookieTokenGuard.php    # Guard customizado para cookies
│   ├── Helpers/
│   │   └── Utils.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php      # Base com CRUD + ExceptionHandlerTrait
│   │   │   ├── LoginController.php # Login, logout, recuperacao de senha
│   │   │   └── Api/
│   │   │       ├── UserController.php
│   │   │       ├── PermissionController.php
│   │   │       ├── NotificationController.php
│   │   │       ├── AuditController.php
│   │   │       └── EnumController.php
│   │   ├── Middleware/
│   │   │   ├── CookieToTokenMiddleware.php  # Cookie → header Authorization
│   │   │   ├── RefreshTokenMiddleware.php   # Refresh transparente
│   │   │   ├── ActiveMiddleware.php         # Verifica usuario ativo
│   │   │   └── CheckPermission.php          # RBAC por rota
│   │   └── Requests/
│   │       ├── BaseRequest.php
│   │       ├── UserRequest.php
│   │       ├── ForgotPasswordRequest.php
│   │       ├── RecoverPasswordRequest.php
│   │       └── NotificationRequest.php
│   ├── Jobs/
│   │   ├── SendWelcomeEmail.php
│   │   ├── RecoverPasswordEmail.php
│   │   └── SendNotificationEmail.php
│   ├── Mail/
│   │   └── NotificationMail.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Permission.php
│   │   ├── UserAddress.php
│   │   ├── Notification.php
│   │   └── AuditLog.php
│   ├── Notifications/
│   │   ├── WelcomeNotification.php
│   │   └── RecoverPasswordNotification.php
│   ├── Observers/
│   │   └── UserObserver.php         # Welcome email + anonimizacao LGPD
│   ├── Repositories/
│   │   ├── BaseRepository.php       # CRUD generico
│   │   ├── UserRepository.php
│   │   ├── NotificationRepository.php
│   │   └── AuditLogRepository.php
│   ├── Rules/
│   │   └── Cpf.php                  # Validacao de CPF
│   ├── Services/
│   │   ├── BaseService.php          # CRUD + filtros encadeados
│   │   ├── UserService.php
│   │   ├── NotificationService.php
│   │   ├── AuditService.php
│   │   ├── ExportService.php        # PDF e Excel
│   │   └── CookieManager.php        # Gerenciamento de cookies
│   ├── Traits/
│   │   ├── Auditable.php            # Log automatico de alteracoes
│   │   └── ExceptionHandlerTrait.php # Transacoes + tratamento de excecoes
│   └── Utils/
│       ├── Utils.php
│       └── Cpf.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UsersSeeder.php
│       ├── PermissionSeeder.php
│       └── GoalsSeeder.php
├── routes/
│   └── api.php                      # Rotas versionadas /api/v1/
├── config/
│   ├── auth.php
│   ├── cors.php
│   └── sanctum.php
└── tests/
    ├── Feature/
    │   ├── Auth/LoginTest.php
    │   ├── Auth/PasswordTest.php
    │   ├── User/UserTest.php
    │   ├── Enum/EnumTest.php
    │   ├── Permission/PermissionTest.php
    │   ├── Notification/NotificationTest.php
    │   └── Audit/AuditLogTest.php
    └── Unit/
        ├── Models/UserModelTest.php
        └── Services/UserServiceTest.php
```

### Instalacao

```bash
cd api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

A API estara disponivel em `http://localhost:8000`.

### Rotas da API

Todas as rotas estao sob `/api/v1/`.

**Publicas:**

| Metodo | Rota | Descricao |
|--------|------|-----------|
| POST | `/login` | Autenticacao |
| POST | `/forgot-password` | Solicitar recuperacao de senha |
| POST | `/recover-password` | Recuperar senha com token |
| GET | `/enums` | Listar todos os enums |
| GET | `/enums/{enum}` | Valores de um enum especifico |

**Protegidas** (requer autenticacao):

| Metodo | Rota | Descricao |
|--------|------|-----------|
| POST | `/logout` | Logout |
| GET | `/me` | Dados do usuario logado |
| GET | `/permissions` | Permissoes do usuario |

**Protegidas com permissao** (middleware `permission`):

| Recurso | Rotas | Operacoes |
|---------|-------|-----------|
| Usuarios | `/users` | CRUD + status + operacoes em massa |
| Metas | `/goals` | CRUD + kanban + status + operacoes em massa |
| Notificacoes | `/notifications` | Listar, marcar lida, exclusao em massa |
| Relatorios | `/reports` | Dashboard, metricas, export PDF/Excel |
| Auditoria | `/audit-logs` | Consulta de logs, acoes, estatisticas |

### Middleware Stack

As rotas protegidas passam pela seguinte cadeia de middleware:

```
cookie.to.token → auth:sanctum → is.active → permission
```

1. **CookieToTokenMiddleware** — Extrai o token do cookie HttpOnly e injeta no header `Authorization`
2. **auth:sanctum** — Valida o token via Sanctum
3. **ActiveMiddleware** — Verifica se o usuario esta ativo
4. **CheckPermission** — Verifica se o usuario tem permissao para a rota

### Arquitetura em tres camadas

```
Request → Controller → Service → Repository → Model → Database
```

Cada camada possui uma classe base abstrata:

- **Controller** — CRUD padrao + `handleWithTransaction()` com rollback automatico e tratamento de excecoes tipadas
- **BaseService** — CRUD completo + sistema de filtros encadeados (`$filtersOrder`) para busca textual, `where`, `whereNull`, `whereBetween`, eager loading, ordenacao e paginacao
- **BaseRepository** — Acesso a dados via Eloquent (`all`, `find`, `create`, `update`, `delete`, `bulkDelete`, `changeActive`)

### Auditoria

A trait `Auditable` registra automaticamente alteracoes no `AuditLog`:

| Evento | O que registra |
|--------|---------------|
| `created` | Todos os atributos como `new_values` |
| `updated` | Campos alterados (`getDirty()`) com `old_values` e `new_values` |
| `deleted` | Todos os atributos como `old_values` |

Para adicionar auditoria a um Model:

```php
use App\Traits\Auditable;

class NovoModel extends Model
{
    use Auditable;
}
```

### Fluxo de autenticacao

```
POST /login (credenciais)
  ↓ Valida email + senha
  ↓ Revoga tokens anteriores
  ↓ Cria access_token (10 min) + refresh_token (24h)
  ↓ Seta cookies HttpOnly (secure, SameSite=lax)
  ↓ Retorna dados do usuario
```

O `CookieManager` centraliza criacao e remocao de cookies com flags `HttpOnly`, `Secure` e `SameSite`.

### Testes

```bash
php artisan test                       # Todos os testes (44)
php artisan test --filter=LoginTest    # Teste especifico
```

Os testes usam SQLite `:memory:` e cobrem autenticacao, CRUD de usuarios, permissoes, notificacoes, auditoria e enums.

### Comandos uteis

```bash
composer install                # Instalar dependencias
php artisan serve               # Servidor em http://localhost:8000
php artisan migrate             # Executar migrations
php artisan migrate:fresh --seed # Recriar banco com seeds
php artisan test                # Executar testes
php artisan test --filter=Nome  # Executar teste especifico
composer dev                    # Servidor + fila + logs simultaneamente
```

---

## English

REST API built with Laravel 12 and HttpOnly cookie authentication (Laravel Sanctum). Follows a three-layer architecture: Controller → Service → Repository.

### Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12, PHP 8.2+ |
| Authentication | Laravel Sanctum (HttpOnly cookies) |
| Database | MySQL |
| Reports | DOMPDF (PDF), PHPSpreadsheet (Excel) |
| Testing | PHPUnit 11 |
| Queues | Laravel Queue (async jobs) |

### Project structure

```
api/
├── app/
│   ├── Enums/                      # UserRole, GoalStatus, GoalPriority
│   ├── Exceptions/                 # Typed exception hierarchy
│   │   ├── ApiException.php        #   Base
│   │   ├── BusinessException.php   #   400
│   │   ├── ValidationException.php #   422
│   │   ├── UnauthorizedException.php # 401
│   │   ├── ForbiddenException.php  #   403
│   │   ├── NotFoundException.php   #   404
│   │   ├── IntegrationException.php
│   │   └── ServerException.php     #   500
│   ├── Guards/
│   │   └── CookieTokenGuard.php    # Custom cookie-based guard
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php      # Base with CRUD + ExceptionHandlerTrait
│   │   │   ├── LoginController.php # Login, logout, password recovery
│   │   │   └── Api/
│   │   │       ├── UserController.php
│   │   │       ├── PermissionController.php
│   │   │       ├── NotificationController.php
│   │   │       ├── AuditController.php
│   │   │       └── EnumController.php
│   │   ├── Middleware/
│   │   │   ├── CookieToTokenMiddleware.php  # Cookie → Authorization header
│   │   │   ├── RefreshTokenMiddleware.php   # Transparent refresh
│   │   │   ├── ActiveMiddleware.php         # Active user check
│   │   │   └── CheckPermission.php          # Route-level RBAC
│   │   └── Requests/                        # Form validation
│   ├── Jobs/                                # Async queue jobs
│   ├── Models/                              # Eloquent models
│   ├── Observers/
│   │   └── UserObserver.php         # Welcome email + LGPD anonymization
│   ├── Repositories/
│   │   └── BaseRepository.php       # Generic data access
│   ├── Services/
│   │   └── BaseService.php          # CRUD + chained filters
│   └── Traits/
│       ├── Auditable.php            # Automatic change logging
│       └── ExceptionHandlerTrait.php # Transactions + exception handling
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php                      # Versioned routes /api/v1/
└── tests/
    ├── Feature/                     # Auth, User, Notification, Audit, Enum, Permission
    └── Unit/                        # Model and Service tests
```

### Installation

```bash
cd api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

The API will be available at `http://localhost:8000`.

### API Routes

All routes are under `/api/v1/`.

**Public:**

| Method | Route | Description |
|--------|-------|-------------|
| POST | `/login` | Authentication |
| POST | `/forgot-password` | Request password recovery |
| POST | `/recover-password` | Recover password with token |
| GET | `/enums` | List all enums |
| GET | `/enums/{enum}` | Get specific enum values |

**Protected** (requires authentication):

| Method | Route | Description |
|--------|-------|-------------|
| POST | `/logout` | Logout |
| GET | `/me` | Current user data |
| GET | `/permissions` | User permissions |

**Permission-protected** (`permission` middleware):

| Resource | Routes | Operations |
|----------|--------|------------|
| Users | `/users` | CRUD + status + bulk operations |
| Goals | `/goals` | CRUD + kanban + status + bulk operations |
| Notifications | `/notifications` | List, mark read, bulk delete |
| Reports | `/reports` | Dashboard, metrics, PDF/Excel export |
| Audit Logs | `/audit-logs` | Query logs, actions, stats |

### Middleware Stack

Protected routes pass through the following middleware chain:

```
cookie.to.token → auth:sanctum → is.active → permission
```

1. **CookieToTokenMiddleware** — Extracts token from HttpOnly cookie and injects into `Authorization` header
2. **auth:sanctum** — Validates token via Sanctum
3. **ActiveMiddleware** — Checks if user is active
4. **CheckPermission** — Checks if user has permission for the route

### Three-layer architecture

```
Request → Controller → Service → Repository → Model → Database
```

Each layer has an abstract base class:

- **Controller** — Standard CRUD + `handleWithTransaction()` with automatic rollback and typed exception handling
- **BaseService** — Full CRUD + chained filter system (`$filtersOrder`) for text search, `where`, `whereNull`, `whereBetween`, eager loading, sorting and pagination
- **BaseRepository** — Eloquent data access (`all`, `find`, `create`, `update`, `delete`, `bulkDelete`, `changeActive`)

### Audit logging

The `Auditable` trait automatically logs changes to `AuditLog`:

| Event | What it logs |
|-------|-------------|
| `created` | All attributes as `new_values` |
| `updated` | Changed fields (`getDirty()`) with `old_values` and `new_values` |
| `deleted` | All attributes as `old_values` |

To add audit logging to a Model:

```php
use App\Traits\Auditable;

class NewModel extends Model
{
    use Auditable;
}
```

### Authentication flow

```
POST /login (credentials)
  ↓ Validates email + password
  ↓ Revokes previous tokens
  ↓ Creates access_token (10 min) + refresh_token (24h)
  ↓ Sets HttpOnly cookies (secure, SameSite=lax)
  ↓ Returns user data
```

The `CookieManager` centralizes cookie creation and removal with `HttpOnly`, `Secure` and `SameSite` flags.

### Tests

```bash
php artisan test                       # All tests (44)
php artisan test --filter=LoginTest    # Specific test
```

Tests use SQLite `:memory:` and cover authentication, user CRUD, permissions, notifications, audit logs and enums.

### Useful commands

```bash
composer install                # Install dependencies
php artisan serve               # Server at http://localhost:8000
php artisan migrate             # Run migrations
php artisan migrate:fresh --seed # Recreate database with seeds
php artisan test                # Run tests
php artisan test --filter=Name  # Run specific test
composer dev                    # Server + queue + logs concurrently
```

---

## License / Licenca

MIT

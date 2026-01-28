# Setup Simplificado

**[Portugues](#portugues) | [English](#english)**

---

<a id="portugues"></a>

## Portugues

Boilerplate full-stack para iniciar novos projetos rapidamente. Estrutura pronta com autenticacao, CRUD generico, permissoes, auditoria, notificacoes e exportacao de relatorios — tudo ja integrado entre frontend e backend.

### Stack

| Camada | Tecnologia |
|--------|-----------|
| Frontend | Vue 3 (Composition API), Pinia, Vue Router, Bootstrap Vue 3, Axios, Vite |
| Backend | Laravel 12, PHP 8.2+, Laravel Sanctum |
| Banco de dados | MySQL |
| Relatorios | DOMPDF (PDF), PHPSpreadsheet (Excel) |

### O que ja vem pronto

- **Autenticacao por cookies HttpOnly** — Login, logout, refresh de token e recuperacao de senha. Sem token exposto no frontend (protecao contra XSS).
- **CRUD generico** — Componentes base (`Crud.vue`, `TableLists.vue`, `ModalForm.vue`, `Filter.vue`, `Actions.vue`) + composable `useCrud` + `BaseService` no front. No backend, `BaseService` com paginacao, filtros, busca e upload de arquivos.
- **Permissoes (RBAC)** — Middleware `permission` no backend + diretiva `v-permission` no frontend.
- **Auditoria** — Log automatico de alteracoes via Trait Auditable e Eloquent Observer.
- **Notificacoes** — Sistema in-app e por e-mail.
- **Relatorios** — Exportacao em PDF e Excel.
- **Kanban** — Visualizacao kanban para metas/tarefas.
- **Operacoes em massa** — Exclusao e alteracao de status em lote.
- **Gerador de CRUD** — Script `makeCrud.js` que gera constants, view, session e rota para novas entidades.

### Estrutura do projeto

```
setup-simplificado/
├── api/                  # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/Api/   # Controllers RESTful
│   │   ├── Http/Middleware/        # Auth (cookie→token), permissoes
│   │   ├── Models/                 # Eloquent models
│   │   ├── Services/               # Camada de negocio
│   │   ├── Repositories/           # Acesso a dados
│   │   ├── Traits/                 # Auditable, ExceptionHandler
│   │   ├── Observers/              # UserObserver (e-mail, LGPD)
│   │   ├── Jobs/                   # Filas
│   │   └── Notifications/          # Notificacoes por e-mail
│   ├── database/migrations/
│   ├── routes/api.php              # Rotas da API (v1)
│   └── config/                     # auth, cors, sanctum, etc.
│
└── front/                # Frontend Vue 3
    ├── src/
    │   ├── components/base/        # Componentes reutilizaveis de CRUD
    │   ├── composables/            # useCrud, messages, functions, etc.
    │   ├── services/               # BaseService + services por entidade
    │   ├── stores/                 # Pinia (auth, layout, notification)
    │   ├── http/                   # Axios com withCredentials
    │   ├── router/                 # Rotas com guards de autenticacao
    │   ├── views/                  # Paginas organizadas por feature
    │   ├── directives/             # v-permission
    │   └── assets/scss/            # Tema SCSS corporativo
    └── makeCrud.js                 # Gerador de CRUD
```

### Como usar

#### Pre-requisitos

- Node.js 18+
- Yarn
- PHP 8.2+
- Composer
- MySQL

#### Backend

```bash
cd api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

A API estara disponivel em `http://localhost:8000`.

#### Frontend

```bash
cd front
cp src/env.example.js src/env.js
# Descomente e ajuste as variaveis em src/env.js
yarn install
yarn dev
```

O frontend estara disponivel em `http://localhost:8080`.

#### Desenvolvimento simultaneo

No diretorio `api/`, o comando abaixo sobe servidor, fila, logs e Vite simultaneamente:

```bash
composer dev
```

### Arquitetura Backend: Controller → Service → Repository

Toda a API segue uma arquitetura em tres camadas com separacao clara de responsabilidades:

```
Request → Controller → Service → Repository → Model → Database
```

#### Classes Base

Cada camada possui uma classe base abstrata que concentra a logica reutilizavel:

- **`Controller`** (`app/Http/Controllers/Controller.php`) — Metodos `index`, `store`, `show`, `update`, `destroy`, `changeActive`, `bulkDelete`, `bulkChangeActive`. Usa a trait `ExceptionHandlerTrait` para envolver operacoes em transacoes (`handleWithTransaction`) com rollback automatico e tratamento padronizado de excecoes (422, 400, 404, 401, 403).
- **`BaseService`** (`app/Services/BaseService.php`) — Recebe o repository via construtor. Implementa CRUD completo e um sistema de filtros encadeados (`$filtersOrder`) que aplica busca textual, `where`, `whereNull`, `whereBetweenDate`, `whereInColumn`, eager loading (`with`), ordenacao e paginacao de forma declarativa.
- **`BaseRepository`** (`app/Repositories/BaseRepository.php`) — Recebe o Model Eloquent via construtor. Encapsula `all`, `find`, `create`, `update`, `delete`, `bulkDelete`, `changeActive`, `bulkChangeActive`, alem de helpers como `select`, `with`, `whereBetween`, `firstOrCreate`.

Controllers concretos estendem `Controller`, injetam o Service correspondente e sobrescrevem apenas o que difere do CRUD padrao. Para criar uma nova entidade, basta seguir o mesmo padrao: Model, Migration, Repository, Service, Controller, FormRequest e rotas.

#### Sistema de Login

O `LoginController` e independente (nao estende o Controller base) e recebe `CookieManager`, `UserService` e `AuditService`:

```
POST /login
  ↓ Valida email + senha via Hash::check()
  ↓ Revoga tokens anteriores (Sanctum)
  ↓ Cria access_token (10 min) e refresh_token (24h)
  ↓ AuditService::logAuth() registra o evento
  ↓ CookieManager seta cookies HttpOnly (secure, SameSite=lax)
  ↓ Retorna dados do usuario

POST /logout
  ↓ Revoga todos os tokens: $user->tokens()->delete()
  ↓ CookieManager limpa os cookies
  ↓ AuditService::logAuth() registra o logout

POST /forgot-password
  ↓ Despacha job RecoverPasswordEmail (fila assincrona)

POST /recover-password
  ↓ Valida token via Password::broker()
  ↓ Atualiza senha e limpa tokens de reset
  ↓ AuditService::logPasswordReset()
```

O `CookieManager` (`app/Services/CookieManager.php`) centraliza a criacao e remocao dos cookies com flags `HttpOnly`, `Secure` e `SameSite`.

#### CRUD de Usuarios

O `UserController` estende o Controller base e injeta `UserService`. O fluxo de criacao ilustra todas as camadas:

```
POST /api/v1/users
  ↓
UserController::store()
  ↓ validation() valida via UserRequest (FormRequest)
  ↓ handleWithTransaction() abre transacao
  ↓
UserService::create(data)
  ↓ prepareUserData() — extrai dados aninhados, faz Hash da senha
  ↓ UserRepository::create() — insere no banco
  ↓   Model criado → dispara evento 'created'
  ↓     → Trait Auditable registra no AuditLog
  ↓     → UserObserver::created() despacha job SendWelcomeEmail
  ↓ uploadAvatar() — salva imagem se enviada
  ↓ saveAddress() — cria/atualiza UserAddress
  ↓ savePermissions() — sincroniza tabela pivot user_permissions
  ↓
Retorna usuario com relacoes carregadas
```

O `show()` do `UserRepository` retorna os dados estruturados em `basicInfo`, `address` e `permissions`, facilitando o consumo no frontend.

#### Auditoria via Trait Auditable

A trait `Auditable` (`app/Traits/Auditable.php`) e um mixin que qualquer Model pode usar. Ela registra automaticamente no `boot` tres hooks do Eloquent:

| Evento | O que registra |
|--------|---------------|
| `created` | Todos os atributos do novo registro como `new_values` |
| `updated` | Apenas os campos alterados (`getDirty()`), salvando `old_values` e `new_values` |
| `deleted` | Todos os atributos originais como `old_values` |

Campos sensiveis (`password`, `remember_token`, `updated_at`, `created_at`) sao automaticamente excluidos do log.

Cada registro no `AuditLog` armazena: `user_id`, `user_name`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent` e `description`.

O `AuditLog` define constantes para todos os tipos de acao (`created`, `updated`, `deleted`, `login`, `logout`, `login_failed`, `password_reset`, `exported_pdf`, `exported_excel`, `status_changed`) e scopes de consulta (`byUser`, `byAction`, `byModelType`, `dateRange`).

Para adicionar auditoria a um novo Model, basta usar a trait:

```php
use App\Traits\Auditable;

class NovoModel extends Model
{
    use Auditable;
}
```

#### Observers

O `UserObserver` (`app/Observers/UserObserver.php`) e registrado via atributo no Model:

```php
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
```

Eventos tratados:

| Evento | Acao |
|--------|------|
| `created` | Despacha job `SendWelcomeEmail` (fila assincrona) |
| `deleted` | Anonimizacao de dados para conformidade com LGPD |

Na exclusao (soft delete), o Observer anonimiza os dados pessoais usando `updateQuietly()` para nao disparar novos eventos de auditoria:

- Deleta o arquivo de avatar do storage
- Email → `deleted_{timestamp}_{id}@deleted.local`
- Nome → `Deleted {timestamp} {id}`
- CPF → ID do usuario com padding
- Avatar → `null`

#### Traits Auxiliares

- **`Auditable`** — Logging automatico de alteracoes (descrito acima)
- **`ExceptionHandlerTrait`** — Usado pelo Controller base. Envelopa operacoes com `handleWithTransaction()` / `handleWithoutTransaction()`, captura excecoes tipadas (`ValidationException` → 422, `BusinessException` → 400, `NotFoundException` → 404, `UnauthorizedException` → 401, `ForbiddenException` → 403) e faz rollback automatico

### Fluxo de autenticacao

```
Login (credenciais) → API valida → Seta cookies HttpOnly (access_token, refresh_token)
                                          ↓
Requisicoes seguintes → Browser envia cookies automaticamente
                                          ↓
Middleware CookieToToken → Converte cookie em header Authorization → Sanctum valida
```

O frontend nao armazena nem manipula tokens. O Axios esta configurado com `withCredentials: true` e o browser cuida da transmissao dos cookies.

### Criando um novo CRUD

#### Backend

1. Crie Model, Migration, Controller, Service e Repository seguindo os padroes existentes em `api/app/`
2. Adicione as rotas em `api/routes/api.php` dentro do grupo protegido
3. Registre as permissoes necessarias no banco

#### Frontend

Use o gerador:

```bash
cd front
node makeCrud.js nomeDaEntidade
```

Isso cria automaticamente:
- `src/constants/<entidades>.js` — Definicao de tabela, formulario e filtros
- `src/views/<entidades>/index.vue` — Pagina com componentes base
- Append em `src/composables/setSessions.js` — Configuracao de sessao/filtros
- Append em `src/router/routes.js` — Rota com `authRequired: true`

Ajuste os campos gerados conforme a entidade e crie o Service correspondente em `src/services/`.

### Comandos uteis

```bash
# Frontend
yarn dev              # Servidor de desenvolvimento
yarn build            # Build de producao
yarn lint             # Lint com auto-fix

# Backend
php artisan serve     # Servidor da API
php artisan migrate   # Executar migrations
php artisan test      # Executar todos os testes
php artisan test --filter=NomeDoTeste   # Executar teste especifico
composer dev          # Desenvolvimento simultaneo (server + queue + logs + vite)
```

---

<a id="english"></a>

## English

Full-stack boilerplate to kickstart new projects quickly. Ready-made structure with authentication, generic CRUD, permissions, audit logging, notifications and report exports — all fully integrated between frontend and backend.

### Stack

| Layer | Technology |
|-------|-----------|
| Frontend | Vue 3 (Composition API), Pinia, Vue Router, Bootstrap Vue 3, Axios, Vite |
| Backend | Laravel 12, PHP 8.2+, Laravel Sanctum |
| Database | MySQL |
| Reports | DOMPDF (PDF), PHPSpreadsheet (Excel) |

### What's included

- **HttpOnly cookie authentication** — Login, logout, token refresh and password recovery. No token exposed to the frontend (XSS protection).
- **Generic CRUD** — Base components (`Crud.vue`, `TableLists.vue`, `ModalForm.vue`, `Filter.vue`, `Actions.vue`) + `useCrud` composable + `BaseService` on the frontend. On the backend, `BaseService` with pagination, filters, search and file uploads.
- **Permissions (RBAC)** — `permission` middleware on the backend + `v-permission` directive on the frontend.
- **Audit logging** — Automatic change tracking via the Auditable Trait and Eloquent Observer.
- **Notifications** — In-app and email notification system.
- **Reports** — PDF and Excel export.
- **Kanban** — Kanban board view for goals/tasks.
- **Bulk operations** — Bulk delete and bulk status change.
- **CRUD generator** — `makeCrud.js` script that scaffolds constants, view, session and route for new entities.

### Project structure

```
setup-simplificado/
├── api/                  # Laravel backend
│   ├── app/
│   │   ├── Http/Controllers/Api/   # RESTful controllers
│   │   ├── Http/Middleware/        # Auth (cookie→token), permissions
│   │   ├── Models/                 # Eloquent models
│   │   ├── Services/               # Business logic layer
│   │   ├── Repositories/           # Data access layer
│   │   ├── Traits/                 # Auditable, ExceptionHandler
│   │   ├── Observers/              # UserObserver (email, LGPD)
│   │   ├── Jobs/                   # Queue jobs
│   │   └── Notifications/          # Email notifications
│   ├── database/migrations/
│   ├── routes/api.php              # API routes (v1)
│   └── config/                     # auth, cors, sanctum, etc.
│
└── front/                # Vue 3 frontend
    ├── src/
    │   ├── components/base/        # Reusable CRUD components
    │   ├── composables/            # useCrud, messages, functions, etc.
    │   ├── services/               # BaseService + entity services
    │   ├── stores/                 # Pinia (auth, layout, notification)
    │   ├── http/                   # Axios with withCredentials
    │   ├── router/                 # Routes with auth guards
    │   ├── views/                  # Pages organized by feature
    │   ├── directives/             # v-permission
    │   └── assets/scss/            # Corporate SCSS theme
    └── makeCrud.js                 # CRUD generator
```

### Getting started

#### Prerequisites

- Node.js 18+
- Yarn
- PHP 8.2+
- Composer
- MySQL

#### Backend

```bash
cd api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

The API will be available at `http://localhost:8000`.

#### Frontend

```bash
cd front
cp src/env.example.js src/env.js
# Uncomment and adjust the variables in src/env.js
yarn install
yarn dev
```

The frontend will be available at `http://localhost:8080`.

#### Concurrent development

From the `api/` directory, the following command starts the server, queue worker, logs and Vite all at once:

```bash
composer dev
```

### Backend Architecture: Controller → Service → Repository

The entire API follows a three-layer architecture with clear separation of concerns:

```
Request → Controller → Service → Repository → Model → Database
```

#### Base Classes

Each layer has an abstract base class that centralizes reusable logic:

- **`Controller`** (`app/Http/Controllers/Controller.php`) — Provides `index`, `store`, `show`, `update`, `destroy`, `changeActive`, `bulkDelete`, `bulkChangeActive`. Uses the `ExceptionHandlerTrait` to wrap operations in transactions (`handleWithTransaction`) with automatic rollback and standardized exception handling (422, 400, 404, 401, 403).
- **`BaseService`** (`app/Services/BaseService.php`) — Receives the repository via constructor. Implements full CRUD and a chained filter system (`$filtersOrder`) that declaratively applies text search, `where`, `whereNull`, `whereBetweenDate`, `whereInColumn`, eager loading (`with`), sorting and pagination.
- **`BaseRepository`** (`app/Repositories/BaseRepository.php`) — Receives the Eloquent Model via constructor. Encapsulates `all`, `find`, `create`, `update`, `delete`, `bulkDelete`, `changeActive`, `bulkChangeActive`, plus helpers like `select`, `with`, `whereBetween`, `firstOrCreate`.

Concrete controllers extend `Controller`, inject the corresponding Service and only override what differs from the standard CRUD. To create a new entity, follow the same pattern: Model, Migration, Repository, Service, Controller, FormRequest and routes.

#### Login System

The `LoginController` is standalone (does not extend the base Controller) and receives `CookieManager`, `UserService` and `AuditService`:

```
POST /login
  ↓ Validates email + password via Hash::check()
  ↓ Revokes previous tokens (Sanctum)
  ↓ Creates access_token (10 min) and refresh_token (24h)
  ↓ AuditService::logAuth() records the event
  ↓ CookieManager sets HttpOnly cookies (secure, SameSite=lax)
  ↓ Returns user data

POST /logout
  ↓ Revokes all tokens: $user->tokens()->delete()
  ↓ CookieManager clears cookies
  ↓ AuditService::logAuth() records the logout

POST /forgot-password
  ↓ Dispatches RecoverPasswordEmail job (async queue)

POST /recover-password
  ↓ Validates token via Password::broker()
  ↓ Updates password and clears reset tokens
  ↓ AuditService::logPasswordReset()
```

The `CookieManager` (`app/Services/CookieManager.php`) centralizes cookie creation and removal with `HttpOnly`, `Secure` and `SameSite` flags.

#### User CRUD

The `UserController` extends the base Controller and injects `UserService`. The creation flow illustrates all layers:

```
POST /api/v1/users
  ↓
UserController::store()
  ↓ validation() validates via UserRequest (FormRequest)
  ↓ handleWithTransaction() opens a transaction
  ↓
UserService::create(data)
  ↓ prepareUserData() — extracts nested data, hashes password
  ↓ UserRepository::create() — inserts into database
  ↓   Model created → fires 'created' event
  ↓     → Auditable Trait logs to AuditLog
  ↓     → UserObserver::created() dispatches SendWelcomeEmail job
  ↓ uploadAvatar() — saves image if provided
  ↓ saveAddress() — creates/updates UserAddress
  ↓ savePermissions() — syncs user_permissions pivot table
  ↓
Returns user with loaded relations
```

The `UserRepository::show()` method returns data structured as `basicInfo`, `address` and `permissions`, making it easy to consume on the frontend.

#### Audit Logging via Auditable Trait

The `Auditable` trait (`app/Traits/Auditable.php`) is a mixin that any Model can use. It automatically registers three Eloquent hooks on `boot`:

| Event | What it logs |
|-------|-------------|
| `created` | All attributes of the new record as `new_values` |
| `updated` | Only changed fields (`getDirty()`), saving both `old_values` and `new_values` |
| `deleted` | All original attributes as `old_values` |

Sensitive fields (`password`, `remember_token`, `updated_at`, `created_at`) are automatically excluded from the log.

Each `AuditLog` record stores: `user_id`, `user_name`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent` and `description`.

The `AuditLog` model defines constants for all action types (`created`, `updated`, `deleted`, `login`, `logout`, `login_failed`, `password_reset`, `exported_pdf`, `exported_excel`, `status_changed`) and query scopes (`byUser`, `byAction`, `byModelType`, `dateRange`).

To add audit logging to a new Model, just use the trait:

```php
use App\Traits\Auditable;

class NewModel extends Model
{
    use Auditable;
}
```

#### Observers

The `UserObserver` (`app/Observers/UserObserver.php`) is registered via attribute on the Model:

```php
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
```

Handled events:

| Event | Action |
|-------|--------|
| `created` | Dispatches `SendWelcomeEmail` job (async queue) |
| `deleted` | Data anonymization for LGPD (Brazilian GDPR) compliance |

On deletion (soft delete), the Observer anonymizes personal data using `updateQuietly()` to avoid triggering new audit events:

- Deletes the avatar file from storage
- Email → `deleted_{timestamp}_{id}@deleted.local`
- Name → `Deleted {timestamp} {id}`
- CPF → User ID with padding
- Avatar → `null`

#### Helper Traits

- **`Auditable`** — Automatic change logging (described above)
- **`ExceptionHandlerTrait`** — Used by the base Controller. Wraps operations with `handleWithTransaction()` / `handleWithoutTransaction()`, catches typed exceptions (`ValidationException` → 422, `BusinessException` → 400, `NotFoundException` → 404, `UnauthorizedException` → 401, `ForbiddenException` → 403) and performs automatic rollback

### Authentication Flow

```
Login (credentials) → API validates → Sets HttpOnly cookies (access_token, refresh_token)
                                              ↓
Subsequent requests → Browser sends cookies automatically
                                              ↓
CookieToToken Middleware → Converts cookie to Authorization header → Sanctum validates
```

The frontend never stores or handles tokens. Axios is configured with `withCredentials: true` and the browser handles cookie transmission.

### Creating a new CRUD

#### Backend

1. Create Model, Migration, Controller, Service and Repository following the existing patterns in `api/app/`
2. Add routes to `api/routes/api.php` inside the protected group
3. Register the required permissions in the database

#### Frontend

Use the generator:

```bash
cd front
node makeCrud.js entityName
```

This automatically creates:
- `src/constants/<entities>.js` — Table, form and filter definitions
- `src/views/<entities>/index.vue` — Page with base components
- Appends to `src/composables/setSessions.js` — Session/filter configuration
- Appends to `src/router/routes.js` — Route with `authRequired: true`

Adjust the generated fields for the entity and create the corresponding Service in `src/services/`.

### Useful commands

```bash
# Frontend
yarn dev              # Development server
yarn build            # Production build
yarn lint             # Lint with auto-fix

# Backend
php artisan serve     # API server
php artisan migrate   # Run migrations
php artisan test      # Run all tests
php artisan test --filter=TestName   # Run a single test
composer dev          # Concurrent development (server + queue + logs + vite)
```

---

## License / Licenca

MIT

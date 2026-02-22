# Tarefa 06 — Substituir README genérico da API

## Problema

O arquivo `api/README.md` ainda contém o texto padrão do Laravel:

> "Laravel is a web application framework with expressive, elegant syntax..."

Isso passa a impressão de que o desenvolvedor não customizou o projeto. Precisa ser substituído por documentação específica da API deste projeto.

## O que fazer

### Substituir `api/README.md` com conteúdo do projeto

O novo README deve conter:

#### 1. Título e descrição
Nome do projeto, stack (Laravel 12, PHP 8.2+, Sanctum, MySQL).

#### 2. Setup rápido
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

#### 3. Tabela de endpoints da API

Documentar as rotas organizadas por módulo:

**Autenticação (públicas):**
| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/v1/login` | Login com email/senha |
| POST | `/api/v1/forgot-password` | Solicitar recuperação de senha |
| POST | `/api/v1/recover-password` | Redefinir senha com token |

**Usuários (protegidas):**
| Método | Rota | Permissão |
|--------|------|-----------|
| GET | `/api/v1/users` | `users.index` |
| POST | `/api/v1/users` | `users.store` |
| GET | `/api/v1/users/{id}` | `users.show` |
| PUT | `/api/v1/users/{id}` | `users.update` |
| DELETE | `/api/v1/users/{id}` | `users.destroy` |
| PUT | `/api/v1/users/change-active/{id}` | `users.change-active` |
| POST | `/api/v1/users/bulk-delete` | `users.bulk-delete` |

E assim por diante para: Goals, Notifications, Audit Logs, Reports, Permissions, Enums.

#### 4. Middleware stack
Explicar a cadeia: `cookie.to.token` → `auth:sanctum` → `is.active` → `permission`

#### 5. Arquitetura
Diagrama simples da estrutura em camadas:
```
Request → Middleware → Controller → Service → Repository → Model → Database
```

#### 6. Testes
```bash
php artisan test                    # Rodar todos os 44 testes
php artisan test --filter=LoginTest # Rodar teste específico
```

#### 7. Seeders
Explicar os dados gerados pelo `php artisan db:seed`:
- 2 usuários master com todas as permissões
- ~28 usuários adicionais (admin, manager, user)
- Permissões hierárquicas por módulo
- Metas distribuídas nos últimos 12 meses

## Arquivos a modificar

1. `api/README.md` — substituir inteiramente

## Estimativa: ~1 hora

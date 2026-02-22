# Frontend — Setup Simplificado

**[Portugues](#portugues) | [English](#english)**

---

## Portugues

SPA construida com Vue 3 (Composition API), Pinia, Bootstrap Vue 3 e Axios. Autenticacao por cookies HttpOnly — o frontend nunca armazena ou manipula tokens.

### Stack

| Camada | Tecnologia |
|--------|-----------|
| Framework | Vue 3.2 (Composition API) |
| Roteamento | Vue Router 4 |
| Estado | Pinia 3 |
| HTTP | Axios (withCredentials) |
| UI | Bootstrap Vue 3, Bootstrap 5 |
| Build | Vite 4 |
| Testes | Vitest 4, @vue/test-utils |
| Graficos | ApexCharts |
| Notificacoes | Notivue, SweetAlert2 |

### Estrutura do projeto

```
front/
├── src/
│   ├── assets/
│   │   └── scss/                    # Tema SCSS corporativo
│   ├── components/
│   │   ├── base/                    # 34 componentes reutilizaveis
│   │   │   ├── PageBase.vue         #   Wrapper de pagina
│   │   │   ├── PageForm.vue         #   Wrapper de formulario
│   │   │   ├── Crud.vue             #   Interface CRUD completa
│   │   │   ├── TableLists.vue       #   Tabela com ordenacao/filtros
│   │   │   ├── Pagination.vue       #   Paginacao
│   │   │   ├── ModalForm.vue        #   Modal de formulario
│   │   │   ├── Filter.vue           #   Filtros avancados
│   │   │   ├── Actions.vue          #   Botoes de acao
│   │   │   ├── ChangeStatus.vue     #   Alternancia de status
│   │   │   ├── BulkActions.vue      #   Acoes em massa
│   │   │   ├── BaseMultiselect.vue  #   Input multiselect
│   │   │   ├── DocumentUploader.vue #   Upload de arquivos
│   │   │   ├── Cropper.vue          #   Recorte de imagem
│   │   │   ├── Editor.vue           #   Editor de texto rico
│   │   │   ├── KanbanBoard.vue      #   Quadro kanban
│   │   │   ├── GraphicColumns.vue   #   Grafico de colunas
│   │   │   ├── GraphicLine.vue      #   Grafico de linhas
│   │   │   ├── StatusMetricsCards.vue # Cards de metricas
│   │   │   ├── MapData.vue          #   Mapa (Leaflet)
│   │   │   ├── Avatar.vue           #   Avatar de usuario
│   │   │   ├── Badge.vue            #   Badge/tag
│   │   │   └── ...
│   │   └── widgets/                 # Widgets do dashboard
│   ├── composables/                 # 15 composables
│   │   ├── useCrud.js               #   Estado CRUD completo
│   │   ├── messages.js              #   notifySuccess(), notifyError()
│   │   ├── setSessions.js           #   Persistencia de filtros (localStorage)
│   │   ├── functions.js             #   Utilitarios (encoding, validacao)
│   │   ├── masks.js                 #   Mascaras de input (CPF, telefone)
│   │   ├── cruds.js                 #   Configuracao de CRUD
│   │   ├── convertVariables.js      #   Conversao de variaveis
│   │   ├── manageDates.js           #   Manipulacao de datas
│   │   ├── enumsData.js             #   Dados de enums
│   │   ├── useGlobalSpinner.js      #   Loading global
│   │   ├── useRegionalSpinner.js    #   Loading regional
│   │   ├── useBreakpoints.js        #   Breakpoints responsivos
│   │   └── __tests__/               #   Testes dos composables
│   ├── directives/
│   │   └── permission.js            # v-permission (visibilidade por permissao)
│   ├── http/
│   │   └── index.js                 # Axios com withCredentials + interceptors
│   ├── router/
│   │   └── index.js                 # Rotas com guards de autenticacao
│   ├── services/
│   │   ├── BaseService.js           # index(), getById(), save(), delete(), bulk...
│   │   ├── UserService.js
│   │   ├── AuditService.js
│   │   └── NotificationService.js
│   ├── stores/
│   │   ├── auth.js                  # Usuario, permissoes, enums, login/logout
│   │   ├── layout.js                # Sidebar, tema
│   │   ├── notification.js          # Gerenciamento de notificacoes
│   │   └── landingPage.js
│   ├── utils/
│   │   └── __tests__/               # Testes de utilitarios
│   └── views/
│       ├── account/                 # Login, recuperacao de senha
│       ├── audit/                   # Logs de auditoria
│       ├── dashboard/               # Dashboard
│       ├── notifications/           # Notificacoes
│       ├── profile/                 # Meu perfil
│       └── users/                   # Gestao de usuarios
│           └── form/                #   Formulario de usuario
├── src/env.js                       # Configuracao de ambiente (gitignored)
├── src/env.example.js               # Template de configuracao
├── makeCrud.js                      # Gerador de CRUD
├── vite.config.js
└── .eslintrc.cjs
```

### Instalacao

```bash
cd front
cp src/env.example.js src/env.js
# Ajuste as variaveis em src/env.js
yarn install
yarn dev
```

O frontend estara disponivel em `http://localhost:8080`.

### Configuracao de ambiente

Edite `src/env.js`:

```javascript
const env = {
    url: 'http://localhost:8000/',
    api: 'http://localhost:8000/api/v1/',
    baseUrl: 'http://localhost:8080/',
    title: 'Libertas',
}

export default env;
```

### Autenticacao

O frontend depende inteiramente de cookies HttpOnly setados pelo backend:

```
1. Usuario envia credenciais
              ↓
2. API valida e seta cookies HttpOnly (access_token, refresh_token)
              ↓
3. Axios envia cookies automaticamente (withCredentials: true)
              ↓
4. Refresh de token acontece de forma transparente no backend
```

O frontend nunca armazena nem manipula tokens. O browser cuida da transmissao dos cookies.

### Padrao de pagina CRUD

Uma pagina CRUD tipica combina:

1. **Arquivo de constantes** (`src/constants/`) — Colunas da tabela, valores padrao do formulario, filtros e tipos de acao
2. **View** (`src/views/`) — Pagina usando componentes base (`Filter`, `TableLists`, `ModalForm`, `Actions`, `ChangeStatus`)
3. **Sessao** (`src/composables/setSessions.js`) — Configuracao de persistencia de filtros
4. **Rota** (`src/router/`) — Rota com `meta: { authRequired: true }`

#### Gerador de CRUD

```bash
node makeCrud.js nomeDaEntidade
```

Gera automaticamente: constantes, view, sessao e rota.

### Camada de servico

Services estendem `BaseService.js` que fornece:

- `index()` — Listagem paginada
- `getById()` — Busca por ID
- `save()` — Criar ou atualizar
- `delete()` — Excluir
- `bulkDelete()` — Exclusao em massa
- `bulkChangeActive()` — Alteracao de status em massa

### Stores (Pinia)

| Store | Responsabilidade |
|-------|-----------------|
| `auth.js` | Usuario logado, permissoes, enums, login/logout/refresh |
| `layout.js` | Estado do sidebar, tema |
| `notification.js` | Gerenciamento de notificacoes |

### Diretiva de permissao

Controle de visibilidade baseado em permissoes do usuario:

```vue
<button v-permission="'users.create'">Novo Usuario</button>
```

### Testes

```bash
yarn test:run    # Todos os testes (101)
yarn test        # Modo watch
```

Os testes cobrem composables (`functions`, `masks`, `convertVariables`), stores (`auth`) e utilitarios (`permissions`).

### Comandos uteis

```bash
yarn install     # Instalar dependencias (usar yarn, nao npm)
yarn dev         # Servidor de desenvolvimento (porta 8080)
yarn build       # Build de producao
yarn lint        # ESLint com auto-fix
yarn test:run    # Executar testes
```

---

## English

SPA built with Vue 3 (Composition API), Pinia, Bootstrap Vue 3 and Axios. HttpOnly cookie authentication — the frontend never stores or handles tokens.

### Stack

| Layer | Technology |
|-------|-----------|
| Framework | Vue 3.2 (Composition API) |
| Routing | Vue Router 4 |
| State | Pinia 3 |
| HTTP | Axios (withCredentials) |
| UI | Bootstrap Vue 3, Bootstrap 5 |
| Build | Vite 4 |
| Testing | Vitest 4, @vue/test-utils |
| Charts | ApexCharts |
| Notifications | Notivue, SweetAlert2 |

### Project structure

```
front/
├── src/
│   ├── assets/
│   │   └── scss/                    # Corporate SCSS theme
│   ├── components/
│   │   ├── base/                    # 34 reusable components
│   │   │   ├── PageBase.vue         #   Page wrapper
│   │   │   ├── PageForm.vue         #   Form wrapper
│   │   │   ├── Crud.vue             #   Complete CRUD interface
│   │   │   ├── TableLists.vue       #   Table with sorting/filters
│   │   │   ├── Pagination.vue       #   Pagination
│   │   │   ├── ModalForm.vue        #   Form modal
│   │   │   ├── Filter.vue           #   Advanced filters
│   │   │   ├── Actions.vue          #   Action buttons
│   │   │   ├── ChangeStatus.vue     #   Status toggle
│   │   │   ├── BulkActions.vue      #   Bulk actions
│   │   │   ├── BaseMultiselect.vue  #   Multiselect input
│   │   │   ├── DocumentUploader.vue #   File upload
│   │   │   ├── Cropper.vue          #   Image cropping
│   │   │   ├── Editor.vue           #   Rich text editor
│   │   │   ├── KanbanBoard.vue      #   Kanban board
│   │   │   ├── GraphicColumns.vue   #   Column chart
│   │   │   ├── GraphicLine.vue      #   Line chart
│   │   │   ├── StatusMetricsCards.vue # Metric cards
│   │   │   ├── MapData.vue          #   Map (Leaflet)
│   │   │   ├── Avatar.vue           #   User avatar
│   │   │   ├── Badge.vue            #   Badge/tag
│   │   │   └── ...
│   │   └── widgets/                 # Dashboard widgets
│   ├── composables/                 # 15 composables
│   │   ├── useCrud.js               #   Complete CRUD state
│   │   ├── messages.js              #   notifySuccess(), notifyError()
│   │   ├── setSessions.js           #   Filter persistence (localStorage)
│   │   ├── functions.js             #   Utilities (encoding, validation)
│   │   ├── masks.js                 #   Input masks (CPF, phone)
│   │   └── __tests__/               #   Composable tests
│   ├── directives/
│   │   └── permission.js            # v-permission (permission-based visibility)
│   ├── http/
│   │   └── index.js                 # Axios with withCredentials + interceptors
│   ├── router/
│   │   └── index.js                 # Routes with auth guards
│   ├── services/
│   │   ├── BaseService.js           # index(), getById(), save(), delete(), bulk...
│   │   ├── UserService.js
│   │   ├── AuditService.js
│   │   └── NotificationService.js
│   ├── stores/
│   │   ├── auth.js                  # User, permissions, enums, login/logout
│   │   ├── layout.js                # Sidebar, theme
│   │   └── notification.js          # Notification management
│   └── views/
│       ├── account/                 # Login, password recovery
│       ├── audit/                   # Audit logs
│       ├── dashboard/               # Dashboard
│       ├── notifications/           # Notifications
│       ├── profile/                 # My profile
│       └── users/                   # User management
│           └── form/                #   User form
├── src/env.js                       # Environment config (gitignored)
├── src/env.example.js               # Config template
├── makeCrud.js                      # CRUD generator
├── vite.config.js
└── .eslintrc.cjs
```

### Installation

```bash
cd front
cp src/env.example.js src/env.js
# Adjust the variables in src/env.js
yarn install
yarn dev
```

The frontend will be available at `http://localhost:8080`.

### Environment configuration

Edit `src/env.js`:

```javascript
const env = {
    url: 'http://localhost:8000/',
    api: 'http://localhost:8000/api/v1/',
    baseUrl: 'http://localhost:8080/',
    title: 'Libertas',
}

export default env;
```

### Authentication

The frontend relies entirely on HttpOnly cookies set by the backend:

```
1. User submits credentials
              ↓
2. API validates and sets HttpOnly cookies (access_token, refresh_token)
              ↓
3. Axios sends cookies automatically (withCredentials: true)
              ↓
4. Token refresh happens transparently on the backend
```

The frontend never stores or handles tokens. The browser handles cookie transmission.

### CRUD page pattern

A typical CRUD page combines:

1. **Constants file** (`src/constants/`) — Table columns, form defaults, filters and action types
2. **View** (`src/views/`) — Page using base components (`Filter`, `TableLists`, `ModalForm`, `Actions`, `ChangeStatus`)
3. **Session** (`src/composables/setSessions.js`) — Filter persistence configuration
4. **Route** (`src/router/`) — Route with `meta: { authRequired: true }`

#### CRUD generator

```bash
node makeCrud.js entityName
```

Automatically generates: constants, view, session and route.

### Service layer

Services extend `BaseService.js` which provides:

- `index()` — Paginated listing
- `getById()` — Fetch by ID
- `save()` — Create or update
- `delete()` — Delete
- `bulkDelete()` — Bulk delete
- `bulkChangeActive()` — Bulk status change

### Stores (Pinia)

| Store | Responsibility |
|-------|---------------|
| `auth.js` | Logged user, permissions, enums, login/logout/refresh |
| `layout.js` | Sidebar state, theme |
| `notification.js` | Notification management |

### Permission directive

Permission-based visibility control:

```vue
<button v-permission="'users.create'">New User</button>
```

### Tests

```bash
yarn test:run    # All tests (101)
yarn test        # Watch mode
```

Tests cover composables (`functions`, `masks`, `convertVariables`), stores (`auth`) and utilities (`permissions`).

### Useful commands

```bash
yarn install     # Install dependencies (use yarn, not npm)
yarn dev         # Dev server (port 8080)
yarn build       # Production build
yarn lint        # ESLint with auto-fix
yarn test:run    # Run tests
```

---

## License / Licenca

MIT

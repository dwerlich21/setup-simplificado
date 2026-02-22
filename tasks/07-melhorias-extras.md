# Tarefa 07 — Melhorias extras (menor prioridade)

## Itens opcionais que agregam valor

Estas melhorias são menos urgentes mas diferenciam o projeto de outros repositórios similares.

---

### 7.1 — Página 404 no frontend

**Problema:** Acessar uma rota inexistente não mostra nada útil.

**Solução:** Criar um componente `NotFound.vue` e adicionar como catch-all no router:

```javascript
{ path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
```

**Arquivos:** `front/src/views/NotFound.vue`, `front/src/router/index.js`

---

### 7.2 — Makefile na raiz

**Problema:** Muitos comandos espalhados entre `composer`, `artisan`, `yarn`.

**Solução:** Criar um Makefile com atalhos:

```makefile
install:        ## Instalar dependências do backend e frontend
test:           ## Rodar todos os testes (backend + frontend)
test-back:      ## Rodar testes do backend
test-front:     ## Rodar testes do frontend
seed:           ## Rodar seeders
fresh:          ## Migrate fresh + seed
lint:           ## Lint do frontend
```

**Arquivo:** `Makefile`

---

### 7.3 — Badge de testes no README

**Problema:** Não há indicador visual de que os testes passam.

**Solução:** Após implementar a tarefa 01 (CI com testes), adicionar badge no topo do README:

```markdown
![Backend Tests](https://github.com/dwerlich21/setup-simplificado/actions/workflows/api-tests.yml/badge.svg)
![Frontend Tests](https://github.com/dwerlich21/setup-simplificado/actions/workflows/front-tests.yml/badge.svg)
```

**Depende de:** Tarefa 01

---

### 7.4 — CONTRIBUTING.md

**Problema:** Sem guia de contribuição, o projeto parece pessoal e não colaborativo.

**Solução:** Criar `CONTRIBUTING.md` com:
- Como clonar e configurar o ambiente
- Convenções de commit (Conventional Commits)
- Fluxo de branches (feature branches → PR → main)
- Como rodar os testes antes de submeter PR
- Padrões de código (ESLint, PSR-12)

**Arquivo:** `CONTRIBUTING.md`

---

### 7.5 — Swagger / Documentação interativa da API

**Problema:** As rotas da API só estão documentadas em código.

**Solução:** Instalar `darkaonline/l5-swagger` ou criar uma collection do Postman e exportar como JSON no repo.

Opção mais simples: exportar uma collection Postman em `docs/postman-collection.json` e mencionar no README.

**Arquivos:** `docs/postman-collection.json` ou configuração do Swagger

---

## Ordem recomendada de execução geral

```
Tarefa 04 (LICENSE)          — 5 min   ← fazer primeiro, é instantâneo
Tarefa 05 (GitHub topics)    — 5 min   ← fazer junto
Tarefa 01 (CI com testes)    — 30 min  ← o mais impactante tecnicamente
Tarefa 02 (Screenshots)      — 30 min  ← o mais impactante visualmente
Tarefa 06 (API README)       — 1 hora
Tarefa 03 (Docker Compose)   — 2 horas
Tarefa 07 (Extras)           — conforme sobrar tempo
```

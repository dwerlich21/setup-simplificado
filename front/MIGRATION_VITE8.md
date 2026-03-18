# Guia de Migração: Vite 4/5/6/7 → Vite 8

## O que mudou no Vite 8

O Vite 8 substitui **esbuild + Rollup** por **Rolldown** (bundler unificado em Rust). Isso traz builds mais rápidos e uma arquitetura simplificada.

Referências oficiais:
- Blog: https://vite.dev/blog/announcing-vite8
- Guia de migração: https://vite.dev/guide/migration

---

## Pré-requisitos

- **Node.js 20.19+** ou **22.12+**

Verifique com:
```bash
node --version
```

---

## Passo 1: Atualizar dependências

No `package.json`, atualize:

```json
{
  "devDependencies": {
    "vite": "^8.0.0",
    "@vitejs/plugin-vue": "^6.0.5"
  }
}
```

> **Nota:** O `vitest` tem versionamento independente e não precisa ser atualizado junto.

Depois instale:
```bash
yarn install --ignore-engines
```

O `--ignore-engines` pode ser necessário se dependências transitivas não declararem suporte ao seu Node.

---

## Passo 2: Migrar `vite.config.js`

### 2.1 — `rollupOptions` → `rolldownOptions`

O Vite 8 tem uma camada de compatibilidade que converte automaticamente, mas o ideal é migrar direto.

**Antes:**
```js
build: {
  rollupOptions: {
    output: { ... }
  }
}
```

**Depois:**
```js
build: {
  rolldownOptions: {
    output: { ... }
  }
}
```

### 2.2 — `manualChunks` objeto → função

O formato objeto foi **removido**. Converta para função:

**Antes:**
```js
manualChunks: {
  'vendor': ['vue', 'vue-router', 'pinia', 'axios'],
  'bootstrap': ['bootstrap', 'bootstrap-vue-3', '@popperjs/core'],
  'utils': ['lodash-es']
}
```

**Depois:**
```js
manualChunks(id) {
  if (id.includes('node_modules/vue/') || id.includes('node_modules/vue-router/') || id.includes('node_modules/pinia/') || id.includes('node_modules/axios/')) {
    return 'vendor'
  }
  if (id.includes('node_modules/bootstrap') || id.includes('node_modules/@popperjs/core')) {
    return 'bootstrap'
  }
  if (id.includes('node_modules/lodash-es')) {
    return 'utils'
  }
}
```

> **Dica:** Use `id.includes('node_modules/<pacote>')` para cada pacote que estava no array.

### 2.3 — `esbuild` → `oxc` (se usado)

Se você tinha configurações de `esbuild` no config:

| Antes | Depois |
|-------|--------|
| `esbuild.jsxInject` | `oxc.jsxInject` |
| `esbuild.jsx: 'preserve'` | `oxc.jsx: 'preserve'` |
| `esbuild.jsx: 'automatic'` | `oxc.jsx: { runtime: 'automatic' }` |
| `esbuild.jsx: 'transform'` | `oxc.jsx: { runtime: 'classic' }` |

### 2.4 — `optimizeDeps.esbuildOptions` → `optimizeDeps.rolldownOptions` (se usado)

| Antes | Depois |
|-------|--------|
| `esbuildOptions.define` | `rolldownOptions.transform.define` |
| `esbuildOptions.loader` | `rolldownOptions.moduleTypes` |

---

## Passo 3: Corrigir imports SCSS com `./node_modules/`

O Rolldown não resolve caminhos `./node_modules/` da mesma forma. Se seus arquivos SCSS importam assim:

```scss
// Antes (quebra no Vite 8)
@import "./node_modules/bootstrap/scss/functions";
```

Corrija para usar o `includePaths` (que já deve estar no config):

```scss
// Depois
@import "bootstrap/scss/functions";
```

Garanta que o `vite.config.js` tenha:
```js
css: {
  preprocessorOptions: {
    scss: {
      includePaths: ['node_modules']
    }
  }
}
```

Para encontrar todos os arquivos afetados:
```bash
grep -r '@import "./node_modules/' src/
```

---

## Passo 4: Verificar

```bash
# Dev server
yarn dev

# Build de produção
yarn build

# Testes
yarn test:run
```

---

## Configs que NÃO precisam mudar

- Aliases (`resolve.alias`) — funcionam normalmente
- `css.preprocessorOptions.scss` — compatível
- `server` / `proxy` — sem mudanças
- `optimizeDeps.include` — sem mudanças
- Código fonte (Vue components, services, etc.) — sem impacto

---

## Warnings esperados (podem ser ignorados)

| Warning | Motivo |
|---------|--------|
| `Use of direct eval` | Bibliotecas como `lottie-web` usam `eval()` internamente |
| `chunks larger than 500 kB` | Informativo — use code splitting se necessário |
| `PLUGIN_TIMINGS` | Informativo sobre tempo gasto em plugins |

---

## Troubleshooting

**Erro de engine no `yarn install`:**
```
error @some-package: The engine "node" is incompatible
```
Use `yarn install --ignore-engines`.

**SCSS não encontra bootstrap:**
Verifique se `includePaths: ['node_modules']` está no config e troque `./node_modules/` por caminho direto nos imports.

**CommonJS interop diferente:**
Se imports de módulos CJS mudaram de comportamento, adicione temporariamente:
```js
// vite.config.js
export default defineConfig({
  legacy: {
    inconsistentCjsInterop: true
  }
})
```

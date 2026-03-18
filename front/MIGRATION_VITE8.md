# Guia de Migracão: Vite 4/5/6/7 → Vite 8

Guia completo para migrar projetos Vue 3 + Bootstrap (template Velzon) para o Vite 8 com Rolldown.

**Resultado esperado:**
- Build ~3x mais rápido (de ~11s para ~3s)
- Bundler unificado Rust-based (Rolldown substitui esbuild + Rollup)
- Chunks otimizados, sem warnings de bundle grande

Referências oficiais:
- Blog: https://vite.dev/blog/announcing-vite8
- Guia de migração: https://vite.dev/guide/migration

---

## Pre-requisitos

- **Node.js 20.19+** ou **22.12+**

```bash
node --version
```

---

## Passo 1: Atualizar dependencias no `package.json`

### 1.1 — devDependencies

| Pacote | Antes | Depois |
|--------|-------|--------|
| `vite` | `^4.x` / `^5.x` / ... | `^8.0.0` |
| `@vitejs/plugin-vue` | `^4.x` / `^5.x` | `^6.0.5` |
| `sass` | `^1.x` | **Remover** |
| `sass-embedded` | (novo) | `^1.98.0` |

O `sass-embedded` usa o Dart VM nativo (binario) ao inves de compilar via JavaScript. Para projetos com muitos arquivos SCSS (como o template Velzon), o build SCSS fica **3-5x mais rapido**.

> **Nota:** `vitest` tem versionamento independente e nao precisa ser atualizado junto.

### 1.2 — Instalar

```bash
yarn install --ignore-engines
```

O `--ignore-engines` pode ser necessario se dependencias transitivas nao declararem suporte ao seu Node.

---

## Passo 2: Migrar `vite.config.js`

### 2.1 — `rollupOptions` → `rolldownOptions`

O Vite 8 tem camada de compatibilidade automatica, mas o ideal e migrar direto.

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

### 2.2 — `manualChunks` objeto → funcao

O formato objeto foi **removido** no Vite 8. Converta para funcao:

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

> **Regra:** para cada entrada do array antigo, use `id.includes('node_modules/<pacote>')`.

### 2.3 — Atualizar `silenceDeprecations` do SCSS

O `sass-embedded` (versao mais recente) marca `mixed-decls` como obsoleto. Troque por `if-function`:

**Antes:**
```js
silenceDeprecations: ['import', 'legacy-js-api', 'global-builtin', 'color-functions', 'mixed-decls']
```

**Depois:**
```js
silenceDeprecations: ['import', 'legacy-js-api', 'global-builtin', 'color-functions', 'if-function']
```

### 2.4 — `esbuild` → `oxc` (se usado)

Se voce tinha configuracoes de `esbuild` no config:

| Antes | Depois |
|-------|--------|
| `esbuild.jsxInject` | `oxc.jsxInject` |
| `esbuild.jsx: 'preserve'` | `oxc.jsx: 'preserve'` |
| `esbuild.jsx: 'automatic'` | `oxc.jsx: { runtime: 'automatic' }` |
| `esbuild.jsx: 'transform'` | `oxc.jsx: { runtime: 'classic' }` |

### 2.5 — `optimizeDeps.esbuildOptions` → `optimizeDeps.rolldownOptions` (se usado)

| Antes | Depois |
|-------|--------|
| `esbuildOptions.define` | `rolldownOptions.transform.define` |
| `esbuildOptions.loader` | `rolldownOptions.moduleTypes` |

### 2.6 — Exemplo completo do `vite.config.js` apos migracao

```js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      '~bootstrap': 'bootstrap',
      '~@': fileURLToPath(new URL('./src', import.meta.url)),
    },
    extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.vue']
  },
  server: {
    port: 8080,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      }
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        quietDeps: true,
        silenceDeprecations: ['import', 'legacy-js-api', 'global-builtin', 'color-functions', 'if-function'],
        includePaths: ['node_modules']
      }
    }
  },
  build: {
    rolldownOptions: {
      output: {
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
      }
    }
  }
})
```

---

## Passo 3: Corrigir imports SCSS com `./node_modules/`

O Rolldown nao resolve caminhos `./node_modules/` da mesma forma que o Rollup. Todos os imports SCSS que usam esse padrao vao quebrar.

**Encontrar todos os arquivos afetados:**
```bash
grep -r '@import "./node_modules/' src/
```

**Antes (quebra no Vite 8):**
```scss
@import "./node_modules/bootstrap/scss/functions";
@import "./node_modules/bootstrap/scss/variables";
@import "./node_modules/bootstrap/scss/mixins";
@import "./node_modules/bootstrap/scss/bootstrap";
```

**Depois:**
```scss
@import "bootstrap/scss/functions";
@import "bootstrap/scss/variables";
@import "bootstrap/scss/mixins";
@import "bootstrap/scss/bootstrap";
```

Isso funciona porque o `includePaths: ['node_modules']` no `vite.config.js` ja resolve o caminho.

**Arquivos tipicamente afetados no template Velzon:**
- `src/assets/scss/config/corporate/app.scss`
- `src/assets/scss/config/corporate/bootstrap.scss`
- `src/assets/scss/corporate/app.scss`
- `src/assets/scss/corporate/bootstrap.scss`

---

## Passo 4: Otimizar bibliotecas pesadas com lazy loading

Bibliotecas grandes que nao sao usadas em todas as paginas devem ser carregadas sob demanda (lazy) para reduzir o bundle inicial.

### 4.1 — lottie-web: usar versao light

O `lottie-web` tem uma versao light sem o motor de expressions (que usa `eval()` e gera warning no Rolldown).

**No componente `src/components/widgets/lottie.vue`:**

```js
// Antes
import lottie from 'lottie-web';

// Depois
import lottie from 'lottie-web/build/player/lottie_light';
```

Resultado: 625 kB → 389 kB (38% menor) e sem warning de `eval`.

### 4.2 — lottie-web: import dinamico nas paginas

Nas paginas que usam o componente Lottie (tipicamente ForgotPassword e Logout):

```js
// Antes
import Lottie from "@/components/widgets/lottie.vue";

// Depois
import { defineAsyncComponent } from 'vue';
const Lottie = defineAsyncComponent(() => import("@/components/widgets/lottie.vue"));
```

### 4.3 — ApexCharts: remover registro global

O `vue3-apexcharts` (~567 kB) registrado globalmente no `main.js` impede code-splitting.

**No `main.js`, remover:**
```js
// Remover estas linhas:
import VueApexCharts from "vue3-apexcharts";
// ...
.use(VueApexCharts)
```

**Em cada componente que usa `<apexchart>`, adicionar import local:**

Para `<script setup>` (StatusChart, UserChart, TimelineChart, etc.):
```js
import { defineAsyncComponent } from 'vue';
const apexchart = defineAsyncComponent(() => import("vue3-apexcharts"));
```

Para Options API (GraphicColumns, GraphicLine, etc.):
```js
import { defineAsyncComponent } from 'vue';
const VueApexCharts = defineAsyncComponent(() => import("vue3-apexcharts"));

export default {
  components: {
    apexchart: VueApexCharts,
  },
  // ...
}
```

**Encontrar todos os componentes que usam apexchart:**
```bash
grep -r '<apexchart' src/ --include="*.vue" -l
```

---

## Passo 5: Verificar

```bash
# Instalar
yarn install --ignore-engines

# Build de producao
yarn build

# Dev server
yarn dev

# Testes
yarn test:run
```

---

## Checklist rapido

- [ ] `vite` → `^8.0.0`
- [ ] `@vitejs/plugin-vue` → `^6.0.5`
- [ ] `sass` → `sass-embedded` (`^1.98.0`)
- [ ] `rollupOptions` → `rolldownOptions`
- [ ] `manualChunks` objeto → funcao
- [ ] `silenceDeprecations`: trocar `mixed-decls` por `if-function`
- [ ] SCSS: `./node_modules/bootstrap/` → `bootstrap/`
- [ ] `lottie-web` → `lottie-web/build/player/lottie_light`
- [ ] Lottie: import dinamico com `defineAsyncComponent`
- [ ] ApexCharts: remover `.use()` global, importar localmente
- [ ] `yarn build` sem erros
- [ ] `yarn dev` funcionando
- [ ] Testes passando

---

## Configs que NAO precisam mudar

- Aliases (`resolve.alias`) — funcionam normalmente
- `css.preprocessorOptions.scss` — compativel (exceto `silenceDeprecations`)
- `server` / `proxy` — sem mudancas
- `optimizeDeps.include` — sem mudancas
- Codigo fonte Vue (components, services, composables) — sem impacto
- `vitest` / `vitest.setup.js` — sem mudancas

---

## Troubleshooting

**Erro de engine no `yarn install`:**
```
error @some-package: The engine "node" is incompatible
```
Use `yarn install --ignore-engines`.

**SCSS nao encontra bootstrap:**
Verifique se `includePaths: ['node_modules']` esta no config e troque `./node_modules/` por caminho direto nos imports.

**Warning `PLUGIN_TIMINGS`:**
Informativo. O CSS sempre domina o build por causa do volume do Bootstrap + icones. Com `sass-embedded` isso ja esta otimizado.

**CommonJS interop diferente:**
Se imports de modulos CJS mudaram de comportamento, adicione temporariamente:
```js
export default defineConfig({
  legacy: {
    inconsistentCjsInterop: true
  }
})
```

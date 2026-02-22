# Tarefa 01 — CI rodando testes antes do deploy

## Problema

Os workflows do GitHub Actions (`api/.github/workflows/main.yml` e `front/.github/workflows/main.yml`) fazem deploy direto para o servidor sem rodar nenhum teste. Isso significa que código quebrado pode ir para produção. Temos 145 testes escritos (44 backend + 101 frontend) que são completamente ignorados pelo pipeline.

Qualquer recrutador que abrir a aba "Actions" do repositório vai perceber que não existe validação de qualidade antes do deploy.

## O que fazer

### Backend (`api/.github/workflows/main.yml`)

Adicionar um **job de testes** que roda **antes** do job de deploy. O job de deploy deve depender (`needs: test`) do job de testes passar.

O job de testes precisa:
- Usar PHP 8.2+ com extensão SQLite
- Rodar `composer install`
- Copiar `.env.example` para `.env` e gerar app key
- Executar `php artisan test`

### Frontend (`front/.github/workflows/main.yml`)

Mesmo padrão: adicionar um job de testes antes do deploy.

O job de testes precisa:
- Usar Node.js 20+
- Rodar `yarn install`
- Copiar `src/env.example.js` para `src/env.js`
- Executar `yarn lint`
- Executar `yarn test:run`
- Executar `yarn build` (valida que o build não quebra)

### Resultado esperado

- Push na `main` → roda testes → só faz deploy se testes passarem
- Badge de status visível no README (opcional, mas recomendado)

## Arquivos a modificar

1. `api/.github/workflows/main.yml`
2. `front/.github/workflows/main.yml`

## Estimativa: ~30 minutos

# Tarefa 03 — Docker Compose para desenvolvimento local

## Problema

O projeto não tem nenhuma configuração Docker. Para um recrutador, isso levanta a dúvida: "ele sabe Docker?". Além disso, qualquer pessoa que clonar o repo precisa instalar PHP, MySQL, Node.js manualmente. Docker Compose resolve isso com um único comando.

## O que fazer

### 1. Criar `docker-compose.yml` na raiz

Definir 3 serviços:

| Serviço | Imagem | Porta | Função |
|---------|--------|-------|--------|
| **api** | PHP 8.2 FPM + extensões | 8000 | Backend Laravel |
| **mysql** | MySQL 8.0 | 3306 | Banco de dados |
| **front** | Node 20 Alpine | 8080 | Frontend Vue 3 (dev server) |

### 2. Criar `api/Dockerfile`

```
FROM php:8.2-fpm

# Instalar extensões: pdo_mysql, bcmath, gd, zip
# Instalar Composer
# Copiar código
# Rodar composer install
```

### 3. Criar `front/Dockerfile`

```
FROM node:20-alpine

# Copiar código
# Rodar yarn install
# Expor porta 8080
# CMD yarn dev --host
```

### 4. Configurar volumes e rede

- Volume para código-fonte (hot reload em dev)
- Volume nomeado para dados do MySQL (persistência)
- Rede interna para comunicação entre serviços
- `.env` do Laravel apontando para o host `mysql` ao invés de `localhost`

### 5. Atualizar README

Adicionar seção "Quick Start com Docker":

```bash
git clone https://github.com/dwerlich21/setup-simplificado.git
cd setup-simplificado
docker-compose up -d
# API: http://localhost:8000
# Frontend: http://localhost:8080
```

### 6. Criar `.dockerignore`

Ignorar `vendor/`, `node_modules/`, `.git/`, `.env` nos builds.

## Arquivos a criar

1. `docker-compose.yml`
2. `api/Dockerfile`
3. `front/Dockerfile`
4. `api/.dockerignore`
5. `front/.dockerignore`

## Arquivos a modificar

1. `README.md` — adicionar seção Docker
2. `.gitignore` — se necessário

## Considerações

- O `vite.config.js` do frontend precisa de `server.host: '0.0.0.0'` para funcionar dentro do container
- O proxy do Vite (`/api → localhost:8000`) precisa apontar para o nome do serviço Docker (`api`)
- Variáveis de ambiente do MySQL devem ser configuradas via `environment` no compose

## Estimativa: ~2 horas

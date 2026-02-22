# Tarefa 02 — Screenshots e GIF demo no README

## Problema

O README tem 580 linhas de texto mas zero imagens. Um recrutador gasta 10-30 segundos olhando um repositório. Sem screenshots, ele não tem como saber como o projeto realmente se parece. Texto não vende — visual vende.

## O que fazer

### 1. Capturar screenshots das telas principais

Tirar prints das seguintes telas (com dados dos seeders para parecer realista):

| Tela | O que mostrar |
|------|---------------|
| **Login** | Tela de login com o formulário |
| **Dashboard** | Cards de estatísticas, gráficos ApexCharts |
| **Listagem de Usuários** | Tabela com dados, filtros, paginação, ações em bulk |
| **Modal de Criação** | Formulário de criar/editar usuário com validação |
| **Kanban de Metas** | Board com cards arrastáveis por status |
| **Audit Logs** | Tabela de logs com filtros por ação/modelo |
| **Permissões** | Tela de permissões hierárquicas (checkboxes) |

### 2. (Opcional) Gravar um GIF demo

Um GIF de 15-20 segundos mostrando o fluxo: login → dashboard → listagem → criar registro → arrastar no kanban. Ferramentas: [ScreenToGif](https://www.screentogif.com/) (Windows) ou `peek` (Linux).

### 3. Adicionar ao README

Criar uma pasta `docs/screenshots/` para as imagens. No README, adicionar uma seção "Preview" logo após a descrição do projeto, com as imagens em grid:

```markdown
## Preview

| Dashboard | Usuários |
|-----------|----------|
| ![Dashboard](docs/screenshots/dashboard.png) | ![Usuários](docs/screenshots/users.png) |

| Kanban | Audit Logs |
|--------|------------|
| ![Kanban](docs/screenshots/kanban.png) | ![Audit](docs/screenshots/audit.png) |
```

## Arquivos a criar/modificar

1. `docs/screenshots/` — pasta com as imagens
2. `README.md` — adicionar seção Preview

## Observação

Esta tarefa requer rodar o projeto localmente com dados dos seeders. Rodar:
```bash
cd api && php artisan migrate:fresh --seed
cd front && yarn dev
```

## Estimativa: ~30 minutos

# Tarefa 04 — Arquivo LICENSE na raiz

## Problema

O README menciona "MIT License" no rodapé, mas não existe um arquivo `LICENSE` na raiz do projeto. O GitHub não reconhece a licença sem esse arquivo — o repositório aparece como "sem licença" na sidebar, o que pode afastar quem quiser usar ou contribuir.

## O que fazer

### 1. Criar arquivo `LICENSE` na raiz do projeto

Usar o texto padrão da licença MIT com o ano e nome do autor:

```
MIT License

Copyright (c) 2025 Daniel Werlich

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

### 2. Verificar

Após o push, a sidebar do repositório no GitHub deve exibir "MIT License" automaticamente.

## Arquivos a criar

1. `LICENSE`

## Estimativa: ~5 minutos

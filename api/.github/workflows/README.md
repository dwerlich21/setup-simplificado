# Configuração do CD/CI - Deploy API na AWS

Este documento descreve como configurar os secrets necessários para o deploy automático da API Laravel na AWS EC2.

## Secrets Necessários no GitHub

Acesse: **Settings** → **Secrets and variables** → **Actions** → **New repository secret**

### 1. Credenciais AWS (para gerenciamento do Security Group)

#### `AWS_ACCESS_KEY_ID`
- **Descrição**: Access Key ID da conta AWS
- **Como obter**:
  1. Acesse AWS Console → IAM → Users
  2. Selecione seu usuário (ou crie um usuário específico para CI/CD)
  3. Vá em "Security credentials" → "Create access key"
  4. Selecione "Application running outside AWS"
  5. Copie o **Access key ID**

#### `AWS_SECRET_ACCESS_KEY`
- **Descrição**: Secret Access Key da conta AWS
- **Como obter**:
  - Obtido junto com o Access Key ID (só é exibido uma vez)
  - Copie e guarde em local seguro

#### `AWS_REGION`
- **Descrição**: Região da AWS onde está o EC2
- **Exemplo**: `us-east-2`, `us-east-1`, `sa-east-1`
- **Como descobrir**:
  - Acesse EC2 Console e veja no canto superior direito
  - Ou verifique na URL: `https://console.aws.amazon.com/ec2/v2/home?region=us-east-2`

#### `SECURITY_GROUP_ID`
- **Descrição**: ID do Security Group da instância EC2
- **Como obter**:
  1. Acesse EC2 Console → Instances
  2. Selecione sua instância
  3. Vá na aba "Security"
  4. Copie o ID do Security Group (formato: `sg-xxxxxxxxxxxxxxxxx`)

### 2. Credenciais SSH (para conectar ao servidor)

#### `SSH_HOST`
- **Descrição**: IP público ou DNS da instância EC2
- **Exemplo**: `3.136.154.79` ou `ec2-3-136-154-79.us-east-2.compute.amazonaws.com`
- **Como obter**:
  - EC2 Console → Instances → Selecione a instância
  - Copie o "Public IPv4 address" ou "Public IPv4 DNS"

#### `SSH_USERNAME`
- **Descrição**: Nome de usuário SSH no servidor
- **Padrão Ubuntu**: `ubuntu`
- **Padrão Amazon Linux**: `ec2-user`

#### `SSH_PRIVATE_KEY`
- **Descrição**: Chave privada SSH para autenticação
- **Como obter**:
  - Use a chave privada (.pem) que você baixou ao criar a instância EC2
  - Copie todo o conteúdo do arquivo (incluindo `-----BEGIN RSA PRIVATE KEY-----` e `-----END RSA PRIVATE KEY-----`)

  ```bash
  cat ~/.ssh/sua-chave.pem
  ```

#### `SSH_PORT` (opcional)
- **Descrição**: Porta SSH customizada (se não for 22)
- **Padrão**: `22`
- **Nota**: Só configure se você alterou a porta SSH padrão

### 3. Configurações do Projeto

#### `PROJECT_PATH`
- **Descrição**: Caminho completo do projeto no servidor
- **Exemplo**: `/var/www/citidash-api` ou `/home/ubuntu/citidash-api`

---

## Permissões Necessárias para o Usuário IAM AWS

O usuário IAM usado no CI/CD precisa das seguintes permissões:

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "ec2:AuthorizeSecurityGroupIngress",
        "ec2:RevokeSecurityGroupIngress"
      ],
      "Resource": "arn:aws:ec2:*:*:security-group/*"
    }
  ]
}
```

### Como configurar:
1. AWS Console → IAM → Users → Selecione o usuário
2. "Add permissions" → "Create inline policy"
3. Use o JSON acima
4. Nomeie como `GitHubActionsSecurityGroupManagement`

---

## Verificando se está funcionando

Após configurar todos os secrets:

1. Faça um commit no repositório
2. Push para a branch `main`
3. Acesse: **Actions** no GitHub
4. Acompanhe o workflow "Deploy API to VPS"
5. Verifique os logs:
   - ✅ "Adding runner IP to Security Group..."
   - ✅ "Deploying via SSH..."
   - ✅ "Removing runner IP from Security Group..."
   - ✅ "Deployment successful!"

---

## Troubleshooting

### Erro: "An error occurred (UnauthorizedOperation)"
- **Causa**: Usuário IAM sem permissões
- **Solução**: Adicione as permissões listadas acima

### Erro: "dial tcp: i/o timeout"
- **Causa**: Security Group ainda bloqueando ou servidor offline
- **Solução**:
  1. Verifique se o servidor está rodando
  2. Confirme que o `SECURITY_GROUP_ID` está correto
  3. Teste conexão SSH local: `ssh -i chave.pem ubuntu@IP`

### Erro: "Permission denied (publickey)"
- **Causa**: Chave SSH incorreta ou usuário errado
- **Solução**:
  1. Verifique se copiou a chave privada completa
  2. Confirme o `SSH_USERNAME` correto
  3. Teste local: `ssh -i chave.pem usuario@IP`

### Erro: "InvalidGroup.NotFound"
- **Causa**: `SECURITY_GROUP_ID` incorreto ou de outra região
- **Solução**: Confirme o ID correto e que está na mesma região (`AWS_REGION`)

---

## Segurança

### ✅ Boas Práticas Implementadas:
- ✅ IP do runner é adicionado dinamicamente (just-in-time access)
- ✅ IP é removido automaticamente após o deploy
- ✅ Usa chave SSH (não senha)
- ✅ Credenciais armazenadas como secrets (não no código)

### ⚠️ Recomendações Adicionais:
- Use um usuário IAM específico para CI/CD (não use root)
- Rotacione as chaves de acesso periodicamente
- Monitore logs do CloudTrail para atividades suspeitas
- Considere usar AWS Systems Manager Session Manager como alternativa ao SSH direto

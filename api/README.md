# 🍺 API Rigon Motor Bar - Versão Simplificada

API simples em PHP puro para gerenciamento de produtos do cardápio.

## 🚀 Características

- **PHP Puro**: Sem frameworks, simples e direto
- **CRUD Completo**: Create, Read, Update, Delete de produtos
- **Autenticação**: Sistema de login para administradores
- **JSON**: Todas as respostas em formato JSON
- **CORS**: Configurado para permitir requisições cross-origin

## 📋 Endpoints da API

### Autenticação

#### POST `/index.php?path=login`
**Login de usuário**
```json
{
  "username": "admin",
  "password": "admin123"
}
```

#### POST `/index.php?path=logout`
**Logout do usuário**

#### GET `/index.php?path=auth/check`
**Verificar status de autenticação**

### Produtos

#### GET `/index.php?path=produtos`
**Listar todos os produtos**
- Parâmetros opcionais:
  - `categoria`: Filtrar por categoria
  - `ativo`: 1 para ativos, 0 para inativos

#### GET `/index.php?path=produtos/categorias`
**Listar todas as categorias disponíveis**

#### GET `/index.php?path=produtos/id&id={id}`
**Buscar produto por ID**

#### POST `/index.php?path=produtos`
**Criar novo produto**
```json
{
  "nome": "Nome do Produto",
  "descricao": "Descrição do produto",
  "preco": 15.50,
  "categoria": "Drinks"
}
```

#### PUT `/index.php?path=produtos`
**Atualizar produto existente**
```json
{
  "id": 1,
  "nome": "Nome Atualizado",
  "descricao": "Descrição atualizada",
  "preco": 16.00,
  "categoria": "Drinks"
}
```

#### DELETE `/index.php?path=produtos?id={id}`
**Remover produto (soft delete)**

## 🗄️ Estrutura do Banco

### Tabela `usuarios`
- `id`: ID único
- `username`: Nome de usuário
- `password`: Senha criptografada
- `nome`: Nome completo
- `email`: Email do usuário
- `role`: Papel (admin/user)
- `ativo`: Status ativo/inativo
- `created_at`: Data de criação
- `last_login`: Último login
- `updated_at`: Data de atualização

### Tabela `produtos`
- `id`: ID único
- `nome`: Nome do produto
- `descricao`: Descrição detalhada
- `preco`: Preço em reais
- `categoria`: Categoria do produto
- `imagem`: Nome do arquivo de imagem
- `ativo`: Status ativo/inativo
- `created_at`: Data de criação
- `updated_at`: Data de atualização

## 🔧 Configuração

1. **Banco de dados**: Configure as credenciais em `config.php`
2. **Tabelas**: Execute o script `database/schema.sql`
3. **Usuário admin**: Usuário padrão `admin` com senha `admin123`

## 📱 Teste da API

Use o arquivo `test-api.html` para testar todas as funcionalidades da API:

1. Abra `test-api.html` no navegador
2. Faça login com usuário `admin` e senha `admin123`
3. Teste as operações CRUD nos produtos

## 🌐 Produção

Para usar em produção no domínio `https://rigonmotorbar.com.br/`:

1. **Upload dos arquivos**:
   - `index.php` → `/public_html/api/`
   - `config.php` → `/public_html/api/`
   - `test-api.html` → `/public_html/api/`

2. **Configuração do banco**:
   - Atualize `config.php` com credenciais de produção
   - Execute o script SQL para criar as tabelas

3. **Segurança**:
   - Altere a senha padrão do admin
   - Configure HTTPS
   - Considere adicionar rate limiting

## 📝 Exemplos de Uso

### JavaScript/Fetch
```javascript
// Login
const response = await fetch('index.php?path=login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    username: 'admin',
    password: 'admin123'
  })
});

// Listar produtos
const produtos = await fetch('index.php?path=produtos');
```

### cURL
```bash
# Login
curl -X POST "index.php?path=login" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'

# Listar produtos
curl "index.php?path=produtos"
```

## 🚨 Notas Importantes

- **Soft Delete**: Produtos removidos são marcados como inativos, não excluídos
- **Sessões**: Usar sessões PHP para autenticação
- **Validação**: Validação básica de entrada implementada
- **CORS**: Configurado para permitir requisições de qualquer origem

## 🔒 Segurança

- Senhas criptografadas com `password_hash()`
- Validação de entrada para prevenir SQL injection
- Verificação de permissões para operações administrativas
- Sessões seguras para autenticação

## 📞 Suporte

Para dúvidas ou problemas, verifique:
1. Logs de erro do PHP
2. Configuração do banco de dados
3. Permissões de arquivo no servidor
4. Configuração CORS se necessário

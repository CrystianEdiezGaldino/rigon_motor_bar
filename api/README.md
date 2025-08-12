# 🍸 Sistema CRUD - Rigon Motor Bar

Sistema completo de gerenciamento de cardápio com autenticação e banco de dados MySQL.

## 🚀 Funcionalidades

- **Sistema de Login** com autenticação segura
- **CRUD Completo** para produtos do cardápio
- **Painel Administrativo** responsivo e intuitivo
- **API REST** com endpoints seguros
- **Banco de Dados MySQL** com estrutura otimizada

## 📋 Pré-requisitos

### Software Necessário
- **XAMPP** ou **WAMP** (Apache + MySQL + PHP)
- **PHP 7.4+** com extensões PDO e MySQL
- **MySQL 5.7+** ou **MariaDB 10.2+**
- **Node.js 16+** e **npm**

### Configurações do Banco
- **Host**: localhost
- **Database**: rigon
- **Username**: root
- **Password**: The-dark3

## 🗄️ Estrutura do Banco de Dados

### Tabela: usuarios
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- username (VARCHAR(50), UNIQUE)
- password (VARCHAR(255)) - Hash bcrypt
- nome (VARCHAR(100))
- email (VARCHAR(100), UNIQUE)
- role (ENUM: 'admin', 'user')
- ativo (TINYINT(1))
- created_at (TIMESTAMP)
- last_login (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabela: produtos
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- nome (VARCHAR(100))
- descricao (TEXT)
- preco (DECIMAL(10,2))
- categoria (VARCHAR(50))
- imagem (VARCHAR(255))
- ativo (TINYINT(1))
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## ⚙️ Instalação

### 1. Configurar Banco de Dados
```bash
# Acesse o phpMyAdmin ou MySQL CLI
# Execute o script: api/database/schema.sql
```

### 2. Configurar Servidor Web
```bash
# Copie a pasta 'api' para o diretório do seu servidor web
# Exemplo: C:\xampp\htdocs\rigon\api\
```

### 3. Configurar Frontend
```bash
# No diretório raiz do projeto
npm install
npm run dev
```

## 🔐 Sistema de Autenticação

### Usuário Padrão
- **Username**: admin
- **Password**: admin123
- **Role**: admin

### Endpoints de Autenticação
- `POST /api/endpoints/auth.php` - Login/Logout/Registro
- `GET /api/endpoints/auth.php` - Verificar status da sessão

### Ações Disponíveis
- `action: 'login'` - Fazer login
- `action: 'logout'` - Fazer logout
- `action: 'register'` - Registrar novo usuário (apenas admin)

## 📡 API Endpoints

### Produtos
- `GET /api/endpoints/produtos.php` - Listar todos os produtos
- `GET /api/endpoints/produtos.php?id=X` - Buscar produto específico
- `GET /api/endpoints/produtos.php?categoria=X` - Buscar por categoria
- `POST /api/endpoints/produtos.php` - Criar novo produto
- `PUT /api/endpoints/produtos.php` - Atualizar produto
- `DELETE /api/endpoints/produtos.php` - Deletar produto (soft delete)

### Segurança
- Todos os endpoints de produtos requerem autenticação
- Validação de sessão em todas as operações
- Sanitização de dados de entrada
- Prepared statements para prevenir SQL injection

## 🎯 Como Usar

### 1. Acessar o Painel Admin
```
http://localhost/rigon/admin
```

### 2. Fazer Login
- Usuário: admin
- Senha: admin123

### 3. Gerenciar Produtos
- **Criar**: Clique em "Novo Produto"
- **Editar**: Clique em "Editar" na linha do produto
- **Deletar**: Clique em "Deletar" na linha do produto

### 4. Visualizar Cardápio
```
http://localhost:5173/menu
```

## 🔧 Configurações

### Arquivo de Configuração
```php
// api/config/database.php
private $host = "localhost";
private $db_name = "rigon";
private $username = "root";
private $password = "The-dark3";
```

### Categorias Disponíveis
- Drinks
- Cervejas
- Destilados
- Vinhos
- Refrigerantes
- Petiscos
- Pratos
- Sobremesas
- Outros

## 🚨 Troubleshooting

### Erro de Conexão com Banco
- Verifique se o MySQL está rodando
- Confirme as credenciais em `database.php`
- Teste a conexão via phpMyAdmin

### Erro de Permissões
- Verifique se o Apache tem acesso à pasta `api`
- Confirme as permissões de escrita para uploads

### Erro de Sessão
- Verifique se o PHP tem suporte a sessões
- Confirme se o diretório de sessões é gravável

## 📱 Responsividade

O painel administrativo é totalmente responsivo e funciona em:
- 📱 Dispositivos móveis
- 💻 Tablets
- 🖥️ Desktops

## 🔒 Segurança

- **Hash de senhas** com bcrypt
- **Validação de sessão** em todas as operações
- **Sanitização de dados** de entrada
- **Prepared statements** para prevenir SQL injection
- **Controle de acesso** baseado em roles

## 📈 Performance

- **Índices otimizados** no banco de dados
- **Queries preparadas** para melhor performance
- **Paginação** para grandes volumes de dados
- **Cache de sessão** para usuários logados

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT.

---

**Desenvolvido por:** Crystian Ediez Galdino  
**Projeto:** Rigon Motor Bar  
**Versão:** 1.0.0

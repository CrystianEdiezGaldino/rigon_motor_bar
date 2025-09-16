# 🚀 Instruções de Deploy para Produção

## 📋 Pré-requisitos
- ✅ Domínio: https://rigonmotorbar.com.br/
- ✅ Hosting com suporte a PHP 8.0+
- ✅ Banco MySQL
- ✅ Acesso FTP/SFTP ou cPanel

## 🔧 Passos para Deploy

### 1. Preparar os arquivos da API
```
api/
├── config/
│   └── database.php (ATUALIZAR CREDENCIAIS)
├── endpoints/
│   ├── auth.php
│   ├── produtos.php
│   └── cardapio-publico.php
├── models/
│   ├── Produto.php
│   └── Usuario.php
├── database/
│   └── schema.sql
├── .htaccess
├── index.php
├── test.php
├── test-database.php
├── 404.json
└── 500.json
```

### 2. Atualizar configuração do banco
Editar `api/config/database.php`:
```php
private $host = "localhost"; // ou IP do servidor
private $db_name = "rigon"; // nome do banco no hosting
private $username = "seu_usuario"; // usuário do banco
private $password = "sua_senha"; // senha do banco
```

### 3. Upload via FTP
- Conectar ao servidor via FTP
- Navegar para pasta `public_html/` ou `www/`
- Criar pasta `api/`
- Upload de todos os arquivos da API

### 4. Configurar banco de dados
- Acessar phpMyAdmin do hosting
- Criar banco `rigon`
- Importar `api/database/schema.sql`

### 5. Testar endpoints
```
https://rigonmotorbar.com.br/api/index.php
https://rigonmotorbar.com.br/api/test.php
https://rigonmotorbar.com.br/api/test-database.php
https://rigonmotorbar.com.br/api/endpoints/cardapio-publico.php
```

## 🌐 Configuração do Frontend

### 1. Build para produção
```bash
npm run build
```

### 2. Deploy do frontend
- Upload da pasta `dist/` para o hosting
- Configurar domínio para apontar para esta pasta

### 3. Configuração de ambiente
O frontend detecta automaticamente se está em desenvolvimento ou produção:
- **Dev**: `http://localhost/rigon/api`
- **Prod**: `https://rigonmotorbar.com.br/api`

## 🔒 Segurança

### 1. HTTPS obrigatório
- Certificado SSL ativo
- Redirecionamento HTTP → HTTPS

### 2. Headers de segurança
- CORS configurado
- XSS Protection
- Content Type Options

### 3. Banco de dados
- Usuário com privilégios mínimos
- Senha forte
- Backup regular

## 📱 Testes pós-deploy

### 1. Funcionalidades básicas
- ✅ Carregamento do cardápio
- ✅ Login administrativo
- ✅ CRUD de produtos
- ✅ Testes de API

### 2. Performance
- ✅ Tempo de resposta < 2s
- ✅ Compressão GZIP ativa
- ✅ Cache funcionando

### 3. Segurança
- ✅ HTTPS funcionando
- ✅ Headers de segurança
- ✅ CORS configurado

## 🆘 Suporte

Em caso de problemas:
1. Verificar logs do servidor
2. Testar endpoints individualmente
3. Verificar configuração do banco
4. Contatar suporte do hosting

## 📞 Contato
- **Desenvolvedor**: Crystian Ediez Galdino
- **Projeto**: Rigon Motor Bar
- **Domínio**: https://rigonmotorbar.com.br/

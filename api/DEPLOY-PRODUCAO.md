# 🚀 Deploy em Produção - Rigon Motor Bar

Instruções completas para colocar a API em produção no domínio `https://rigonmotorbar.com.br/`

## 📋 Pré-requisitos

- ✅ Domínio configurado e apontando para o servidor
- ✅ Servidor web (Apache/Nginx) configurado
- ✅ MySQL/MariaDB instalado e configurado
- ✅ PHP 7.4+ instalado com extensões PDO e MySQL
- ✅ SSL/HTTPS configurado (obrigatório para produção)

## 🔧 Passo a Passo

### 1. Preparar o Servidor

#### A. Acessar o servidor via SSH ou painel de controle
```bash
# Via SSH (se disponível)
ssh usuario@rigonmotorbar.com.br

# Ou via painel de controle (cPanel, Plesk, etc.)
```

#### B. Verificar requisitos do PHP
```bash
php -v
php -m | grep -E "(pdo|mysql)"
```

### 2. Configurar Banco de Dados

#### A. Acessar MySQL
```bash
mysql -u root -p
```

#### B. Criar banco e usuário de produção
```sql
-- Criar banco de dados
CREATE DATABASE rigon_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Criar usuário específico para produção
CREATE USER 'rigon_user'@'localhost' IDENTIFIED BY 'SUA_SENHA_SUPER_FORTE_AQUI';

-- Conceder permissões necessárias
GRANT SELECT, INSERT, UPDATE, DELETE ON rigon_prod.* TO 'rigon_user'@'localhost';

-- Aplicar permissões
FLUSH PRIVILEGES;

-- Verificar usuário criado
SELECT User, Host FROM mysql.user WHERE User = 'rigon_user';
```

#### C. Executar script de criação das tabelas
```sql
USE rigon_prod;

-- Executar o conteúdo do arquivo database/schema.sql
-- Copie e cole o conteúdo aqui
```

### 3. Upload dos Arquivos

#### A. Via FTP/SFTP
```bash
# Conectar via FTP
ftp rigonmotorbar.com.br

# Navegar para diretório público
cd public_html

# Criar pasta api
mkdir api
cd api

# Upload dos arquivos
put index.php
put config.php
put test-api.html
put README.md
```

#### B. Via cPanel File Manager
1. Acessar cPanel → File Manager
2. Navegar para `public_html`
3. Criar pasta `api`
4. Upload dos arquivos:
   - `index.php`
   - `config.php`
   - `test-api.html`
   - `README.md`

### 4. Configurar Arquivo de Configuração

#### A. Editar `config.php` para produção
```php
<?php
// Configuração do banco de dados para PRODUÇÃO
return [
    'host' => 'localhost',
    'dbname' => 'rigon_prod',
    'username' => 'rigon_user',
    'password' => 'SUA_SENHA_SUPER_FORTE_AQUI',
    'charset' => 'utf8mb4'
];
?>
```

#### B. Verificar permissões dos arquivos
```bash
chmod 644 index.php
chmod 644 config.php
chmod 644 test-api.html
chmod 644 README.md
chmod 755 api/
```

### 5. Configurar Servidor Web

#### A. Apache (.htaccess)
Criar arquivo `.htaccess` na pasta `api/`:
```apache
RewriteEngine On

# Forçar HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Headers de segurança
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# Headers CORS
Header always set Access-Control-Allow-Origin "*"
Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
Header always set Access-Control-Allow-Headers "Content-Type, Authorization"

# Compressão GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache de arquivos estáticos
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>
```

#### B. Nginx (se usar)
```nginx
server {
    listen 80;
    server_name rigonmotorbar.com.br;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name rigonmotorbar.com.br;
    
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    
    root /var/www/rigonmotorbar.com.br/public_html;
    index index.php index.html;
    
    location /api/ {
        try_files $uri $uri/ /api/index.php?$query_string;
        
        # Headers CORS
        add_header Access-Control-Allow-Origin *;
        add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS";
        add_header Access-Control-Allow-Headers "Content-Type, Authorization";
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Testar a API

#### A. Testar endpoints básicos
```bash
# Testar se a API está respondendo
curl https://rigonmotorbar.com.br/api/index.php?path=produtos

# Testar login
curl -X POST "https://rigonmotorbar.com.br/api/index.php?path=login" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'
```

#### B. Testar via navegador
1. Acessar: `https://rigonmotorbar.com.br/api/test-api.html`
2. Fazer login com usuário `admin` e senha `admin123`
3. Testar todas as funcionalidades CRUD

### 7. Configurações de Segurança

#### A. Alterar senha padrão do admin
```sql
USE rigon_prod;

-- Gerar nova senha hash
-- Use: https://www.php.net/manual/en/function.password-hash.php
UPDATE usuarios 
SET password = '$2y$10$NOVO_HASH_AQUI' 
WHERE username = 'admin';
```

#### B. Configurar firewall
```bash
# Permitir apenas portas necessárias
ufw allow 22    # SSH
ufw allow 80    # HTTP
ufw allow 443   # HTTPS
ufw enable
```

#### C. Configurar backup automático
```bash
# Criar script de backup
nano /root/backup-rigon.sh

# Conteúdo do script:
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u rigon_user -p rigon_prod > /backup/rigon_$DATE.sql
gzip /backup/rigon_$DATE.sql

# Agendar backup diário
crontab -e
# Adicionar linha:
0 2 * * * /root/backup-rigon.sh
```

### 8. Monitoramento

#### A. Configurar logs
```bash
# Verificar logs do Apache
tail -f /var/log/apache2/access.log
tail -f /var/log/apache2/error.log

# Verificar logs do PHP
tail -f /var/log/php7.4-fpm.log
```

#### B. Configurar alertas
```bash
# Script para verificar se a API está funcionando
nano /root/check-api.sh

# Conteúdo:
#!/bin/bash
if ! curl -s https://rigonmotorbar.com.br/api/index.php?path=produtos > /dev/null; then
    echo "API fora do ar em $(date)" | mail -s "ALERTA: API Rigon" seu@email.com
fi
```

### 9. Verificação Final

#### ✅ Checklist de Verificação
- [ ] API respondendo em `https://rigonmotorbar.com.br/api/`
- [ ] Banco de dados conectando corretamente
- [ ] Login funcionando
- [ ] CRUD de produtos funcionando
- [ ] HTTPS configurado e funcionando
- [ ] Senha padrão alterada
- [ ] Backup configurado
- [ ] Logs configurados
- [ ] Firewall configurado

## 🚨 Problemas Comuns

### Erro de conexão com banco
- Verificar credenciais em `config.php`
- Verificar se usuário MySQL tem permissões
- Verificar se banco existe

### Erro 500
- Verificar logs de erro do PHP
- Verificar permissões dos arquivos
- Verificar sintaxe PHP

### CORS não funcionando
- Verificar headers no `.htaccess`
- Verificar configuração do servidor web

## 📞 Suporte

Se encontrar problemas:
1. Verificar logs de erro
2. Testar endpoints individualmente
3. Verificar configuração do servidor
4. Consultar documentação do PHP/MySQL

## 🔄 Atualizações

Para atualizar a API:
1. Fazer backup do banco
2. Upload dos novos arquivos
3. Testar em ambiente de staging
4. Aplicar em produção
5. Verificar funcionamento

---

**⚠️ IMPORTANTE**: Sempre teste em ambiente de desenvolvimento antes de aplicar em produção!

# 📄 Sistema de Upload de Cardápio - Rigon Motorbar

## 🎯 Funcionalidades

- **Upload de PDF**: Substitui o cardápio atual mantendo o mesmo nome
- **Validação**: Verifica tipo de arquivo e tamanho
- **Backup automático**: Cria backup do arquivo anterior
- **Interface amigável**: Página HTML para upload fácil
- **Logs**: Registra todas as atualizações

## 📁 Arquivos Criados

### `upload-cardapio.php`
- **Função**: API para receber e processar uploads
- **Validações**: 
  - Apenas arquivos PDF
  - Máximo 10MB
  - Verificação de permissões
- **Recursos**:
  - Backup automático
  - Log de atividades
  - Resposta JSON

### `test-upload.html`
- **Função**: Interface para upload
- **Recursos**:
  - Drag & drop
  - Barra de progresso
  - Validação em tempo real
  - Design responsivo

## 🚀 Como Usar

### 1. Acesso via Navegador
```
http://seu-dominio.com/api/test-upload.html
```

### 2. Upload via API (Programático)
```javascript
const formData = new FormData();
formData.append('cardapio_pdf', fileInput.files[0]);

fetch('upload-cardapio.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

## ⚙️ Configurações

### Permissões de Arquivo
- **Diretório**: `../public/assets/imagens/`
- **Arquivo**: `cardapio.pdf`
- **Permissões**: 644 (leitura/escrita)

### Validações
- **Tipo**: `application/pdf`
- **Tamanho**: Máximo 10MB
- **Backup**: Automático com timestamp

## 📊 Respostas da API

### Sucesso
```json
{
    "success": true,
    "message": "Cardápio atualizado com sucesso!",
    "filename": "cardapio.pdf",
    "size": 2048576,
    "backup_created": true,
    "backup_file": "cardapio_backup_2024-01-15_14-30-25.pdf"
}
```

### Erro
```json
{
    "error": "Apenas arquivos PDF são permitidos"
}
```

## 🔒 Segurança

- **Validação de tipo**: Verifica MIME type real
- **Limite de tamanho**: 10MB máximo
- **Backup**: Preserva arquivo anterior
- **Logs**: Registra todas as operações
- **Permissões**: Verifica permissões de escrita

## 📝 Logs

Os logs são salvos em: `../public/assets/imagens/upload_log.txt`

Formato:
```
2024-01-15 14:30:25 - Cardápio atualizado. Arquivo: novo_cardapio.pdf (2048576 bytes)
```

## 🛠️ Manutenção

### Limpeza de Backups
```bash
# Remover backups antigos (mais de 30 dias)
find ../public/assets/imagens/ -name "cardapio_backup_*.pdf" -mtime +30 -delete
```

### Verificar Logs
```bash
tail -f ../public/assets/imagens/upload_log.txt
```

## 🎨 Interface

- **Design**: Moderno com gradientes
- **Responsivo**: Funciona em mobile e desktop
- **UX**: Drag & drop, progress bar, validação em tempo real
- **Cores**: Tema laranja (#F45F0A) do Rigon Motorbar

## 🔧 Troubleshooting

### Erro: "Diretório não tem permissão de escrita"
```bash
chmod 755 ../public/assets/imagens/
```

### Erro: "Arquivo muito grande"
- Verificar limite de upload no PHP
- Ajustar `upload_max_filesize` e `post_max_size`

### Erro: "Apenas arquivos PDF são permitidos"
- Verificar se o arquivo é realmente um PDF
- Testar com outro arquivo PDF

## 📞 Suporte

Para problemas ou dúvidas, verificar:
1. Logs de upload
2. Permissões de arquivo
3. Configurações do PHP
4. Tamanho do arquivo

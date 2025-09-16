<?php
// Configuração do banco de dados para PRODUÇÃO
// ⚠️ ATENÇÃO: Este arquivo contém configurações sensíveis
// Não commite este arquivo no controle de versão

return [
    'host' => 'localhost', // ou IP do servidor de banco
    'dbname' => 'delitc66_rigon_motor_bar', // nome do banco em produção
    'username' => 'delitc66_rigon_motor_bar', // usuário específico para produção
    'password' => 'z[Q_kcVxD1I&', // ⚠️ ALTERE ESTA SENHA
    'charset' => 'utf8mb4'
];

// 🔒 RECOMENDAÇÕES DE SEGURANÇA PARA PRODUÇÃO:
// 1. Crie um usuário específico no MySQL com permissões limitadas
// 2. Use uma senha forte e única
// 3. Configure o MySQL para aceitar apenas conexões locais
// 4. Ative o modo SSL se possível
// 5. Configure backup automático do banco
// 6. Monitore logs de acesso e erro

// 📝 COMANDOS MYSQL PARA CONFIGURAR USUÁRIO DE PRODUÇÃO:
/*
CREATE DATABASE rigon_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'rigon_user'@'localhost' IDENTIFIED BY 'SUA_SENHA_FORTE_AQUI';
GRANT SELECT, INSERT, UPDATE, DELETE ON rigon_prod.* TO 'rigon_user'@'localhost';
FLUSH PRIVILEGES;
*/
?>

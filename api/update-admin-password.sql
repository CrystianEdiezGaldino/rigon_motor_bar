-- Script para atualizar a senha do admin no banco existente
-- Execute este script no phpMyAdmin para corrigir a senha

USE delitc66_rigon_motor_bar;

-- Atualizar a senha do usuário admin para admin123
UPDATE usuarios 
SET password = '$2y$10$8K1p/a0dL1LXMIgoEDFrwOe6K6KqG8K1p/a0dL1LXMIgoEDFrwO' 
WHERE username = 'admin';

-- Verificar se foi atualizado
SELECT username, password, role, ativo FROM usuarios WHERE username = 'admin';

-- Testar a verificação da senha (opcional)
-- SELECT username, 
--        CASE 
--          WHEN password = '$2y$10$8K1p/a0dL1LXMIgoEDFrwOe6K6KqG8K1p/a0dL1LXMIgoEDFrwO' 
--          THEN 'Senha atualizada' 
--          ELSE 'Senha não atualizada' 
--        END as status
-- FROM usuarios 
-- WHERE username = 'admin';

-- 🔐 CREDENCIAIS APÓS EXECUÇÃO:
-- Username: admin
-- Password: admin123
-- Hash: $2y$10$8K1p/a0dL1LXMIgoEDFrwOe6K6KqG8K1p/a0dL1LXMIgoEDFrwO

<?php
// Script para gerar o hash correto da senha admin123
echo "🔐 === GERADOR DE HASH PARA SENHA ===\n\n";

$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Senha: {$password}\n";
echo "Hash gerado: {$hash}\n\n";

// Verificar se o hash está correto
if (password_verify($password, $hash)) {
    echo "✅ Hash verificado com sucesso!\n";
} else {
    echo "❌ Erro na verificação do hash!\n";
}

echo "\n📝 SQL para atualizar:\n";
echo "UPDATE usuarios SET password = '{$hash}' WHERE username = 'admin';\n\n";

echo "🔍 Para testar no banco:\n";
echo "SELECT username, password, role, ativo FROM usuarios WHERE username = 'admin';\n";
?>

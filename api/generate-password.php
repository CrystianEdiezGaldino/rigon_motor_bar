<?php
// Script para gerar hash correto da senha admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Senha: $password\n";
echo "Hash gerado: $hash\n";
echo "\n";
echo "Verificação: " . (password_verify($password, $hash) ? '✅ OK' : '❌ FALHOU') . "\n";

// Hash específico para admin123
$admin123_hash = '$2y$10$YourNewHashHere';
echo "\nHash atual no banco: \$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi\n";
echo "Verificação com hash atual: " . (password_verify($password, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi') ? '✅ OK' : '❌ FALHOU') . "\n";
?>

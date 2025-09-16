<?php
// Script para testar password_hash e password_verify
echo "<h2>🧪 Teste de Hash de Senha</h2>";

// Teste 1: Hash básico
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Teste 1: Hash básico</h3>";
echo "Senha: <strong>$password</strong><br>";
echo "Hash gerado: <code>$hash</code><br>";
echo "Verificação: " . (password_verify($password, $hash) ? '✅ OK' : '❌ FALHOU') . "<br><br>";

// Teste 2: Verificar hash específico do admin
$admin_hash = '$2y$10$8K1p/a0dL1LXMIgoEDFrwOe6K6KqG8K1p/a0dL1LXMIgoEDFrwO';
echo "<h3>Teste 2: Hash do admin no schema</h3>";
echo "Hash do schema: <code>$admin_hash</code><br>";
echo "Verificação com 'admin123': " . (password_verify('admin123', $admin_hash) ? '✅ OK' : '❌ FALHOU') . "<br><br>";

// Teste 3: Verificar hash antigo
$old_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "<h3>Teste 3: Hash antigo</h3>";
echo "Hash antigo: <code>$old_hash</code><br>";
echo "Verificação com 'admin123': " . (password_verify('admin123', $old_hash) ? '✅ OK' : '❌ FALHOU') . "<br><br>";

// Teste 4: Gerar novo hash para admin123
echo "<h3>Teste 4: Novo hash para admin123</h3>";
$new_admin_hash = password_hash('admin123', PASSWORD_DEFAULT);
echo "Novo hash para 'admin123': <code>$new_admin_hash</code><br>";
echo "Verificação: " . (password_verify('admin123', $new_admin_hash) ? '✅ OK' : '❌ FALHOU') . "<br><br>";

// Teste 5: Verificar se o hash antigo corresponde a alguma senha conhecida
echo "<h3>Teste 5: Verificar hash antigo</h3>";
$test_passwords = ['admin', 'admin123', 'password', '123456', 'admin1234', 'admin12345'];
foreach ($test_passwords as $test_pwd) {
    $result = password_verify($test_pwd, $old_hash);
    echo "Hash antigo + '$test_pwd': " . ($result ? '✅ MATCH!' : '❌ Não') . "<br>";
}

echo "<hr>";
echo "<h3>📝 Comando SQL para atualizar a senha do admin:</h3>";
echo "<code>UPDATE usuarios SET password = '$new_admin_hash' WHERE username = 'admin';</code>";
?>

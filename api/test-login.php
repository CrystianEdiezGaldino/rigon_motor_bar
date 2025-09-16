<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// Incluir configuração do banco
$config = require_once 'config.php';

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", 
                    $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Teste do Sistema de Login</h2>";
    
    // 1. Verificar conexão
    echo "<h3>✅ Conexão com banco:</h3>";
    echo "Host: {$config['host']}<br>";
    echo "Database: {$config['dbname']}<br>";
    echo "Status: Conectado com sucesso<br><br>";
    
    // 2. Verificar usuários existentes
    echo "<h3>👥 Usuários no banco:</h3>";
    $stmt = $pdo->query("SELECT id, username, role, ativo, password FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "❌ Nenhum usuário encontrado!<br><br>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Ativo</th><th>Password Hash</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['ativo']}</td>";
            echo "<td style='font-size: 10px; max-width: 200px; word-break: break-all;'>{$user['password']}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
    
    // 3. Testar validação de senha
    echo "<h3>🔐 Teste de Validação de Senha:</h3>";
    
    $testPasswords = [
        'admin' => 'admin123',
        'admin2' => 'admin123'
    ];
    
    foreach ($testPasswords as $username => $password) {
        echo "<h4>Testando: {$username} / {$password}</h4>";
        
        $stmt = $pdo->prepare("SELECT id, username, password, role, ativo FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✅ Usuário encontrado<br>";
            echo "ID: {$user['id']}<br>";
            echo "Role: {$user['role']}<br>";
            echo "Ativo: {$user['ativo']}<br>";
            
            if (password_verify($password, $user['password'])) {
                echo "✅ Senha válida!<br>";
            } else {
                echo "❌ Senha inválida!<br>";
                echo "Hash no banco: {$user['password']}<br>";
                
                // Gerar novo hash
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                echo "Novo hash gerado: {$newHash}<br>";
                
                // Atualizar no banco
                $updateStmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
                if ($updateStmt->execute([$newHash, $username])) {
                    echo "✅ Senha atualizada no banco!<br>";
                } else {
                    echo "❌ Erro ao atualizar senha!<br>";
                }
            }
        } else {
            echo "❌ Usuário não encontrado!<br>";
        }
        echo "<br>";
    }
    
    // 4. Criar usuários se não existirem
    echo "<h3>🆕 Criando usuários se necessário:</h3>";
    
    $defaultUsers = [
        ['admin', 'admin123', 'admin', 'Administrador'],
        ['admin2', 'admin123', 'admin', 'Admin 2']
    ];
    
    foreach ($defaultUsers as $userData) {
        $username = $userData[0];
        $password = $userData[1];
        $role = $userData[2];
        $nome = $userData[3];
        
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        
        if (!$stmt->fetch()) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare("INSERT INTO usuarios (username, password, role, nome, ativo) VALUES (?, ?, ?, ?, 1)");
            
            if ($insertStmt->execute([$username, $hash, $role, $nome])) {
                echo "✅ Usuário {$username} criado com sucesso!<br>";
            } else {
                echo "❌ Erro ao criar usuário {$username}!<br>";
            }
        } else {
            echo "ℹ️ Usuário {$username} já existe<br>";
        }
    }
    
    // 5. Verificar estrutura da tabela
    echo "<h3>📋 Estrutura da tabela usuarios:</h3>";
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
} catch(PDOException $e) {
    echo "<h3>❌ Erro de conexão:</h3>";
    echo "Erro: " . $e->getMessage();
}
?>

<hr>
<h3>🔧 Ações Recomendadas:</h3>
<ol>
    <li>Execute este script para verificar o status</li>
    <li>Se houver problemas, ele vai corrigir automaticamente</li>
    <li>Teste o login novamente após a correção</li>
</ol>

<a href="login.php">← Voltar ao Login</a>

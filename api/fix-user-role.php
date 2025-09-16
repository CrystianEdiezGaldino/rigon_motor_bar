<?php
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🔧 Corrigindo Role do Usuário</h2>";

// Usar as mesmas credenciais que funcionam
$host = "localhost";
$dbname = "delitc66_rigon_motor_bar";
$username_db = "delitc66_rigon_motor_bar";
$password_db = "z[Q_kcVxD1I&";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Conectado ao banco com sucesso!</p>";
    
    // Verificar usuário lordxim
    echo "<h3>👤 Verificando usuário lordxim:</h3>";
    $stmt = $pdo->prepare("SELECT id, username, role, ativo FROM usuarios WHERE username = ?");
    $stmt->execute(['lordxim']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p>✅ Usuário encontrado:</p>";
        echo "<ul>";
        echo "<li>ID: {$user['id']}</li>";
        echo "<li>Username: {$user['username']}</li>";
        echo "<li>Role atual: <strong>{$user['role']}</strong></li>";
        echo "<li>Ativo: {$user['ativo']}</li>";
        echo "</ul>";
        
        if ($user['role'] !== 'admin') {
            echo "<p>🔄 Alterando role de '{$user['role']}' para 'admin'...</p>";
            
            $updateStmt = $pdo->prepare("UPDATE usuarios SET role = 'admin' WHERE username = ?");
            if ($updateStmt->execute(['lordxim'])) {
                echo "<p>✅ Role alterado com sucesso!</p>";
                
                // Verificar se foi alterado
                $stmt->execute(['lordxim']);
                $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p>🆕 Novo role: <strong>{$updatedUser['role']}</strong></p>";
            } else {
                echo "<p>❌ Erro ao alterar role!</p>";
            }
        } else {
            echo "<p>ℹ️ Usuário já tem role 'admin'</p>";
        }
    } else {
        echo "<p>❌ Usuário 'lordxim' não encontrado!</p>";
    }
    
    // Mostrar todos os usuários admin
    echo "<h3>👑 Usuários com role 'admin':</h3>";
    $stmt = $pdo->query("SELECT id, username, role, ativo FROM usuarios WHERE role = 'admin' ORDER BY username");
    $adminUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($adminUsers)) {
        echo "<p>Nenhum usuário admin encontrado.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Ativo</th></tr>";
        foreach ($adminUsers as $adminUser) {
            echo "<tr>";
            echo "<td>{$adminUser['id']}</td>";
            echo "<td>{$adminUser['username']}</td>";
            echo "<td>{$adminUser['role']}</td>";
            echo "<td>{$adminUser['ativo']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch(PDOException $e) {
    echo "<p>❌ Erro: " . $e->getMessage() . "</p>";
}
?>

<hr>
<h3>🎯 Próximos passos:</h3>
<ol>
    <li>Execute este script para corrigir o role</li>
    <li>Faça logout e login novamente</li>
    <li>O usuário lordxim deve ter acesso ao painel admin</li>
</ol>

<a href="login.php">← Voltar ao Login</a>

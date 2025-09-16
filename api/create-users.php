<?php
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🔧 Criando Usuários no Banco</h2>";

// Usar as mesmas credenciais que funcionam
$host = "localhost";
$dbname = "delitc66_rigon_motor_bar";
$username_db = "delitc66_rigon_motor_bar";
$password_db = "z[Q_kcVxD1I&";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Conectado ao banco com sucesso!</p>";
    
    // Verificar se tabela usuarios existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() == 0) {
        echo "<p>❌ Tabela 'usuarios' não existe!</p>";
        echo "<p>Criando tabela...</p>";
        
        $sql = "CREATE TABLE usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'user',
            nome VARCHAR(100),
            ativo TINYINT(1) DEFAULT 1,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "<p>✅ Tabela 'usuarios' criada!</p>";
    } else {
        echo "<p>✅ Tabela 'usuarios' já existe!</p>";
    }
    
    // Verificar usuários existentes
    echo "<h3>👥 Usuários existentes:</h3>";
    $stmt = $pdo->query("SELECT id, username, role, ativo FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "<p>Nenhum usuário encontrado.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Ativo</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['ativo']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Criar usuários padrão se não existirem
    echo "<h3>🆕 Criando usuários padrão:</h3>";
    
    $defaultUsers = [
        ['admin', 'admin123', 'admin', 'Administrador'],
        ['admin2', 'admin123', 'admin', 'Admin 2']
    ];
    
    foreach ($defaultUsers as $userData) {
        $username = $userData[0];
        $password = $userData[1];
        $role = $userData[2];
        $nome = $userData[3];
        
        // Verificar se já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        
        if (!$stmt->fetch()) {
            // Criar usuário
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare("INSERT INTO usuarios (username, password, role, nome, ativo) VALUES (?, ?, ?, ?, 1)");
            
            if ($insertStmt->execute([$username, $hash, $role, $nome])) {
                echo "<p>✅ Usuário <strong>{$username}</strong> criado com sucesso!</p>";
            } else {
                echo "<p>❌ Erro ao criar usuário {$username}!</p>";
            }
        } else {
            echo "<p>ℹ️ Usuário <strong>{$username}</strong> já existe</p>";
        }
    }
    
    // Testar login
    echo "<h3>🔐 Testando login:</h3>";
    
    foreach ($defaultUsers as $userData) {
        $username = $userData[0];
        $password = $userData[1];
        
        $stmt = $pdo->prepare("SELECT id, username, password, role, nome, ativo FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            echo "<p>✅ Login <strong>{$username}</strong> funcionando!</p>";
        } else {
            echo "<p>❌ Login <strong>{$username}</strong> falhou!</p>";
        }
    }
    
} catch(PDOException $e) {
    echo "<p>❌ Erro: " . $e->getMessage() . "</p>";
}
?>

<hr>
<h3>🎯 Próximos passos:</h3>
<ol>
    <li>Execute este script para criar os usuários</li>
    <li>Teste o login em <a href="login.php">login.php</a></li>
    <li>Use as credenciais: admin/admin123 ou admin2/admin123</li>
</ol>

<a href="login.php">← Voltar ao Login</a>

<?php
// Script para debug da sessão e autenticação
session_start();
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🔍 Debug da Sessão e Autenticação</h2>";

try {
    // Incluir arquivos necessários
    include_once 'config/database.php';
    include_once 'models/Usuario.php';
    include_once 'models/Produto.php';
    
    // Instanciar classes
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);
    $produto = new Produto($db);
    
    echo "<h3>📊 Status da Sessão:</h3>";
    echo "<p>Session ID: <strong>" . session_id() . "</strong></p>";
    echo "<p>Session Status: <strong>" . (session_status() === PHP_SESSION_ACTIVE ? 'Ativa' : 'Inativa') . "</strong></p>";
    
    echo "<h3>🔑 Variáveis de Sessão:</h3>";
    if (empty($_SESSION)) {
        echo "<p style='color: orange;'>⚠️ Sessão vazia</p>";
    } else {
        echo "<pre>" . print_r($_SESSION, true) . "</pre>";
    }
    
    echo "<h3>👤 Verificação de Login:</h3>";
    
    // Verificar se está logado
    $isLoggedIn = isset($_SESSION['user_id']) && $usuario->isLoggedIn();
    echo "<p>isLoggedIn: <strong>" . ($isLoggedIn ? 'true' : 'false') . "</strong></p>";
    
    // Verificar se é admin
    $isAdmin = $isLoggedIn && $usuario->isAdmin();
    echo "<p>isAdmin: <strong>" . ($isAdmin ? 'true' : 'false') . "</strong></p>";
    
    echo "<h3>🔍 Detalhes da Verificação:</h3>";
    echo "<p>isset(\$_SESSION['user_id']): <strong>" . (isset($_SESSION['user_id']) ? 'true' : 'false') . "</strong></p>";
    echo "<p>isset(\$_SESSION['user_role']): <strong>" . (isset($_SESSION['user_role']) ? 'true' : 'false') . "</strong></p>";
    echo "<p>isset(\$_SESSION['user_ativo']): <strong>" . (isset($_SESSION['user_ativo']) ? 'true' : 'false') . "</strong></p>";
    
    if (isset($_SESSION['user_id'])) {
        echo "<p>\$_SESSION['user_id']: <strong>{$_SESSION['user_id']}</strong></p>";
    }
    if (isset($_SESSION['user_role'])) {
        echo "<p>\$_SESSION['user_role']: <strong>{$_SESSION['user_role']}</strong></p>";
    }
    if (isset($_SESSION['user_ativo'])) {
        echo "<p>\$_SESSION['user_ativo']: <strong>{$_SESSION['user_ativo']}</strong></p>";
    }
    
    echo "<h3>🧪 Testando método isLoggedIn():</h3>";
    $testLoggedIn = $usuario->isLoggedIn();
    echo "<p>usuario->isLoggedIn(): <strong>" . ($testLoggedIn ? 'true' : 'false') . "</strong></p>";
    
    echo "<h3>🧪 Testando método isAdmin():</h3>";
    $testAdmin = $usuario->isAdmin();
    echo "<p>usuario->isAdmin(): <strong>" . ($testAdmin ? 'true' : 'false') . "</strong></p>";
    
    echo "<h3>📋 Testando carregamento de produtos:</h3>";
    if ($isAdmin) {
        echo "<p style='color: green;'>✅ Usuário é admin, carregando produtos...</p>";
        
        $stmt = $produto->read();
        if ($stmt) {
            $produtos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produtos[] = $row;
            }
            echo "<p>Produtos carregados: <strong>" . count($produtos) . "</strong></p>";
            
            if (!empty($produtos)) {
                echo "<p>Primeiro produto: <strong>{$produtos[0]['nome']}</strong></p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Erro ao carregar produtos</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Usuário não é admin, produtos não serão carregados</p>";
    }
    
    echo "<h3>🔧 Para testar login:</h3>";
    echo "<p>1. Acesse: <a href='index.php'>index.php</a></p>";
    echo "<p>2. Use as credenciais: <strong>admin</strong> / <strong>admin123</strong></p>";
    echo "<p>3. Verifique se a sessão foi criada</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

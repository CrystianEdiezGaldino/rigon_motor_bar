<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// Se já estiver logado, redirecionar para o painel
if (isset($_SESSION['user_id'])) {
    header("Location: admin-panel.php");
    exit();
}

$error = '';
$success = '';

// Verificar se há erro na URL
if (isset($_GET['error']) && $_GET['error'] === 'session_expired') {
    $error = 'Sessão expirada. Faça login novamente.';
}

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validações de entrada
    if (empty($username) || empty($password)) {
        $error = 'Usuário e senha são obrigatórios';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Usuário deve ter entre 3 e 50 caracteres';
    } elseif (strlen($password) < 6) {
        $error = 'Senha deve ter pelo menos 6 caracteres';
    } else {
        // Usar as mesmas credenciais do index.php que funcionam
        $host = "localhost";
        $dbname = "delitc66_rigon_motor_bar";
        $username_db = "delitc66_rigon_motor_bar";
        $password_db = "z[Q_kcVxD1I&";
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Buscar usuário com informações básicas
            $stmt = $pdo->prepare("SELECT id, username, password, role, nome, ativo FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Verificar se usuário está ativo
                if ($user['ativo'] != 1) {
                    $error = 'Usuário inativo. Entre em contato com o administrador.';
                } elseif (password_verify($password, $user['password'])) {
                    // Login bem-sucedido
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['nome'] = $user['nome'];
                    $_SESSION['login_time'] = time();
                    
                    // Tentar atualizar último login (se o campo existir)
                    try {
                        $updateStmt = $pdo->prepare("UPDATE usuarios SET last_login = NOW() WHERE id = ?");
                        $updateStmt->execute([$user['id']]);
                    } catch (Exception $e) {
                        // Campo pode não existir, ignorar erro
                    }
                    
                    // Log de acesso bem-sucedido
                    error_log("Login bem-sucedido: {$username} - " . date('Y-m-d H:i:s'));
                    
                    // Redirecionar para o painel
                    header("Location: admin-panel.php");
                    exit();
                } else {
                    $error = 'Usuário ou senha incorretos';
                    // Log de tentativa de login falhada
                    error_log("Tentativa de login falhada: {$username} - " . date('Y-m-d H:i:s'));
                }
            } else {
                $error = 'Usuário ou senha incorretos';
                // Log de tentativa de login com usuário inexistente
                error_log("Tentativa de login com usuário inexistente: {$username} - " . date('Y-m-d H:i:s'));
            }
            
        } catch(PDOException $e) {
            $error = 'Erro de conexão com o banco de dados';
            error_log("Erro de banco no login: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍺 Login - Rigon Motor Bar</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container { 
            background: rgba(255,255,255,0.05); 
            padding: 40px; 
            border-radius: 20px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo { 
            font-size: 3em; 
            margin-bottom: 20px;
            background: linear-gradient(135deg, #F45F0A 0%, #ff6b35 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .title { 
            font-size: 1.8em; 
            margin-bottom: 10px;
            color: #F45F0A;
        }
        .subtitle { 
            color: rgba(255,255,255,0.7); 
            margin-bottom: 30px;
        }
        .form-group { 
            margin-bottom: 20px; 
            text-align: left;
        }
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600;
            color: #F45F0A;
        }
        .form-group input { 
            width: 100%; 
            padding: 15px; 
            border: 2px solid rgba(255,255,255,0.2); 
            border-radius: 10px; 
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-group input:focus { 
            outline: none; 
            border-color: #F45F0A;
            box-shadow: 0 0 0 3px rgba(244, 95, 10, 0.2);
        }
        .form-group input::placeholder { 
            color: rgba(255,255,255,0.5);
        }
        .btn-login { 
            width: 100%; 
            padding: 15px; 
            border: none; 
            border-radius: 10px; 
            background: linear-gradient(135deg, #F45F0A 0%, #ff6b35 100%);
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .btn-login:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(244, 95, 10, 0.4);
        }
        .message { 
            padding: 15px; 
            border-radius: 10px; 
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message.error { 
            background: rgba(220, 53, 69, 0.2); 
            border: 1px solid #dc3545;
            color: #dc3545;
        }
        .message.success { 
            background: rgba(40, 167, 69, 0.2); 
            border: 1px solid #28a745;
            color: #28a745;
        }
        .credentials { 
            background: rgba(255,255,255,0.1); 
            padding: 20px; 
            border-radius: 10px; 
            margin-top: 30px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .credentials h3 { 
            color: #F45F0A; 
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        .credential-item { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .credential-label { 
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }
        .credential-value { 
            color: #F45F0A;
            font-family: monospace;
            font-weight: 600;
        }
        .back-link { 
            margin-top: 20px;
        }
        .back-link a { 
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .back-link a:hover { 
            color: #F45F0A;
        }
        @media (max-width: 480px) {
            .login-container { 
                margin: 20px; 
                padding: 30px 20px;
            }
            .logo { font-size: 2.5em; }
            .title { font-size: 1.5em; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo"></div>
        <h1 class="title">🔐 Rigon Motor Bar</h1>
        <p class="subtitle">🛡️ Painel Administrativo Seguro</p>
        
        <?php if ($error): ?>
            <div class="message error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="message success">✅ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">👤 Usuário</label>
                <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>
            
            <button type="submit" class="btn-login">🚪 Entrar no Sistema</button>
        </form>
        
        <div class="back-link">
            <a href="../">🏠 Voltar ao site principal</a>
        </div>
    </div>

    <script>
        // Auto-focus no primeiro campo
        document.getElementById('username').focus();
        
        // Auto-hide error messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.message');
            messages.forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>

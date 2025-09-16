<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// VALIDAÇÃO DE SESSÃO - Proteger o painel admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Se não estiver logado, redirecionar para login
    header("Location: login.php");
    exit();
}

// Usar as mesmas credenciais do index.php que funcionam
$host = "localhost";
$dbname = "delitc66_rigon_motor_bar";
$username_db = "delitc66_rigon_motor_bar";
$password_db = "z[Q_kcVxD1I&";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Processar ações
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $preco = floatval($_POST['preco'] ?? 0);
                $categoria = trim($_POST['categoria'] ?? '');
                $nova_categoria = trim($_POST['nova_categoria'] ?? '');
                
                // Se selecionou "nova categoria", usar o valor do input
                if ($categoria === 'nova_categoria') {
                    if (empty($nova_categoria)) {
                        $error = 'Nome da nova categoria é obrigatório';
                        break;
                    }
                    $categoria = $nova_categoria;
                }
                
                // Validações de negócio
                if (empty($nome) || empty($categoria) || $preco <= 0) {
                    $error = 'Nome, categoria e preço são obrigatórios';
                } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
                    $error = 'Nome deve ter entre 3 e 100 caracteres';
                } elseif (strlen($descricao) > 500) {
                    $error = 'Descrição deve ter no máximo 500 caracteres';
                } elseif ($preco > 9999.99) {
                    $error = 'Preço deve ser menor que R$ 10.000,00';
                } elseif (strlen($categoria) < 2 || strlen($categoria) > 50) {
                    $error = 'Categoria deve ter entre 2 e 50 caracteres';
                } else {
                    // Verificar se produto já existe
                    $checkStmt = $pdo->prepare("SELECT id FROM produtos WHERE nome = ? AND categoria = ? AND ativo = 1");
                    $checkStmt->execute([$nome, $categoria]);
                    
                    if ($checkStmt->fetch()) {
                        $error = 'Já existe um produto com este nome nesta categoria';
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, categoria, ativo, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
                        if ($stmt->execute([$nome, $descricao, $preco, $categoria])) {
                            $message = 'Produto criado com sucesso na categoria "' . htmlspecialchars($categoria) . '"!';
                            // Log de criação
                            error_log("Produto criado: {$nome} na categoria '{$categoria}' por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                        } else {
                            $error = 'Erro ao criar produto';
                        }
                    }
                }
                break;
                
            case 'edit_product':
                $id = intval($_POST['id'] ?? 0);
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $preco = floatval($_POST['preco'] ?? 0);
                $categoria = trim($_POST['categoria'] ?? '');
                
                // Validações de negócio
                if ($id <= 0 || empty($nome) || empty($categoria) || $preco <= 0) {
                    $error = 'ID, nome, categoria e preço são obrigatórios';
                } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
                    $error = 'Nome deve ter entre 3 e 100 caracteres';
                } elseif (strlen($descricao) > 500) {
                    $error = 'Descrição deve ter no máximo 500 caracteres';
                } elseif ($preco > 9999.99) {
                    $error = 'Preço deve ser menor que R$ 10.000,00';
                } else {
                    // Verificar se produto existe e está ativo
                    $checkStmt = $pdo->prepare("SELECT id FROM produtos WHERE id = ? AND ativo = 1");
                    $checkStmt->execute([$id]);
                    
                    if (!$checkStmt->fetch()) {
                        $error = 'Produto não encontrado ou inativo';
                    } else {
                        // Verificar se nome já existe em outra categoria
                        $checkStmt = $pdo->prepare("SELECT id FROM produtos WHERE nome = ? AND categoria = ? AND id != ? AND ativo = 1");
                        $checkStmt->execute([$nome, $categoria, $id]);
                        
                        if ($checkStmt->fetch()) {
                            $error = 'Já existe um produto com este nome nesta categoria';
                        } else {
                            // Usar UPDATE simples sem campos que podem não existir
                            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, updated_at = NOW() WHERE id = ?");
                            if ($stmt->execute([$nome, $descricao, $preco, $categoria, $id])) {
                                $message = 'Produto atualizado com sucesso!';
                                // Log de atualização
                                error_log("Produto atualizado: {$nome} por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                            } else {
                                $error = 'Erro ao atualizar produto: ' . implode(', ', $stmt->errorInfo());
                            }
                        }
                    }
                }
                break;
                
            case 'delete_product':
                $id = intval($_POST['id'] ?? 0);
                if ($id > 0) {
                    // Verificar se produto existe
                    $checkStmt = $pdo->prepare("SELECT nome FROM produtos WHERE id = ?");
                    $checkStmt->execute([$id]);
                    $produto = $checkStmt->fetch();
                    
                    if ($produto) {
                        // REMOVER COMPLETAMENTE DA TABELA
                        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
                        if ($stmt->execute([$id])) {
                            $message = 'Produto removido permanentemente do banco de dados!';
                            // Log de remoção
                            error_log("Produto DELETADO permanentemente: {$produto['nome']} por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                        } else {
                            $error = 'Erro ao remover produto do banco: ' . implode(', ', $stmt->errorInfo());
                        }
                    } else {
                        $error = 'Produto não encontrado';
                    }
                } else {
                    $error = 'ID de produto inválido';
                }
                break;
                
            case 'logout':
                // Log de logout
                error_log("Logout realizado: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                session_destroy();
                header("Location: login.php");
                exit();
                break;
                
            case 'create_user':
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $nome = trim($_POST['nome'] ?? '');
                $role = trim($_POST['role'] ?? '');
                
                // Validações de negócio
                if (empty($username) || empty($password) || empty($nome) || empty($role)) {
                    $error = 'Todos os campos são obrigatórios';
                } elseif (strlen($username) < 3 || strlen($username) > 50) {
                    $error = 'Usuário deve ter entre 3 e 50 caracteres';
                } elseif (strlen($password) < 6) {
                    $error = 'Senha deve ter no mínimo 6 caracteres';
                } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
                    $error = 'Nome deve ter entre 3 e 100 caracteres';
                } elseif (!in_array($role, ['user', 'admin'])) {
                    $error = 'Tipo de usuário inválido';
                } else {
                    // Verificar se usuário já existe
                    $checkStmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
                    $checkStmt->execute([$username]);
                    
                    if ($checkStmt->fetch()) {
                        $error = 'Já existe um usuário com este nome de usuário';
                    } else {
                        // Hash da senha
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        
                        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, nome, email, role, ativo, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
                        if ($stmt->execute([$username, $password_hash, $nome, $username . '@rigon.com', $role])) {
                            $message = 'Usuário criado com sucesso!';
                            // Log de criação
                            error_log("Usuário criado: {$username} ({$role}) por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                        } else {
                            $error = 'Erro ao criar usuário: ' . implode(', ', $stmt->errorInfo());
                        }
                    }
                }
                break;
        }
    }
}

// Buscar produtos para edição
$editProduct = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar produtos
$stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY categoria, nome");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar usuários para o modal
$stmt = $pdo->query("SELECT id, username, nome, role, ativo, created_at, last_login FROM usuarios ORDER BY username");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por categoria
$categorias = [];
foreach ($produtos as $produto) {
    if (!isset($categorias[$produto['categoria']])) {
        $categorias[$produto['categoria']] = [];
    }
    $categorias[$produto['categoria']][] = $produto;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍺 Painel Admin - Rigon Motor Bar</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #fff;
            min-height: 100vh;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { 
            background: linear-gradient(135deg, #F45F0A 0%, #ff6b35 100%);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(244, 95, 10, 0.4);
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .header h1 { 
            font-size: 3em; 
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        .header p { 
            font-size: 1.2em; 
            opacity: 0.95;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .user-info { 
            background: rgba(255,255,255,0.15); 
            padding: 20px; 
            border-radius: 15px;
            margin-top: 20px;
            display: inline-block;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            z-index: 1;
        }
        .section { 
            background: rgba(255,255,255,0.08); 
            padding: 30px; 
            border-radius: 20px; 
            margin-bottom: 30px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .section h2 { 
            color: #F45F0A; 
            margin-bottom: 25px; 
            font-size: 2em;
            border-bottom: 3px solid #F45F0A;
            padding-bottom: 15px;
            text-align: center;
        }
        .form-group { margin-bottom: 25px; }
        .form-group label { 
            display: block; 
            margin-bottom: 10px; 
            font-weight: 700;
            color: #F45F0A;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; 
            padding: 15px; 
            border: 2px solid rgba(255,255,255,0.2); 
            border-radius: 12px; 
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { 
            outline: none; 
            border-color: #F45F0A;
            box-shadow: 0 0 0 4px rgba(244, 95, 10, 0.2);
            transform: translateY(-2px);
        }
        .form-group textarea { height: 120px; resize: vertical; }
        
        /* Estilos específicos para select */
        .form-group select {
            color: #fff !important;
            background: rgba(255,255,255,0.1) !important;
        }
        
        .form-group select option {
            background: #2d2d2d !important;
            color: #fff !important;
        }
        
        .form-group select option:checked {
            background: #F45F0A !important;
            color: #fff !important;
        }
        
        /* Grupo de categoria */
        .categoria-input-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .categoria-input-group select,
        .categoria-input-group input {
            width: 100%;
        }
        
        .categoria-input-group input {
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .categoria-input-group input:focus {
            outline: none;
            border-color: #F45F0A;
            box-shadow: 0 0 0 4px rgba(244, 95, 10, 0.2);
            transform: translateY(-2px);
        }
        
        .categoria-input-group input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        
        .btn { 
            padding: 15px 30px; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: 700;
            transition: all 0.3s ease;
            margin-right: 15px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #F45F0A 0%, #ff6b35 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(244, 95, 10, 0.3);
        }
        .btn-primary:hover { 
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(244, 95, 10, 0.5);
        }
        .btn-danger { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        }
        .btn-danger:hover { 
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(220, 53, 69, 0.5);
        }
        .btn-secondary { 
            background: rgba(255,255,255,0.15); 
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-secondary:hover { 
            background: rgba(255,255,255,0.25);
            transform: translateY(-3px);
        }
        .message { 
            padding: 20px; 
            border-radius: 12px; 
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
            font-size: 1.1em;
        }
        .message.success { 
            background: rgba(40, 167, 69, 0.2); 
            border: 2px solid #28a745;
            color: #28a745;
        }
        .message.error { 
            background: rgba(220, 53, 69, 0.2); 
            border: 2px solid #dc3545;
            color: #dc3545;
        }
        .products-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); 
            gap: 25px; 
            margin-top: 25px;
        }
        .product-card { 
            background: rgba(255,255,255,0.1); 
            padding: 25px; 
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
        }
        .product-card:hover { 
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
            border-color: #F45F0A;
        }
        .product-name { 
            font-size: 1.4em; 
            font-weight: 700; 
            color: #F45F0A; 
            margin-bottom: 15px;
            text-align: center;
        }
        .product-price { 
            font-size: 1.3em; 
            font-weight: 700; 
            color: #fff; 
            margin-bottom: 12px;
            text-align: center;
        }
        .product-category { 
            background: rgba(244, 95, 10, 0.2); 
            color: #F45F0A; 
            padding: 8px 16px; 
            border-radius: 25px; 
            font-size: 0.9em;
            display: inline-block;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
            font-weight: 600;
        }
        .product-description { 
            color: rgba(255,255,255,0.8); 
            margin-bottom: 20px;
            line-height: 1.6;
            text-align: center;
            font-style: italic;
        }
        .product-actions { 
            display: flex; 
            gap: 12px; 
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn-sm { 
            padding: 10px 20px; 
            font-size: 14px;
        }
        .logout-btn { 
            position: absolute; 
            top: 25px; 
            right: 25px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        .logout-btn:hover { 
            background: rgba(220, 53, 69, 1);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
        }
        .stats { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 25px; 
            margin-bottom: 35px;
        }
        .stat-card { 
            background: rgba(255,255,255,0.1); 
            padding: 30px; 
            border-radius: 18px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        .stat-number { 
            font-size: 3em; 
            font-weight: 800; 
            color: #F45F0A; 
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .stat-label { 
            color: rgba(255,255,255,0.9); 
            font-size: 1.2em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .top-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            padding: 15px 0;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .menu-item {
            display: inline-block;
        }
        .menu-link {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
            background: rgba(255,255,255,0.1);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
        }
        .menu-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(255,255,255,0.1);
        }
        .menu-link.active {
            background: linear-gradient(135deg, #F45F0A 0%, #ff6b35 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(244, 95, 10, 0.3);
        }
        .menu-link.active:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(244, 95, 10, 0.5);
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            .header h1 { font-size: 2em; }
            .products-grid { grid-template-columns: 1fr; }
            .stats { grid-template-columns: 1fr; }
            .top-menu {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }

        /* Estilos para Modais */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(10px);
        }
        
        .modal-content {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            margin: 5% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            animation: modalSlideIn 0.3s ease;
        }
        
        .modal-content.large {
            max-width: 900px;
        }
        
        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #F45F0A 0%, #ff6b35 100%);
            padding: 25px 30px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            color: white;
            margin: 0;
            font-size: 1.8em;
        }
        
        .close {
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close:hover {
            transform: scale(1.2);
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }
        
        .modal form {
            padding: 30px;
        }
        
        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        /* Tabela de usuários */
        .users-list {
            padding: 30px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .users-table th,
        .users-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .users-table th {
            background: rgba(244, 95, 10, 0.2);
            color: #F45F0A;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .users-table tr:hover {
            background: rgba(255,255,255,0.05);
        }
        
        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .role-badge.admin {
            background: rgba(244, 95, 10, 0.3);
            color: #F45F0A;
        }
        
        .role-badge.user {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }
        
        .status-badge.active {
            background: rgba(40, 167, 69, 0.3);
            color: #28a745;
        }
        
        .status-badge.inactive {
            background: rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }
        
        /* Configurações */
        .settings-content {
            padding: 30px;
        }
        
        .setting-group {
            margin-bottom: 30px;
        }
        
        .setting-group h3 {
            color: #F45F0A;
            margin-bottom: 20px;
            font-size: 1.3em;
            border-bottom: 2px solid #F45F0A;
            padding-bottom: 10px;
        }
        
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .stat-item .stat-number {
            font-size: 2em;
            color: #F45F0A;
            font-weight: 800;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-item .stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 0.9em;
        }
        
        /* Estilos para listagem de usuários */
        .users-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .users-header h3 {
            color: #F45F0A;
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        
        .users-header p {
            color: rgba(255,255,255,0.8);
            font-size: 1.1em;
        }
        
        .no-users {
            text-align: center;
            padding: 40px;
            color: rgba(255,255,255,0.7);
            font-style: italic;
        }
        
        .current-user-badge {
            background: rgba(244, 95, 10, 0.3);
            color: #F45F0A;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.7em;
            margin-left: 8px;
            font-weight: 600;
        }
        
        .never-logged {
            color: rgba(255,255,255,0.5);
            font-style: italic;
            font-size: 0.9em;
        }
        
        .user-actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #000;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
        }
        
        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 193, 7, 0.5);
        }
        
        /* Estilos para ícones Feather */
        [data-feather] {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        .btn [data-feather] {
            width: 18px;
            height: 18px;
        }
        
        .btn-sm [data-feather] {
            width: 14px;
            height: 14px;
        }
        
        h1 [data-feather], h2 [data-feather], h3 [data-feather] {
            width: 24px;
            height: 24px;
            margin-right: 12px;
        }
        
        .header h1 [data-feather] {
            width: 32px;
            height: 32px;
            margin-right: 15px;
        }
        
        .user-info [data-feather] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }
        
        .message [data-feather] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        
        .current-user-badge [data-feather] {
            width: 12px;
            height: 12px;
            margin-right: 4px;
        }
        
        .status-badge [data-feather] {
            width: 14px;
            height: 14px;
            margin-right: 6px;
        }
        
        .role-badge [data-feather] {
            width: 12px;
            height: 12px;
            margin-right: 4px;
        }
        
        /* Ajustes para ícones em botões */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-sm {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-sm i {
            margin-right: 6px;
        }
        
        /* Ajustes para títulos com ícones */
        h1, h2, h3 {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header h1 {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .message {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .current-user-badge, .status-badge, .role-badge {
            display: inline-flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i data-feather="beer"></i> Painel Administrativo</h1>
            <p>Rigon Motor Bar - Gerenciamento de Produtos</p>
            <div class="user-info">
                <i data-feather="user"></i> Logado como: <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuário'); ?></strong>
                (<?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'admin'; ?>)
            </div>
        </div>

        <!-- Menu Superior -->
        <div class="top-menu">
            <div class="menu-item">
                <a href="#" class="menu-link active" onclick="showSection('dashboard')">
                    <i data-feather="home"></i> Dashboard
                </a>
            </div>
            <div class="menu-item">
                <a href="#" class="menu-link" onclick="showModal('createAccountModal')">
                    <i data-feather="users"></i> Criar Conta
                </a>
            </div>
            <div class="menu-item">
                <a href="#" class="menu-link" onclick="showModal('manageUsersModal')">
                    <i data-feather="settings"></i> Gerenciar Usuários
                </a>
            </div>
            <div class="menu-item">
                <!-- Botão Logout -->
                <form method="POST" style="position: relative; z-index: 100;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="logout-btn"><i data-feather="log-out"></i> Sair</button>
                </form>
            </div>
        </div>
    
        <!-- Mensagens -->
        <?php if ($message): ?>
            <div class="message success"><i data-feather="check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><i data-feather="alert-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Estatísticas -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($produtos); ?></div>
                <div class="stat-label">Total de Produtos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($categorias); ?></div>
                <div class="stat-label">Categorias</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($produtos, fn($p) => $p['categoria'] === 'Drinks')); ?></div>
                <div class="stat-label">Drinks</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($produtos, fn($p) => $p['categoria'] === 'Petiscos')); ?></div>
                <div class="stat-label">Petiscos</div>
            </div>
        </div>

        <!-- Adicionar/Editar Produto -->
        <div class="section">
            <h2><?php echo $editProduct ? '<i data-feather="edit-3"></i> Editar Produto' : '<i data-feather="plus"></i> Adicionar Novo Produto'; ?></h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit_product' : 'add_product'; ?>">
                <?php if ($editProduct): ?>
                    <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nome">Nome do Produto *</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $editProduct ? htmlspecialchars($editProduct['nome']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao"><?php echo $editProduct ? htmlspecialchars($editProduct['descricao']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="preco">Preço (R$) *</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?php echo $editProduct ? $editProduct['preco'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoria *</label>
                    <div class="categoria-input-group">
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <option value="Cervejas" <?php echo ($editProduct && $editProduct['categoria'] === 'Cervejas') ? 'selected' : ''; ?>>Cervejas</option>
                            <option value="Destilados" <?php echo ($editProduct && $editProduct['categoria'] === 'Destilados') ? 'selected' : ''; ?>>Destilados</option>
                            <option value="Drinks" <?php echo ($editProduct && $editProduct['categoria'] === 'Drinks') ? 'selected' : ''; ?>>Drinks</option>
                            <option value="Petiscos" <?php echo ($editProduct && $editProduct['categoria'] === 'Petiscos') ? 'selected' : ''; ?>>Petiscos</option>
                            <option value="Refrigerantes" <?php echo ($editProduct && $editProduct['categoria'] === 'Refrigerantes') ? 'selected' : ''; ?>>Refrigerantes</option>
                            <option value="Outros" <?php echo ($editProduct && $editProduct['categoria'] === 'Outros') ? 'selected' : ''; ?>>Outros</option>
                            <option value="nova_categoria"><i data-feather="plus"></i> Nova Categoria</option>
                        </select>
                        <input type="text" id="nova_categoria" name="nova_categoria" placeholder="Digite o nome da nova categoria" style="display: none;">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?php echo $editProduct ? '<i data-feather="save"></i> Atualizar Produto' : '<i data-feather="plus"></i> Adicionar Produto'; ?>
                </button>
                
                <?php if ($editProduct): ?>
                    <a href="admin-panel.php" class="btn btn-secondary"><i data-feather="x"></i> Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Lista de Produtos -->
        <div class="section">
            <h2><i data-feather="package"></i> Produtos Cadastrados</h2>
            
            <?php if (empty($categorias)): ?>
                <p style="text-align: center; color: rgba(255,255,255,0.7); padding: 40px;">
                    Nenhum produto cadastrado ainda.
                </p>
            <?php else: ?>
                <?php foreach ($categorias as $categoria => $produtos_cat): ?>
                    <h3 style="color: #F45F0A; margin: 30px 0 20px 0; font-size: 1.5em;">
                        <i data-feather="folder"></i> <?php echo htmlspecialchars($categoria); ?>
                    </h3>
                    
                    <div class="products-grid">
                        <?php foreach ($produtos_cat as $produto): ?>
                            <div class="product-card">
                                <div class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></div>
                                <div class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                                <div class="product-category"><?php echo htmlspecialchars($produto['categoria']); ?></div>
                                
                                <?php if ($produto['descricao']): ?>
                                    <div class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></div>
                                <?php endif; ?>
                                
                                <div class="product-actions">
                                    <a href="?edit=<?php echo $produto['id']; ?>" class="btn btn-secondary btn-sm"><i data-feather="edit-3"></i> Editar</a>
                                    
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_product">
                                        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Tem certeza que deseja remover este produto?')">
                                            <i data-feather="trash-2"></i> Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Criar Conta -->
    <div id="createAccountModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i data-feather="user-plus"></i> Criar Nova Conta</h2>
                <span class="close" onclick="closeModal('createAccountModal')">&times;</span>
            </div>
            <form method="POST" id="createAccountForm">
                <input type="hidden" name="action" value="create_user">
                <div class="form-group">
                    <label for="new_username">Usuário *</label>
                    <input type="text" id="new_username" name="username" required minlength="3" maxlength="50">
                </div>
                <div class="form-group">
                    <label for="new_password">Senha *</label>
                    <input type="password" id="new_password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="new_nome">Nome Completo *</label>
                    <input type="text" id="new_nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="new_role">Tipo de Usuário *</label>
                    <select id="new_role" name="role" required>
                        <option value="">Selecione...</option>
                        <option value="user">Usuário</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> Criar Conta</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createAccountModal')"><i data-feather="x"></i> Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Gerenciar Usuários -->
    <div id="manageUsersModal" class="modal">
        <div class="modal-content large">
            <div class="modal-header">
                <h2><i data-feather="users"></i> Gerenciar Usuários</h2>
                <span class="close" onclick="closeModal('manageUsersModal')">&times;</span>
            </div>
            <div class="users-list">
                <div class="users-header">
                    <h3><i data-feather="users"></i> Lista de Usuários do Sistema</h3>
                    <p>Total de usuários: <strong><?php echo count($users); ?></strong></p>
                </div>
                
                <?php if (empty($users)): ?>
                    <div class="no-users">
                        <p>Nenhum usuário cadastrado ainda.</p>
                    </div>
                <?php else: ?>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Último Login</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                    <?php if ($user['id'] == ($_SESSION['user_id'] ?? 0)): ?>
                                        <span class="current-user-badge"><i data-feather="user"></i> Você</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['nome']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $user['ativo'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $user['ativo'] ? '<i data-feather="check-circle"></i> Ativo' : '<i data-feather="x-circle"></i> Inativo'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php 
                                    if (isset($user['last_login']) && $user['last_login']): 
                                        echo date('d/m/Y H:i', strtotime($user['last_login']));
                                    else:
                                        echo '<span class="never-logged">Nunca logou</span>';
                                    endif; 
                                    ?>
                                </td>
                                <td>
                                    <div class="user-actions">
                                        <button class="btn btn-sm btn-secondary" onclick="editUser(<?php echo $user['id']; ?>)" title="Editar usuário">
                                            <i data-feather="edit-3"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="resetPassword(<?php echo $user['id']; ?>)" title="Redefinir senha">
                                            <i data-feather="key"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="toggleUserStatus(<?php echo $user['id']; ?>, <?php echo $user['ativo']; ?>)" title="<?php echo $user['ativo'] ? 'Desativar usuário' : 'Ativar usuário'; ?>">
                                            <i data-feather="<?php echo $user['ativo'] ? 'user-x' : 'user-check'; ?>"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        console.log('Script carregado!');
        
        // Aguardar o carregamento completo da página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado completamente!');
            
            // Verificar se as funções estão disponíveis
            console.log('showModal disponível:', typeof showModal);
            console.log('closeModal disponível:', typeof closeModal);
            console.log('showSection disponível:', typeof showSection);
            
            // Testar se os modais existem
            console.log('Modal createAccountModal existe:', document.getElementById('createAccountModal'));
            console.log('Modal manageUsersModal existe:', document.getElementById('manageUsersModal'));
            console.log('Modal settingsModal existe:', document.getElementById('settingsModal'));
            
            // Removido: teste automático de abertura de modal
        });
        
        // Funções para modais
        function showModal(modalId) {
            console.log('showModal chamado com:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                console.log('Modal aberto:', modalId);
            } else {
                console.error('Modal não encontrado:', modalId);
            }
        }
        
        function closeModal(modalId) {
            console.log('closeModal chamado com:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                console.log('Modal fechado:', modalId);
            }
        }
        
        function showSection(sectionName) {
            console.log('showSection chamado com:', sectionName);
            // Atualizar menu ativo
            document.querySelectorAll('.menu-link').forEach(link => link.classList.remove('active'));
            event.target.classList.add('active');
        }
        
        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
        
        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.message');
            messages.forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
        
        // Fechar modal após criação de usuário bem-sucedida
        <?php if ($message && strpos($message, 'criado com sucesso') !== false): ?>
        setTimeout(() => {
            closeModal('createAccountModal');
            // Limpar formulário
            document.getElementById('createAccountForm').reset();
        }, 2000);
        <?php endif; ?>

        // Confirm delete
        function confirmDelete() {
            return confirm('Tem certeza que deseja remover este produto?');
        }

        // Funções para gerenciar usuários
        function editUser(userId) {
            alert('Função de editar usuário será implementada em breve! ID: ' + userId);
        }
        
        function toggleUserStatus(userId, currentStatus) {
            if (confirm('Tem certeza que deseja ' + (currentStatus ? 'desativar' : 'ativar') + ' este usuário?')) {
                // Aqui você pode implementar a lógica para alternar o status
                alert('Função de alternar status será implementada em breve! ID: ' + userId);
            }
        }
        
        function resetPassword(userId) {
            if (confirm('Tem certeza que deseja redefinir a senha deste usuário?\n\nA nova senha será: admin123')) {
                // Aqui você pode implementar a lógica para redefinir senha
                alert('Função de redefinir senha será implementada em breve! ID: ' + userId);
            }
        }
        
        // Função para testar modais
        function testModal() {
            alert('Teste de modal realizado!');
            // Exemplo de como abrir um modal
            // showModal('createAccountModal');
        }

        // Função para testar Bootstrap
        function testBootstrap() {
            alert('Teste de Bootstrap realizado!');
            // Exemplo de como usar Bootstrap, por exemplo, um modal
            // const myModal = new bootstrap.Modal(document.getElementById('createAccountModal'));
            // myModal.show();
        }
        
        // Controle do campo de nova categoria
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaSelect = document.getElementById('categoria');
            const novaCategoriaInput = document.getElementById('nova_categoria');
            
            if (categoriaSelect && novaCategoriaInput) {
                categoriaSelect.addEventListener('change', function() {
                    if (this.value === 'nova_categoria') {
                        novaCategoriaInput.style.display = 'block';
                        novaCategoriaInput.required = true;
                        novaCategoriaInput.focus();
                    } else {
                        novaCategoriaInput.style.display = 'none';
                        novaCategoriaInput.required = false;
                        novaCategoriaInput.value = '';
                    }
                });
            }
            
            // Inicializar ícones Feather
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
</body>
</html>

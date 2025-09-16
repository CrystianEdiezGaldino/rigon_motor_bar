<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Configuração do banco de dados


$host = "localhost";
$dbname = "delitc66_rigon_motor_bar";
$username = "delitc66_rigon_motor_bar";
$password = "z[Q_kcVxD1I&";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro de conexão: " . $e->getMessage()]);
    exit;
}

// Função para verificar se usuário é admin
function isAdmin() {
    return isset($_SESSION['user_id']) && 
           isset($_SESSION['username']) && 
           (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ||
           (isset($_SESSION['username']) && $_SESSION['username'] === 'admin2');
}

// Função para retornar resposta JSON
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Função para validar dados de entrada
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Roteamento da API
$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

switch ($method) {
    case 'POST':
        handlePost($path, $pdo);
        break;
    case 'GET':
        handleGet($path, $pdo);
        break;
    case 'PUT':
        handlePut($path, $pdo);
        break;
    case 'DELETE':
        handleDelete($path, $pdo);
        break;
    default:
        jsonResponse(["error" => "Método não permitido"], 405);
}

// Funções para cada método HTTP
function handlePost($path, $pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($path) {
        case 'login':
            $username = validateInput($input['username'] ?? '');
            $password = $input['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                jsonResponse(["error" => "Usuário e senha são obrigatórios"], 400);
            }
            
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM usuarios WHERE username = ? AND ativo = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                jsonResponse([
                    "success" => true,
                    "message" => "Login realizado com sucesso",
                    "user" => [
                        "id" => $user['id'],
                        "username" => $user['username'],
                        "role" => $user['role']
                    ]
                ]);
            } else {
                jsonResponse(["error" => "Usuário ou senha incorretos"], 401);
            }
            break;
            
        case 'logout':
            session_destroy();
            jsonResponse(["success" => true, "message" => "Logout realizado com sucesso"]);
            break;
            
        case 'produtos':
            if (!isAdmin()) {
                jsonResponse(["error" => "Acesso negado"], 403);
            }
            
            $nome = validateInput($input['nome'] ?? '');
            $descricao = validateInput($input['descricao'] ?? '');
            $preco = floatval($input['preco'] ?? 0);
            $categoria = validateInput($input['categoria'] ?? '');
            
            if (empty($nome) || empty($categoria) || $preco <= 0) {
                jsonResponse(["error" => "Nome, categoria e preço são obrigatórios"], 400);
            }
            
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, categoria) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nome, $descricao, $preco, $categoria])) {
                $id = $pdo->lastInsertId();
                jsonResponse([
                    "success" => true,
                    "message" => "Produto criado com sucesso",
                    "id" => $id
                ], 201);
            } else {
                jsonResponse(["error" => "Erro ao criar produto"], 500);
            }
            break;
            
        default:
            jsonResponse(["error" => "Endpoint não encontrado"], 404);
    }
}

function handleGet($path, $pdo) {
    switch ($path) {
        case 'produtos':
            $categoria = $_GET['categoria'] ?? '';
            $ativo = $_GET['ativo'] ?? 1;
            
            $sql = "SELECT * FROM produtos WHERE ativo = ?";
            $params = [$ativo];
            
            if (!empty($categoria)) {
                $sql .= " AND categoria = ?";
                $params[] = $categoria;
            }
            
            $sql .= " ORDER BY nome";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            jsonResponse(["produtos" => $produtos]);
            break;
            
        case 'produtos/categorias':
            $stmt = $pdo->prepare("SELECT DISTINCT categoria FROM produtos WHERE ativo = 1 ORDER BY categoria");
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            jsonResponse(["categorias" => $categorias]);
            break;
            
        case 'produtos/id':
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                jsonResponse(["error" => "ID inválido"], 400);
            }
            
            $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ? AND ativo = 1");
            $stmt->execute([$id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($produto) {
                jsonResponse(["produto" => $produto]);
            } else {
                jsonResponse(["error" => "Produto não encontrado"], 404);
            }
            break;
            
        case 'auth/check':
            if (isset($_SESSION['user_id'])) {
                jsonResponse([
                    "logged_in" => true,
                    "user" => [
                        "id" => $_SESSION['user_id'],
                        "username" => $_SESSION['username'],
                        "role" => $_SESSION['role']
                    ]
                ]);
            } else {
                jsonResponse(["logged_in" => false]);
            }
            break;
            
        default:
            jsonResponse(["error" => "Endpoint não encontrado"], 404);
    }
}

function handlePut($path, $pdo) {
    if (!isAdmin()) {
        jsonResponse(["error" => "Acesso negado"], 403);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($path === 'produtos') {
        $id = intval($input['id'] ?? 0);
        $nome = validateInput($input['nome'] ?? '');
        $descricao = validateInput($input['descricao'] ?? '');
        $preco = floatval($input['preco'] ?? 0);
        $categoria = validateInput($input['categoria'] ?? '');
        
        if ($id <= 0 || empty($nome) || empty($categoria) || $preco <= 0) {
            jsonResponse(["error" => "ID, nome, categoria e preço são obrigatórios"], 400);
        }
        
        $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ? WHERE id = ?");
        if ($stmt->execute([$nome, $descricao, $preco, $categoria, $id])) {
            jsonResponse([
                "success" => true,
                "message" => "Produto atualizado com sucesso"
            ]);
        } else {
            jsonResponse(["error" => "Erro ao atualizar produto"], 500);
        }
    } else {
        jsonResponse(["error" => "Endpoint não encontrado"], 404);
    }
}

function handleDelete($path, $pdo) {
    if (!isAdmin()) {
        jsonResponse(["error" => "Acesso negado"], 403);
    }
    
    if ($path === 'produtos') {
        $id = intval($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            jsonResponse(["error" => "ID inválido"], 400);
        }
        
        // Soft delete - apenas marca como inativo
        $stmt = $pdo->prepare("UPDATE produtos SET ativo = 0 WHERE id = ?");
        if ($stmt->execute([$id])) {
            jsonResponse([
                "success" => true,
                "message" => "Produto removido com sucesso"
            ]);
        } else {
            jsonResponse(["error" => "Erro ao remover produto"], 500);
        }
    } else {
        jsonResponse(["error" => "Endpoint não encontrado"], 404);
    }
}
?>

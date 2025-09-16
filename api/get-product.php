<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verificar se está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado'
    ]);
    exit;
}

// Verificar se é admin
if ($_SESSION['user_role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Acesso negado. Apenas administradores podem editar produtos.'
    ]);
    exit;
}

// Verificar se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID do produto não fornecido'
    ]);
    exit;
}

$id = intval($_GET['id']);

// Incluir arquivos necessários
include_once 'config/database.php';
include_once 'models/Produto.php';

try {
    // Instanciar classes
    $database = new Database();
    $db = $database->getConnection();
    $produto = new Produto($db);
    
    // Buscar produto por ID
    $produto_data = $produto->readOne($id);
    
    if ($produto_data) {
        echo json_encode([
            'success' => true,
            'produto' => [
                'id' => $produto_data['id'],
                'nome' => $produto_data['nome'],
                'descricao' => $produto_data['descricao'],
                'preco' => $produto_data['preco'],
                'categoria' => $produto_data['categoria'],
                'imagem' => $produto_data['imagem'],
                'ativo' => $produto_data['ativo']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Produto não encontrado'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Erro ao buscar produto: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor'
    ]);
}
?>

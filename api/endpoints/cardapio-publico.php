<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir arquivos necessários
include_once '../config/database.php';
include_once '../models/Produto.php';

// Instanciar banco de dados
$database = new Database();
$db = $database->getConnection();

// Instanciar objeto produto
$produto = new Produto($db);

// Obter método da requisição
$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET') {
    // Ler produtos ativos para o cardápio público
    if(isset($_GET['categoria'])) {
        // Ler produtos por categoria
        $stmt = $produto->readByCategory($_GET['categoria']);
        $num = $stmt->rowCount();
        
        if($num > 0) {
            $produtos_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $produto_item = array(
                    "id" => $id,
                    "nome" => $nome,
                    "descricao" => $descricao,
                    "preco" => $preco,
                    "categoria" => $categoria,
                    "imagem" => $imagem
                );
                array_push($produtos_arr, $produto_item);
            }
            echo json_encode($produtos_arr);
        } else {
            echo json_encode(array());
        }
    } else {
        // Ler todos os produtos ativos
        $stmt = $produto->read();
        $num = $stmt->rowCount();
        
        if($num > 0) {
            $produtos_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $produto_item = array(
                    "id" => $id,
                    "nome" => $nome,
                    "descricao" => $descricao,
                    "preco" => $preco,
                    "categoria" => $categoria,
                    "imagem" => $imagem
                );
                array_push($produtos_arr, $produto_item);
            }
            echo json_encode($produtos_arr);
        } else {
            echo json_encode(array());
        }
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Método não permitido. Apenas GET é aceito."));
}
?>

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir arquivos necessários
include_once '../config/database.php';
include_once '../models/Produto.php';

// Iniciar sessão para verificar login
session_start();

// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Acesso negado. Faça login primeiro."));
    exit();
}

// Instanciar banco de dados
$database = new Database();
$db = $database->getConnection();

// Instanciar objeto produto
$produto = new Produto($db);

// Obter método da requisição
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Ler produtos
        if(isset($_GET['id'])) {
            // Ler um produto específico
            $produto->id = $_GET['id'];
            if($produto->readOne()) {
                echo json_encode(array(
                    "id" => $produto->id,
                    "nome" => $produto->nome,
                    "descricao" => $produto->descricao,
                    "preco" => $produto->preco,
                    "categoria" => $produto->categoria,
                    "imagem" => $produto->imagem,
                    "ativo" => $produto->ativo,
                    "created_at" => $produto->created_at,
                    "updated_at" => $produto->updated_at
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Produto não encontrado."));
            }
        } elseif(isset($_GET['categoria'])) {
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
            // Ler todos os produtos
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
                        "imagem" => $imagem,
                        "ativo" => $ativo,
                        "created_at" => $created_at,
                        "updated_at" => $updated_at
                    );
                    array_push($produtos_arr, $produto_item);
                }
                echo json_encode($produtos_arr);
            } else {
                echo json_encode(array());
            }
        }
        break;
        
    case 'POST':
        // Criar produto
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nome) && !empty($data->preco) && !empty($data->categoria)) {
            $produto->nome = $data->nome;
            $produto->descricao = $data->descricao ?? '';
            $produto->preco = $data->preco;
            $produto->categoria = $data->categoria;
            $produto->imagem = $data->imagem ?? '';
            $produto->ativo = $data->ativo ?? 1;
            
            if($produto->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Produto criado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível criar o produto."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. Nome, preço e categoria são obrigatórios."));
        }
        break;
        
    case 'PUT':
        // Atualizar produto
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->nome) && !empty($data->preco) && !empty($data->categoria)) {
            $produto->id = $data->id;
            $produto->nome = $data->nome;
            $produto->descricao = $data->descricao ?? '';
            $produto->preco = $data->preco;
            $produto->categoria = $data->categoria;
            $produto->imagem = $data->imagem ?? '';
            $produto->ativo = $data->ativo ?? 1;
            
            if($produto->update()) {
                echo json_encode(array("message" => "Produto atualizado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível atualizar o produto."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. ID, nome, preço e categoria são obrigatórios."));
        }
        break;
        
    case 'DELETE':
        // Deletar produto (soft delete)
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $produto->id = $data->id;
            
            if($produto->delete()) {
                echo json_encode(array("message" => "Produto deletado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível deletar o produto."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID do produto é obrigatório."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}
?>

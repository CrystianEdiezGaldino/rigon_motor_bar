<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir arquivos necessários
include_once '../config/database.php';
include_once '../models/Usuario.php';

// Iniciar sessão
session_start();

// Instanciar banco de dados
$database = new Database();
$db = $database->getConnection();

// Instanciar objeto usuário
$usuario = new Usuario($db);

// Obter método da requisição
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        // Login ou logout
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->action)) {
            switch($data->action) {
                case 'login':
                    // Login
                    if(!empty($data->username) && !empty($data->password)) {
                        if($usuario->login($data->username, $data->password)) {
                            // Criar sessão
                            $_SESSION['user_id'] = $usuario->id;
                            $_SESSION['username'] = $usuario->username;
                            $_SESSION['user_nome'] = $usuario->nome;
                            $_SESSION['user_role'] = $usuario->role;
                            
                            echo json_encode(array(
                                "success" => true,
                                "message" => "Login realizado com sucesso!",
                                "user" => array(
                                    "id" => $usuario->id,
                                    "username" => $usuario->username,
                                    "nome" => $usuario->nome,
                                    "email" => $usuario->email,
                                    "role" => $usuario->role
                                )
                            ));
                        } else {
                            http_response_code(401);
                            echo json_encode(array(
                                "success" => false,
                                "message" => "Usuário ou senha incorretos."
                            ));
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(array(
                            "success" => false,
                            "message" => "Username e senha são obrigatórios."
                        ));
                    }
                    break;
                    
                case 'logout':
                    // Logout
                    session_destroy();
                    echo json_encode(array(
                        "success" => true,
                        "message" => "Logout realizado com sucesso!"
                    ));
                    break;
                    
                case 'register':
                    // Registrar novo usuário (apenas admin)
                    if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                        http_response_code(403);
                        echo json_encode(array(
                            "success" => false,
                            "message" => "Acesso negado. Apenas administradores podem criar usuários."
                        ));
                        break;
                    }
                    
                    if(!empty($data->username) && !empty($data->password) && !empty($data->nome) && !empty($data->email)) {
                        $usuario->username = $data->username;
                        $usuario->password = $data->password;
                        $usuario->nome = $data->nome;
                        $usuario->email = $data->email;
                        $usuario->role = $data->role ?? 'user';
                        $usuario->ativo = 1;
                        
                        if($usuario->create()) {
                            http_response_code(201);
                            echo json_encode(array(
                                "success" => true,
                                "message" => "Usuário criado com sucesso!"
                            ));
                        } else {
                            http_response_code(503);
                            echo json_encode(array(
                                "success" => false,
                                "message" => "Não foi possível criar o usuário."
                            ));
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(array(
                            "success" => false,
                            "message" => "Todos os campos são obrigatórios."
                        ));
                    }
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode(array(
                        "success" => false,
                        "message" => "Ação inválida."
                    ));
                    break;
            }
        } else {
            http_response_code(400);
            echo json_encode(array(
                "success" => false,
                "message" => "Ação não especificada."
            ));
        }
        break;
        
    case 'GET':
        // Verificar status da sessão
        if(isset($_SESSION['user_id'])) {
            echo json_encode(array(
                "success" => true,
                "logged_in" => true,
                "user" => array(
                    "id" => $_SESSION['user_id'],
                    "username" => $_SESSION['username'],
                    "nome" => $_SESSION['user_nome'],
                    "role" => $_SESSION['user_role']
                )
            ));
        } else {
            echo json_encode(array(
                "success" => true,
                "logged_in" => false
            ));
        }
        break;
        
    case 'DELETE':
        // Logout
        session_destroy();
        echo json_encode(array(
            "success" => true,
            "message" => "Logout realizado com sucesso!"
        ));
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array(
            "success" => false,
            "message" => "Método não permitido."
        ));
        break;
}
?>

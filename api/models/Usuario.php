<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $username;
    public $password;
    public $nome;
    public $email;
    public $role;
    public $ativo;
    public $created_at;
    public $last_login;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Login do usuário
    public function login($username, $password) {
        $query = "SELECT id, username, password, nome, email, role, ativo 
                  FROM " . $this->table_name . " 
                  WHERE username = ? AND ativo = 1 LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug detalhado
        error_log("=== DEBUG LOGIN ===");
        error_log("Username: $username");
        error_log("Password recebida: $password");
        error_log("Hash no banco: " . ($row ? $row['password'] : 'NÃO ENCONTRADO'));
        error_log("Usuário encontrado: " . ($row ? 'SIM' : 'NÃO'));
        
        if($row) {
            error_log("Verificando senha...");
            $password_verify_result = password_verify($password, $row['password']);
            error_log("password_verify resultado: " . ($password_verify_result ? 'TRUE' : 'FALSE'));
            
            if($password_verify_result) {
                error_log("Senha válida! Configurando sessão...");
                
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->nome = $row['nome'];
                $this->email = $row['email'];
                $this->role = $row['role'];
                $this->ativo = $row['ativo'];
                
                // Definir variáveis de sessão
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_username'] = $row['username'];
                $_SESSION['user_nome'] = $row['nome'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                $_SESSION['user_ativo'] = $row['ativo'];
                
                // Atualizar último login
                $this->updateLastLogin();
                
                error_log("Login bem-sucedido para: $username");
                return true;
            } else {
                error_log("Senha inválida para usuário: $username");
            }
        } else {
            error_log("Usuário não encontrado: $username");
        }
        
        error_log("=== FIM DEBUG LOGIN ===");
        return false;
    }

    // Atualizar último login
    public function updateLastLogin($userId = null) {
        $userId = $userId ?: $_SESSION['user_id'];
        if ($userId) {
            $query = "UPDATE " . $this->table_name . " SET last_login = NOW() WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $userId);
            $stmt->execute();
        }
    }

    // Verificar se usuário está logado
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
               !empty($_SESSION['user_id']) && 
               isset($_SESSION['user_role']) && 
               $_SESSION['user_ativo'] == 1;
    }

    // Verificar se usuário é admin
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user_role'] === 'admin';
    }

    // Criar novo usuário
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET username=:username, password=:password, nome=:nome, 
                      email=:email, role=:role, ativo=:ativo, created_at=:created_at";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->ativo = htmlspecialchars(strip_tags($this->ativo));
        $this->created_at = date('Y-m-d H:i:s');
        
        // Hash da senha
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Debug do cadastro
        error_log("=== DEBUG CADASTRO ===");
        error_log("Username: " . $this->username);
        error_log("Senha original: " . $this->password);
        error_log("Hash gerado: " . $hashed_password);
        error_log("Verificação do hash: " . (password_verify($this->password, $hashed_password) ? 'OK' : 'FALHOU'));
        error_log("=== FIM DEBUG CADASTRO ===");
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":ativo", $this->ativo);
        $stmt->bindParam(":created_at", $this->created_at);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Buscar usuário por ID
    public function readOne() {
        $query = "SELECT id, username, nome, email, role, ativo, created_at, last_login 
                  FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->username = $row['username'];
            $this->nome = $row['nome'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->ativo = $row['ativo'];
            $this->created_at = $row['created_at'];
            $this->last_login = $row['last_login'];
            return true;
        }
        
        return false;
    }
    
    // Verificar se username já existe
    public function checkUsernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}
?>

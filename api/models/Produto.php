<?php
class Produto {
    private $conn;
    private $table_name = "produtos";

    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $categoria;
    public $imagem;
    public $ativo;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ler todos os produtos
    public function read() {
        $query = "SELECT id, nome, descricao, preco, categoria, imagem, ativo, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE ativo = 1 
                  ORDER BY categoria, nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Ler um produto específico
    public function readOne() {
        $query = "SELECT id, nome, descricao, preco, categoria, imagem, ativo, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nome = $row['nome'];
            $this->descricao = $row['descricao'];
            $this->preco = $row['preco'];
            $this->categoria = $row['categoria'];
            $this->imagem = $row['imagem'];
            $this->ativo = $row['ativo'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        
        return false;
    }

    // Criar produto
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, descricao=:descricao, preco=:preco, 
                      categoria=:categoria, imagem=:imagem, ativo=:ativo, 
                      created_at=:created_at, updated_at=:updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->imagem = htmlspecialchars(strip_tags($this->imagem));
        $this->ativo = htmlspecialchars(strip_tags($this->ativo));
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":imagem", $this->imagem);
        $stmt->bindParam(":ativo", $this->ativo);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Atualizar produto
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome=:nome, descricao=:descricao, preco=:preco, 
                      categoria=:categoria, imagem=:imagem, ativo=:ativo, 
                      updated_at=:updated_at 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->imagem = htmlspecialchars(strip_tags($this->imagem));
        $this->ativo = htmlspecialchars(strip_tags($this->ativo));
        $this->updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":imagem", $this->imagem);
        $stmt->bindParam(":ativo", $this->ativo);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Deletar produto (soft delete)
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET ativo = 0, updated_at = :updated_at WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Buscar por categoria
    public function readByCategory($categoria) {
        $query = "SELECT id, nome, descricao, preco, categoria, imagem, ativo 
                  FROM " . $this->table_name . " 
                  WHERE categoria = ? AND ativo = 1 
                  ORDER BY nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $categoria);
        $stmt->execute();
        
        return $stmt;
    }
}
?>

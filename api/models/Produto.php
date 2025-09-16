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
                  ORDER BY categoria, nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Ler um produto específico
    public function readOne($id = null) {
        $product_id = $id ?: $this->id;
        
        if (!$product_id) {
            return false;
        }
        
        $query = "SELECT id, nome, descricao, preco, categoria, imagem, ativo, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $product_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            // Atualizar propriedades da classe
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->descricao = $row['descricao'];
            $this->preco = $row['preco'];
            $this->categoria = $row['categoria'];
            $this->imagem = $row['imagem'];
            $this->ativo = $row['ativo'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            
            // Retornar os dados para uso externo
            return $row;
        }
        
        return false;
    }

    // Criar produto
    public function create($nome = null, $descricao = null, $preco = null, $categoria = null, $imagem = null, $ativo = null) {
        // Usar parâmetros fornecidos ou propriedades da classe
        $product_nome = $nome ?: $this->nome;
        $product_descricao = $descricao ?: $this->descricao;
        $product_preco = $preco ?: $this->preco;
        $product_categoria = $categoria ?: $this->categoria;
        $product_imagem = $imagem ?: $this->imagem;
        $product_ativo = $ativo !== null ? $ativo : $this->ativo;
        
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, descricao=:descricao, preco=:preco, 
                      categoria=:categoria, imagem=:imagem, ativo=:ativo, 
                      created_at=:created_at, updated_at=:updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        $product_nome = htmlspecialchars(strip_tags($product_nome));
        $product_descricao = htmlspecialchars(strip_tags($product_descricao));
        $product_preco = htmlspecialchars(strip_tags($product_preco));
        $product_categoria = htmlspecialchars(strip_tags($product_categoria));
        $product_imagem = htmlspecialchars(strip_tags($product_imagem));
        $product_ativo = htmlspecialchars(strip_tags($product_ativo));
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(":nome", $product_nome);
        $stmt->bindParam(":descricao", $product_descricao);
        $stmt->bindParam(":preco", $product_preco);
        $stmt->bindParam(":categoria", $product_categoria);
        $stmt->bindParam(":imagem", $product_imagem);
        $stmt->bindParam(":ativo", $product_ativo);
        $stmt->bindParam(":created_at", $created_at);
        $stmt->bindParam(":updated_at", $updated_at);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Atualizar produto
    public function update($id = null, $nome = null, $descricao = null, $preco = null, $categoria = null, $imagem = null, $ativo = null) {
        // Usar parâmetros fornecidos ou propriedades da classe
        $product_id = $id ?: $this->id;
        $product_nome = $nome ?: $this->nome;
        $product_descricao = $descricao ?: $this->descricao;
        $product_preco = $preco ?: $this->preco;
        $product_categoria = $categoria ?: $this->categoria;
        $product_imagem = $imagem ?: $this->imagem;
        $product_ativo = $ativo !== null ? $ativo : $this->ativo;
        
        $query = "UPDATE " . $this->table_name . " 
                  SET nome=:nome, descricao=:descricao, preco=:preco, 
                      categoria=:categoria, imagem=:imagem, ativo=:ativo, 
                      updated_at=:updated_at 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $product_nome = htmlspecialchars(strip_tags($product_nome));
        $product_descricao = htmlspecialchars(strip_tags($product_descricao));
        $product_preco = htmlspecialchars(strip_tags($product_preco));
        $product_categoria = htmlspecialchars(strip_tags($product_categoria));
        $product_imagem = htmlspecialchars(strip_tags($product_imagem));
        $product_ativo = htmlspecialchars(strip_tags($product_ativo));
        $updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(":nome", $product_nome);
        $stmt->bindParam(":descricao", $product_descricao);
        $stmt->bindParam(":preco", $product_preco);
        $stmt->bindParam(":categoria", $product_categoria);
        $stmt->bindParam(":imagem", $product_imagem);
        $stmt->bindParam(":ativo", $product_ativo);
        $stmt->bindParam(":updated_at", $updated_at);
        $stmt->bindParam(":id", $product_id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Deletar produto (soft delete)
    public function delete($id = null) {
        error_log("=== DEBUG DELETE() ===");
        error_log("ID recebido: " . ($id ?: 'null'));
        error_log("ID da classe: " . ($this->id ?: 'null'));
        
        $product_id = $id ?: $this->id;
        error_log("ID final usado: $product_id");
        
        if (!$product_id) {
            error_log("❌ ERRO: Nenhum ID fornecido para delete");
            error_log("=== FIM DEBUG DELETE() ===");
            return false;
        }
        
        error_log("🔄 Executando query de delete para ID: $product_id");
        
        $query = "UPDATE " . $this->table_name . " SET ativo = 0, updated_at = :updated_at WHERE id = :id";
        error_log("Query SQL: $query");
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("❌ ERRO: Falha ao preparar statement");
            error_log("=== FIM DEBUG DELETE() ===");
            return false;
        }
        
        $updated_at = date('Y-m-d H:i:s');
        error_log("Timestamp: $updated_at");
        
        $stmt->bindParam(":updated_at", $updated_at);
        $stmt->bindParam(":id", $product_id);
        
        error_log("🔄 Executando statement...");
        $resultado = $stmt->execute();
        error_log("Resultado da execução: " . ($resultado ? 'true' : 'false'));
        
        if ($resultado) {
            $rowCount = $stmt->rowCount();
            error_log("Linhas afetadas: $rowCount");
            
            if ($rowCount > 0) {
                error_log("✅ SUCESSO: Produto marcado como inativo");
            } else {
                error_log("⚠️ AVISO: Nenhuma linha foi afetada (produto pode não existir)");
            }
        } else {
            error_log("❌ ERRO na execução: " . print_r($stmt->errorInfo(), true));
        }
        
        error_log("=== FIM DEBUG DELETE() ===");
        
        return $resultado;
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

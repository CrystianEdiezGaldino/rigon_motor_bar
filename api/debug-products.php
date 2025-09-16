<?php
// Script para debug dos produtos no banco
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🔍 Debug dos Produtos no Banco</h2>";

try {
    // Incluir arquivos necessários
    include_once 'config/database.php';
    include_once 'models/Produto.php';
    
    // Instanciar classes
    $database = new Database();
    $db = $database->getConnection();
    $produto = new Produto($db);
    
    echo "<h3>📊 Verificando tabela produtos:</h3>";
    
    // Verificar se a tabela existe
    $query = "SHOW TABLES LIKE 'produtos'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p style='color: red;'>❌ Tabela 'produtos' não encontrada!</p>";
        exit;
    } else {
        echo "<p style='color: green;'>✅ Tabela 'produtos' encontrada</p>";
    }
    
    // Verificar estrutura da tabela
    echo "<h3>🏗️ Estrutura da tabela:</h3>";
    $query = "DESCRIBE produtos";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Contar produtos
    echo "<h3>📈 Contagem de produtos:</h3>";
    $query = "SELECT COUNT(*) as total FROM produtos";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total de produtos: <strong>{$count['total']}</strong></p>";
    
    // Contar por status
    $query = "SELECT ativo, COUNT(*) as total FROM produtos GROUP BY ativo";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $status_counts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Por status:</p>";
    foreach ($status_counts as $status) {
        $status_text = $status['ativo'] ? 'Ativo' : 'Inativo';
        echo "<p>- {$status_text}: <strong>{$status['total']}</strong></p>";
    }
    
    // Listar todos os produtos
    echo "<h3>📋 Todos os produtos:</h3>";
    $query = "SELECT * FROM produtos ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($produtos)) {
        echo "<p style='color: orange;'>⚠️ Nenhum produto encontrado na tabela</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Categoria</th><th>Imagem</th><th>Ativo</th><th>Criado</th><th>Atualizado</th></tr>";
        
        foreach ($produtos as $prod) {
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['nome']}</td>";
            echo "<td>" . substr($prod['descricao'], 0, 50) . "...</td>";
            echo "<td>R$ " . number_format($prod['preco'], 2, ',', '.') . "</td>";
            echo "<td>{$prod['categoria']}</td>";
            echo "<td>{$prod['imagem']}</td>";
            echo "<td>" . ($prod['ativo'] ? '✅' : '❌') . "</td>";
            echo "<td>{$prod['created_at']}</td>";
            echo "<td>{$prod['updated_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Testar método read da classe
    echo "<h3>🧪 Testando método read() da classe:</h3>";
    $stmt = $produto->read();
    if ($stmt) {
        $produtos_classe = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos_classe[] = $row;
        }
        echo "<p>Método read() retornou: <strong>" . count($produtos_classe) . "</strong> produtos</p>";
        
        if (!empty($produtos_classe)) {
            echo "<p>Primeiro produto: <strong>{$produtos_classe[0]['nome']}</strong></p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Método read() falhou</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

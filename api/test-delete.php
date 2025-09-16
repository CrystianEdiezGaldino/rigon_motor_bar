<?php
// Script de teste para verificar a funcionalidade de remoção
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🧪 Teste da Funcionalidade de Remoção</h2>";

// Incluir arquivos necessários
include_once 'config/database.php';
include_once 'models/Produto.php';

try {
    // Instanciar classes
    $database = new Database();
    $db = $database->getConnection();
    $produto = new Produto($db);
    
    echo "<h3>📊 Status Atual dos Produtos:</h3>";
    
    // Listar todos os produtos (ativos e inativos)
    $query = "SELECT id, nome, ativo FROM produtos ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
        echo "<tr style='background: #F45F0A; color: white;'>";
        echo "<th>ID</th><th>Nome</th><th>Status</th><th>Ações</th>";
        echo "</tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['ativo'] ? '✅ Ativo' : '❌ Inativo';
            $statusColor = $row['ativo'] ? 'green' : 'red';
            
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nome']}</td>";
            echo "<td style='color: $statusColor;'>{$status}</td>";
            echo "<td>";
            
            if ($row['ativo']) {
                echo "<form method='POST' style='display: inline;' onsubmit='return confirm(\"Remover {$row['nome']}?\")'>";
                echo "<input type='hidden' name='action' value='delete_product'>";
                echo "<input type='hidden' name='id' value='{$row['id']}'>";
                echo "<button type='submit' style='background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;'>🗑️ Remover</button>";
                echo "</form>";
            } else {
                echo "<button style='background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;' onclick='ativarProduto({$row['id']})'>✅ Reativar</button>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    
    echo "<h3>🔧 Teste Manual:</h3>";
    echo "<p>1. Clique em '🗑️ Remover' para testar a remoção</p>";
    echo "<p>2. Verifique se o produto ficou inativo</p>";
    echo "<p>3. Use '✅ Reativar' para restaurar o produto</p>";
    
    echo "<h3>📝 Logs de Debug:</h3>";
    echo "<p>Verifique o arquivo de log do PHP para ver os detalhes da remoção</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

// Processar ações POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_product') {
        $id = $_POST['id'] ?? '';
        
        if ($produto->delete($id)) {
            echo "<script>alert('Produto removido com sucesso!'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Erro ao remover produto!');</script>";
        }
    }
}
?>

<script>
function ativarProduto(id) {
    if (confirm('Deseja reativar este produto?')) {
        // Aqui você pode implementar a reativação
        alert('Funcionalidade de reativação será implementada em breve!');
    }
}
</script>




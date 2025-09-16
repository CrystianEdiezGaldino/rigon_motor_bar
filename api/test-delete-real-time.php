<?php
// Script para testar remoção em tempo real com logs detalhados
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🧪 Teste de Remoção em Tempo Real</h2>";

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
    $query = "SELECT id, nome, ativo, updated_at FROM produtos ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
        echo "<tr style='background: #F45F0A; color: white;'>";
        echo "<th>ID</th><th>Nome</th><th>Status</th><th>Última Atualização</th><th>Ações</th>";
        echo "</tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['ativo'] ? '✅ Ativo' : '❌ Inativo';
            $statusColor = $row['ativo'] ? 'green' : 'red';
            $updated = $row['updated_at'] ?: 'Nunca';
            
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nome']}</td>";
            echo "<td style='color: $statusColor;'>{$status}</td>";
            echo "<td>{$updated}</td>";
            echo "<td>";
            
            if ($row['ativo']) {
                echo "<button style='background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;' onclick='testarDelete({$row['id']}, \"{$row['nome']}\")'>🗑️ Testar Remoção</button>";
            } else {
                echo "<span style='color: #6c757d;'>Já removido</span>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    
    echo "<h3>🔧 Como usar:</h3>";
    echo "<p>1. Clique em '🗑️ Testar Remoção' para um produto</p>";
    echo "<p>2. Confirme a remoção</p>";
    echo "<p>3. Verifique os logs no console do navegador</p>";
    echo "<p>4. Recarregue a página para ver as mudanças</p>";
    
    echo "<h3>📝 Logs de Debug:</h3>";
    echo "<p>Abra o console do navegador (F12) para ver os logs detalhados</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}
?>

<script>
function testarDelete(id, nome) {
    console.log('🧪 === TESTE DE REMOÇÃO INICIADO ===');
    console.log('📋 ID:', id);
    console.log('📋 Nome:', nome);
    
    if (confirm(`Deseja testar a remoção do produto "${nome}" (ID: ${id})?`)) {
        console.log('✅ Usuário confirmou o teste');
        
        // Simular o envio do formulário
        const formData = new FormData();
        formData.append('action', 'delete_product');
        formData.append('id', id);
        
        console.log('📤 Enviando dados:', {
            action: 'delete_product',
            id: id
        });
        
        // Fazer requisição AJAX para testar
        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('📡 Resposta recebida:', response);
            return response.text();
        })
        .then(html => {
            console.log('📄 HTML recebido:', html);
            
            // Verificar se há mensagem de sucesso
            if (html.includes('Produto removido com sucesso')) {
                console.log('🎉 SUCESSO: Produto foi removido!');
                alert('✅ Produto removido com sucesso! Recarregue a página para ver as mudanças.');
            } else if (html.includes('Erro ao remover produto')) {
                console.log('❌ ERRO: Falha ao remover produto');
                alert('❌ Erro ao remover produto! Verifique o console para mais detalhes.');
            } else {
                console.log('⚠️ AVISO: Resposta inesperada');
                alert('⚠️ Resposta inesperada. Verifique o console para mais detalhes.');
            }
        })
        .catch(error => {
            console.error('💥 Erro na requisição:', error);
            alert('💥 Erro na requisição: ' + error.message);
        });
        
        console.log('🧪 === TESTE DE REMOÇÃO FINALIZADO ===');
    } else {
        console.log('❌ Usuário cancelou o teste');
    }
}

// Log quando a página carrega
console.log('🚀 Página de teste carregada');
console.log('📋 Abra o console (F12) para ver os logs detalhados');
</script>




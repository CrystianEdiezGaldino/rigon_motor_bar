<?php
// Script de teste para get-product.php
header("Content-Type: text/html; charset=UTF-8");

echo "<h2>🧪 Teste do get-product.php</h2>";

// Simular uma sessão de admin para teste
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';
$_SESSION['user_ativo'] = 1;

echo "<h3>📊 Sessão simulada:</h3>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<h3>🔍 Testando get-product.php:</h3>";

// Testar com ID 1
$url = 'get-product.php?id=1';
echo "<p>Testando URL: <code>$url</code></p>";

// Fazer requisição interna
ob_start();
include 'get-product.php';
$response = ob_get_clean();

echo "<h4>📡 Resposta recebida:</h4>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Tentar decodificar JSON
$json_data = json_decode($response, true);
if ($json_data) {
    echo "<h4>✅ JSON válido:</h4>";
    echo "<pre>" . print_r($json_data, true) . "</pre>";
    
    if (isset($json_data['success']) && $json_data['success']) {
        echo "<p style='color: green;'>🎉 get-product.php está funcionando!</p>";
    } else {
        echo "<p style='color: red;'>❌ get-product.php retornou erro: " . ($json_data['message'] ?? 'Desconhecido') . "</p>";
    }
} else {
    echo "<h4>❌ JSON inválido:</h4>";
    echo "<p>Erro ao decodificar JSON. Resposta pode conter HTML ou PHP errors.</p>";
}

echo "<hr>";
echo "<h3>🔧 Para testar no navegador:</h3>";
echo "<p>1. Abra o console do navegador (F12)</p>";
echo "<p>2. Acesse: <a href='index.php'>index.php</a></p>";
echo "<p>3. Faça login como admin</p>";
echo "<p>4. Clique em editar um produto</p>";
echo "<p>5. Verifique os logs no console</p>";
?>

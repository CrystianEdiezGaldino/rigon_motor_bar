<?php
// Teste simples do endpoint
echo "<h1>Teste do Endpoint de Upload</h1>";

// Teste 1: Verificar se o arquivo existe
echo "<h2>1. Verificação de Arquivos</h2>";
echo "upload-cardapio.php existe: " . (file_exists('upload-cardapio.php') ? 'Sim' : 'Não') . "<br>";
echo "upload-page.php existe: " . (file_exists('upload-page.php') ? 'Sim' : 'Não') . "<br>";

// Teste 2: Verificar diretório de destino
echo "<h2>2. Verificação de Diretório</h2>";
$targetDir = dirname(__DIR__) . '/public/assets/imagens/';
echo "Diretório de destino: " . $targetDir . "<br>";
echo "Diretório existe: " . (is_dir($targetDir) ? 'Sim' : 'Não') . "<br>";
echo "Diretório gravável: " . (is_writable($targetDir) ? 'Sim' : 'Não') . "<br>";

// Teste 3: Verificar permissões
echo "<h2>3. Verificação de Permissões</h2>";
echo "Diretório pai: " . dirname($targetDir) . "<br>";
echo "Diretório pai gravável: " . (is_writable(dirname($targetDir)) ? 'Sim' : 'Não') . "<br>";

// Teste 4: Testar include da página
echo "<h2>4. Teste de Include da página</h2>";
if (file_exists('upload-page.php')) {
    echo "Tentando incluir upload-page.php...<br>";
    ob_start();
    include 'upload-page.php';
    $html = ob_get_clean();
    echo "Página incluída com sucesso! Tamanho: " . strlen($html) . " caracteres<br>";
} else {
    echo "Arquivo upload-page.php não encontrado!<br>";
}

// Teste 5: Simular requisição POST
echo "<h2>5. Teste de Simulação POST</h2>";
echo "Simulando requisição POST...<br>";

// Simular $_SERVER
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'multipart/form-data';

// Capturar output do endpoint
ob_start();
include 'upload-cardapio.php';
$output = ob_get_clean();

echo "Resposta do endpoint:<br>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

// Teste 6: Verificar logs de erro
echo "<h2>6. Logs de Erro PHP</h2>";
$errorLog = ini_get('error_log');
echo "Arquivo de log: " . $errorLog . "<br>";
if (file_exists($errorLog)) {
    echo "Últimas 10 linhas do log:<br>";
    $lines = file($errorLog);
    $lastLines = array_slice($lines, -10);
    echo "<pre>" . htmlspecialchars(implode('', $lastLines)) . "</pre>";
} else {
    echo "Arquivo de log não encontrado.<br>";
}
?>

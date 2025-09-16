<?php
// Arquivo de teste simples para verificar se a API está funcionando
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode([
    "status" => "success",
    "message" => "API está funcionando!",
    "timestamp" => date('Y-m-d H:i:s'),
    "php_version" => PHP_VERSION,
    "server" => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    "method" => $_SERVER['REQUEST_METHOD'],
    "url" => $_SERVER['REQUEST_URI'],
    "headers" => getallheaders()
]);
?>

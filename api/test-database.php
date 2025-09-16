<?php
// Arquivo de teste para verificar a conexão com o banco de dados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
    // Incluir configuração do banco
    include_once 'config/database.php';
    
    // Testar conexão
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        // Testar query simples
        $stmt = $db->query("SELECT 1 as test");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar se o banco 'rigon' existe
        $stmt = $db->query("SHOW DATABASES LIKE 'rigon'");
        $rigonExists = $stmt->rowCount() > 0;
        
        // Se o banco rigon existir, tentar conectar nele
        if ($rigonExists) {
            $db->exec("USE rigon");
            
            // Verificar tabelas
            $stmt = $db->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Contar produtos
            $stmt = $db->query("SELECT COUNT(*) as total FROM produtos WHERE ativo = 1");
            $produtosCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo json_encode([
                "status" => "success",
                "message" => "Conexão com banco estabelecida com sucesso!",
                "database" => "rigon",
                "tables" => $tables,
                "produtos_ativos" => $produtosCount,
                "timestamp" => date('Y-m-d H:i:s'),
                "php_version" => PHP_VERSION,
                "pdo_drivers" => PDO::getAvailableDrivers()
            ]);
        } else {
            echo json_encode([
                "status" => "warning",
                "message" => "Conexão MySQL OK, mas banco 'rigon' não encontrado",
                "available_databases" => $db->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN),
                "timestamp" => date('Y-m-d H:i:s')
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Falha ao conectar com o banco de dados",
            "timestamp" => date('Y-m-d H:i:s')
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao conectar com banco: " . $e->getMessage(),
        "error_type" => get_class($e),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "timestamp" => date('Y-m-d H:i:s')
    ]);
}
?>

<?php
// Headers CORS (sempre aplicados)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Log da requisição para debug
error_log("Upload request - Method: " . $_SERVER['REQUEST_METHOD'] . " - Content-Type: " . $_SERVER['CONTENT_TYPE']);

// Handle GET requests - show upload page
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: text/html; charset=utf-8');
    include 'upload-page.html';
    exit();
}

// Para todas as outras requisições, usar JSON
header('Content-Type: application/json; charset=utf-8');

// Only allow POST requests for actual uploads
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'error' => 'Método não permitido',
        'method' => $_SERVER['REQUEST_METHOD'],
        'allowed' => 'POST',
        'note' => 'Use POST para fazer upload de arquivos'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Check if file was uploaded
if (!isset($_FILES['cardapio_pdf']) || $_FILES['cardapio_pdf']['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'Arquivo excede o limite do servidor',
        UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o limite do formulário',
        UPLOAD_ERR_PARTIAL => 'Upload parcial do arquivo',
        UPLOAD_ERR_NO_FILE => 'Nenhum arquivo enviado',
        UPLOAD_ERR_NO_TMP_DIR => 'Diretório temporário não encontrado',
        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever arquivo',
        UPLOAD_ERR_EXTENSION => 'Upload bloqueado por extensão'
    ];
    
    $errorCode = $_FILES['cardapio_pdf']['error'] ?? 'unknown';
    $errorMessage = $errorMessages[$errorCode] ?? 'Erro desconhecido no upload';
    
    http_response_code(400);
    echo json_encode([
        'error' => 'Erro no upload do arquivo',
        'details' => $errorMessage,
        'code' => $errorCode
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$uploadedFile = $_FILES['cardapio_pdf'];

// Validate file type
$allowedTypes = ['application/pdf'];
$fileType = mime_content_type($uploadedFile['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Tipo de arquivo não permitido',
        'received_type' => $fileType,
        'allowed_types' => $allowedTypes
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Validate file size (max 10MB)
$maxSize = 10 * 1024 * 1024; // 10MB
if ($uploadedFile['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Arquivo muito grande',
        'file_size' => $uploadedFile['size'],
        'max_size' => $maxSize
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Define target directory and filename
$targetDir = dirname(__DIR__) . '/assets/imagens/';
$targetFile = $targetDir . 'cardapio.pdf';

// Log paths for debug
error_log("Target directory: " . $targetDir);
error_log("Target file: " . $targetFile);
error_log("Directory exists: " . (is_dir($targetDir) ? 'yes' : 'no'));
error_log("Directory writable: " . (is_writable($targetDir) ? 'yes' : 'no'));

// Create directory if it doesn't exist
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0755, true)) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Erro ao criar diretório de destino',
            'directory' => $targetDir,
            'debug' => [
                'parent_dir' => dirname($targetDir),
                'parent_writable' => is_writable(dirname($targetDir)),
                'current_dir' => getcwd()
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// Check if directory is writable
if (!is_writable($targetDir)) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Diretório de destino não tem permissão de escrita',
        'directory' => $targetDir
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Backup existing file if it exists
$backupFile = $targetDir . 'cardapio_backup_' . date('Y-m-d_H-i-s') . '.pdf';
if (file_exists($targetFile)) {
    if (!copy($targetFile, $backupFile)) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Erro ao criar backup do arquivo existente',
            'backup_path' => $backupFile
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// Move uploaded file to target location
$uploadResult = move_uploaded_file($uploadedFile['tmp_name'], $targetFile);

// Log upload attempt
error_log("Upload attempt - Source: " . $uploadedFile['tmp_name'] . " - Target: " . $targetFile . " - Result: " . ($uploadResult ? 'success' : 'failed'));

if ($uploadResult) {
    // Set proper permissions
    chmod($targetFile, 0644);
    
    // Log the upload
    $logMessage = date('Y-m-d H:i:s') . " - Cardápio atualizado. Arquivo: " . $uploadedFile['name'] . " (" . $uploadedFile['size'] . " bytes)\n";
    file_put_contents($targetDir . 'upload_log.txt', $logMessage, FILE_APPEND | LOCK_EX);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Cardápio atualizado com sucesso!',
        'filename' => 'cardapio.pdf',
        'size' => $uploadedFile['size'],
        'backup_created' => file_exists($backupFile),
        'backup_file' => file_exists($backupFile) ? basename($backupFile) : null,
        'url' => 'https://rigonmotorbar.com.br/assets/imagens/cardapio.pdf',
        'debug' => [
            'target_file' => $targetFile,
            'file_exists' => file_exists($targetFile),
            'file_size' => file_exists($targetFile) ? filesize($targetFile) : 0
        ]
    ], JSON_UNESCAPED_UNICODE);
} else {
    // Get detailed error information
    $errorInfo = [
        'upload_error' => $uploadedFile['error'],
        'tmp_name' => $uploadedFile['tmp_name'],
        'tmp_exists' => file_exists($uploadedFile['tmp_name']),
        'target_dir' => $targetDir,
        'target_dir_writable' => is_writable($targetDir),
        'target_file' => $targetFile,
        'php_upload_errors' => [
            'UPLOAD_ERR_OK' => UPLOAD_ERR_OK,
            'UPLOAD_ERR_INI_SIZE' => UPLOAD_ERR_INI_SIZE,
            'UPLOAD_ERR_FORM_SIZE' => UPLOAD_ERR_FORM_SIZE,
            'UPLOAD_ERR_PARTIAL' => UPLOAD_ERR_PARTIAL,
            'UPLOAD_ERR_NO_FILE' => UPLOAD_ERR_NO_FILE,
            'UPLOAD_ERR_NO_TMP_DIR' => UPLOAD_ERR_NO_TMP_DIR,
            'UPLOAD_ERR_CANT_WRITE' => UPLOAD_ERR_CANT_WRITE,
            'UPLOAD_ERR_EXTENSION' => UPLOAD_ERR_EXTENSION
        ]
    ];
    
    // Restore backup if upload failed
    if (file_exists($backupFile)) {
        copy($backupFile, $targetFile);
        unlink($backupFile);
    }
    
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao salvar o arquivo',
        'target_file' => $targetFile,
        'debug' => $errorInfo
    ], JSON_UNESCAPED_UNICODE);
}
?>

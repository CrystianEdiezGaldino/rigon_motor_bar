<?php
/**
 * Smoke: upload-page usa CSS compartilhado e upload-cardapio inclui PHP da página.
 * Uso: php api/tests/upload-page-style.test.php
 */
declare(strict_types=1);

$base = dirname(__DIR__);
$uploadPhp = $base . '/upload-cardapio.php';
$style = $base . '/style-custom.css';
$page = $base . '/upload-page.php';

foreach (['upload-cardapio.php' => $uploadPhp, 'style-custom.css' => $style, 'upload-page.php' => $page] as $name => $path) {
    if (!is_readable($path)) {
        fwrite(STDERR, "FAIL: falta {$name}\n");
        exit(1);
    }
}

$src = file_get_contents($uploadPhp);
if (strpos($src, "upload-page.php") === false) {
    fwrite(STDERR, "FAIL: upload-cardapio não referencia upload-page.php\n");
    exit(1);
}

$pg = file_get_contents($page);
foreach (['style-custom.css', 'app-shell', 'input-file-upload', 'uploadForm'] as $n) {
    if (strpos($pg, $n) === false) {
        fwrite(STDERR, "FAIL: upload-page.php falta {$n}\n");
        exit(1);
    }
}

$css = file_get_contents($style);
if (strpos($css, 'input-file-upload') === false || strpos($css, '#upload-result') === false) {
    fwrite(STDERR, "FAIL: style-custom.css sem estilos de upload\n");
    exit(1);
}

echo "OK upload + style-custom compartilhado\n";
exit(0);

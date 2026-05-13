<?php
/**
 * Smoke: painel admin — layout sidebar, marca e modais.
 * Uso: php api/tests/admin-panel-design.test.php
 */
declare(strict_types=1);

$path = dirname(__DIR__) . '/admin-panel.php';
$src = is_readable($path) ? file_get_contents($path) : false;

if ($src === false) {
    fwrite(STDERR, "FAIL: não leu {$path}\n");
    exit(1);
}

$needles = [
    'app-shell',
    'sidebar-link',
    'class="app-modal"',
    'app-modal__panel',
    'Montserrat',
    'href="style-custom.css"',
];

foreach ($needles as $n) {
    if (strpos($src, $n) === false) {
        fwrite(STDERR, "FAIL: falta: {$n}\n");
        exit(1);
    }
}

$cssPath = dirname(__DIR__) . '/style-custom.css';
$css = is_readable($cssPath) ? file_get_contents($cssPath) : false;
if ($css === false || strpos($css, '--primary: #F45F0A') === false) {
    fwrite(STDERR, "FAIL: style-custom.css inexistente ou sem tokens de marca\n");
    exit(1);
}

echo "OK admin-panel layout + marca\n";
exit(0);

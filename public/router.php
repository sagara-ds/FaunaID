<?php
/**
 * Router untuk PHP built-in server (pengganti mod_rewrite)
 * Jalankan dari root proyek: php -S localhost:8000 -t public public/router.php
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// File fisik yang benar-benar ada (assets, uploads) dilayani langsung
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

require __DIR__ . '/index.php';

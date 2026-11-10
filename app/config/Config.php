<?php
/**
 * Konfigurasi Utama Aplikasi FaunaID
 */

// Timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Start session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database MySQL
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'fauna_id');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

// Konstanta Direktori Sistem
define('APP_PATH', dirname(__DIR__));
define('ROOT_PATH', dirname(APP_PATH));
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
define('UPLOAD_PATH', PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploads');

// Deteksi Otomatis Base URL
function detectBaseUrl(): string {
    if (defined('CUSTOM_BASE_URL') && CUSTOM_BASE_URL) {
        return rtrim(CUSTOM_BASE_URL, '/');
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $baseDir = dirname($scriptName);

    // Normalisasi base directory
    if ($baseDir === '/' || $baseDir === '\\' || $baseDir === '.') {
        $baseDir = '';
    }

    // Jika baseDir berakhir dengan /public, pertahankan atau potong sesuai kebutuhan
    return rtrim($protocol . $host . $baseDir, '/');
}

define('BASE_URL', detectBaseUrl());
define('APP_NAME', 'FaunaID');
define('APP_TAGLINE', 'Eksplorasi & Edukasi Keanekaragaman Fauna Nusantara');


<?php
/**
 * FaunaID - Public Single Entry Point
 * Platform Edukasi & Ensiklopedia Keanekaragaman Fauna Nusantara
 */

// Error reporting (Development mode)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Muat Konfigurasi & Helper Sistem
require_once dirname(__DIR__) . '/app/config/Config.php';
require_once dirname(__DIR__) . '/app/config/Database.php';
require_once dirname(__DIR__) . '/app/core/Helper.php';
require_once dirname(__DIR__) . '/app/core/App.php';

// Inisialisasi dan Jalankan Routing Aplikasi
$app = new App();


<?php
/**
 * FaunaID - Database Seed & Setup Helper
 * Bisa dijalankan via CLI: php database/seed.php
 */

require_once __DIR__ . '/../app/config/Config.php';
require_once __DIR__ . '/../app/config/Database.php';

echo "=== FaunaID Database Setup & Seeder ===\n";

try {
    $pdo = Database::getConnection();
    $driver = Database::getDriver();
    echo "Terhubung menggunakan driver: " . strtoupper($driver) . "\n";

    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        die("Error: schema.sql tidak ditemukan di " . $schemaFile . "\n");
    }

    if ($driver === 'mysql') {
        $sql = file_get_contents($schemaFile);
        $pdo->exec($sql);
        echo "Skema MySQL berhasil diimpor beserta data seed awal!\n";
    } else {
        echo "Menginisialisasi skema SQLite lokal...\n";
        Database::initSqliteDatabase($pdo);
        echo "Database SQLite berhasil disiapkan beserta data seed awal!\n";
    }

    echo "Selesai! Database siap digunakan.\n";
} catch (Exception $e) {
    echo "Gagal melakukan seeding: " . $e->getMessage() . "\n";
}


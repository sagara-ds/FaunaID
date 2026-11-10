<?php
/**
 * Base Model
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

class Model {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Eksekusi query dengan prepared statement
     */
    protected function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Ambil seluruh baris hasil query
     */
    protected function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Ambil satu baris pertama hasil query
     */
    protected function fetch(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Ambil satu kolom skalar
     */
    protected function fetchColumn(string $sql, array $params = []) {
        return $this->query($sql, $params)->fetchColumn();
    }

    /**
     * Ambil ID hasil auto-increment terakhir
     */
    protected function lastInsertId(): string {
        return $this->db->lastInsertId();
    }
}


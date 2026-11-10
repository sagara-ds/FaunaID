<?php
/**
 * Habitat Model
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class HabitatModel extends Model {
    public function getAll(): array {
        $sql = "SELECT h.*, COUNT(f.id) AS total_fauna 
                FROM habitats h 
                LEFT JOIN fauna f ON h.id = f.habitat_id 
                GROUP BY h.id 
                ORDER BY h.nama_habitat ASC";
        return $this->fetchAll($sql);
    }

    public function getById(int $id): ?array {
        return $this->fetch("SELECT * FROM habitats WHERE id = ?", [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO habitats (nama_habitat, deskripsi) VALUES (?, ?)";
        $this->query($sql, [
            $data['nama_habitat'],
            $data['deskripsi'] ?? null
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE habitats SET nama_habitat = ?, deskripsi = ? WHERE id = ?";
        $stmt = $this->query($sql, [
            $data['nama_habitat'],
            $data['deskripsi'] ?? null,
            $id
        ]);
        return $stmt->rowCount() >= 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->query("DELETE FROM habitats WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }
}


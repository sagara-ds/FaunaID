<?php
/**
 * Category Model
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class CategoryModel extends Model {
    public function getAll(): array {
        $sql = "SELECT c.*, COUNT(f.id) AS total_fauna 
                FROM categories c 
                LEFT JOIN fauna f ON c.id = f.kategori_id 
                GROUP BY c.id 
                ORDER BY c.nama_kategori ASC";
        return $this->fetchAll($sql);
    }

    public function getById(int $id): ?array {
        return $this->fetch("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public function getBySlug(string $slug): ?array {
        return $this->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO categories (nama_kategori, slug, deskripsi, ikon) VALUES (?, ?, ?, ?)";
        $this->query($sql, [
            $data['nama_kategori'],
            $data['slug'],
            $data['deskripsi'] ?? null,
            $data['ikon'] ?? 'paw'
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE categories SET nama_kategori = ?, slug = ?, deskripsi = ?, ikon = ? WHERE id = ?";
        $stmt = $this->query($sql, [
            $data['nama_kategori'],
            $data['slug'],
            $data['deskripsi'] ?? null,
            $data['ikon'] ?? 'paw',
            $id
        ]);
        return $stmt->rowCount() >= 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->query("DELETE FROM categories WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }
}


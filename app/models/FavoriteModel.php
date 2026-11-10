<?php
/**
 * Favorite Model
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class FavoriteModel extends Model {
    /**
     * Mengambil seluruh fauna yang difavoritkan oleh user
     */
    public function getUserFavorites(int $userId): array {
        $sql = "SELECT f.*, 
                       c.nama_kategori,
                       h.nama_habitat,
                       fav.created_at AS favorited_at,
                       (SELECT fi.image_url FROM fauna_images fi WHERE fi.fauna_id = f.id ORDER BY fi.is_primary DESC, fi.id ASC LIMIT 1) AS primary_image
                FROM favorites fav
                JOIN fauna f ON fav.fauna_id = f.id
                LEFT JOIN categories c ON f.kategori_id = c.id
                LEFT JOIN habitats h ON f.habitat_id = h.id
                WHERE fav.user_id = ?
                ORDER BY fav.created_at DESC";
        return $this->fetchAll($sql, [$userId]);
    }

    /**
     * Cek status apakah fauna sudah difavoritkan oleh user
     */
    public function isFavorite(int $userId, int $faunaId): bool {
        $sql = "SELECT id FROM favorites WHERE user_id = ? AND fauna_id = ?";
        return (bool)$this->fetchColumn($sql, [$userId, $faunaId]);
    }

    /**
     * Tambah fauna ke favorit
     */
    public function add(int $userId, int $faunaId): bool {
        if ($this->isFavorite($userId, $faunaId)) {
            return true;
        }
        $sql = "INSERT INTO favorites (user_id, fauna_id) VALUES (?, ?)";
        $stmt = $this->query($sql, [$userId, $faunaId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Hapus fauna dari favorit
     */
    public function remove(int $userId, int $faunaId): bool {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND fauna_id = ?";
        $stmt = $this->query($sql, [$userId, $faunaId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Toggle status favorit
     */
    public function toggle(int $userId, int $faunaId): array {
        if ($this->isFavorite($userId, $faunaId)) {
            $this->remove($userId, $faunaId);
            return ['status' => 'removed', 'is_favorite' => false];
        } else {
            $this->add($userId, $faunaId);
            return ['status' => 'added', 'is_favorite' => true];
        }
    }

    /**
     * Hitung total fauna favorit user
     */
    public function countUserFavorites(int $userId): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM favorites WHERE user_id = ?", [$userId]);
    }
}


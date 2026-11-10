<?php
/**
 * Fauna Model
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class FaunaModel extends Model {
    /**
     * Mengambil daftar fauna dengan filter lengkap & pagination
     */
    public function getAll(array $filters = [], int $limit = 0, int $offset = 0): array {
        $params = [];
        $where = ["1=1"];

        if (!empty($filters['search'])) {
            $where[] = "(f.nama_lokal LIKE ? OR f.nama_ilmiah LIKE ? OR f.deskripsi LIKE ?)";
            $keyword = '%' . $filters['search'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $where[] = "f.kategori_id = ?";
                $params[] = (int)$filters['category'];
            } else {
                $where[] = "c.slug = ?";
                $params[] = $filters['category'];
            }
        }

        if (!empty($filters['habitat'])) {
            $where[] = "f.habitat_id = ?";
            $params[] = (int)$filters['habitat'];
        }

        if (!empty($filters['status'])) {
            $where[] = "f.status_konservasi = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['region'])) {
            $where[] = "EXISTS (SELECT 1 FROM fauna_regions fr2 WHERE fr2.fauna_id = f.id AND fr2.region_id = ?)";
            $params[] = (int)$filters['region'];
        }

        if (!empty($filters['zona'])) {
            $where[] = "EXISTS (SELECT 1 FROM fauna_regions fr3 JOIN regions r3 ON fr3.region_id = r3.id WHERE fr3.fauna_id = f.id AND r3.zona = ?)";
            $params[] = $filters['zona'];
        }

        $whereClause = implode(' AND ', $where);

        $sort = "f.created_at DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'name_asc':  $sort = "f.nama_lokal ASC"; break;
                case 'name_desc': $sort = "f.nama_lokal DESC"; break;
                case 'oldest':    $sort = "f.created_at ASC"; break;
                default:          $sort = "f.created_at DESC"; break;
            }
        }

        $sql = "SELECT f.*, 
                       c.nama_kategori, c.slug AS kategori_slug,
                       h.nama_habitat,
                       (SELECT fi.image_url FROM fauna_images fi WHERE fi.fauna_id = f.id ORDER BY fi.is_primary DESC, fi.id ASC LIMIT 1) AS primary_image
                FROM fauna f
                LEFT JOIN categories c ON f.kategori_id = c.id
                LEFT JOIN habitats h ON f.habitat_id = h.id
                WHERE {$whereClause}
                ORDER BY {$sort}";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return $this->fetchAll($sql, $params);
    }

    /**
     * Hitung total fauna sesuai filter (untuk pagination)
     */
    public function countAll(array $filters = []): int {
        $params = [];
        $where = ["1=1"];

        if (!empty($filters['search'])) {
            $where[] = "(f.nama_lokal LIKE ? OR f.nama_ilmiah LIKE ? OR f.deskripsi LIKE ?)";
            $keyword = '%' . $filters['search'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $where[] = "f.kategori_id = ?";
                $params[] = (int)$filters['category'];
            } else {
                $where[] = "c.slug = ?";
                $params[] = $filters['category'];
            }
        }

        if (!empty($filters['habitat'])) {
            $where[] = "f.habitat_id = ?";
            $params[] = (int)$filters['habitat'];
        }

        if (!empty($filters['status'])) {
            $where[] = "f.status_konservasi = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['region'])) {
            $where[] = "EXISTS (SELECT 1 FROM fauna_regions fr2 WHERE fr2.fauna_id = f.id AND fr2.region_id = ?)";
            $params[] = (int)$filters['region'];
        }

        if (!empty($filters['zona'])) {
            $where[] = "EXISTS (SELECT 1 FROM fauna_regions fr3 JOIN regions r3 ON fr3.region_id = r3.id WHERE fr3.fauna_id = f.id AND r3.zona = ?)";
            $params[] = $filters['zona'];
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT COUNT(DISTINCT f.id) 
                FROM fauna f
                LEFT JOIN categories c ON f.kategori_id = c.id
                WHERE {$whereClause}";

        return (int)$this->fetchColumn($sql, $params);
    }

    /**
     * Mengambil detail fauna berdasarkan ID
     */
    public function getById(int $id): ?array {
        $sql = "SELECT f.*, 
                       c.nama_kategori, c.slug AS kategori_slug,
                       h.nama_habitat,
                       (SELECT fi.image_url FROM fauna_images fi WHERE fi.fauna_id = f.id ORDER BY fi.is_primary DESC, fi.id ASC LIMIT 1) AS primary_image
                FROM fauna f
                LEFT JOIN categories c ON f.kategori_id = c.id
                LEFT JOIN habitats h ON f.habitat_id = h.id
                WHERE f.id = ?";
        return $this->fetch($sql, [$id]);
    }

    /**
     * Mengambil daftar gambar untuk fauna tertentu
     */
    public function getImages(int $faunaId): array {
        return $this->fetchAll("SELECT * FROM fauna_images WHERE fauna_id = ? ORDER BY is_primary DESC, id ASC", [$faunaId]);
    }

    /**
     * Mengambil daftar wilayah persebaran fauna
     */
    public function getRegions(int $faunaId): array {
        $sql = "SELECT r.* 
                FROM regions r
                JOIN fauna_regions fr ON r.id = fr.region_id
                WHERE fr.fauna_id = ?
                ORDER BY r.zona ASC, r.nama_wilayah ASC";
        return $this->fetchAll($sql, [$faunaId]);
    }

    /**
     * Mengambil daftar ID wilayah saja (untuk form edit checkbox)
     */
    public function getRegionIds(int $faunaId): array {
        $stmt = $this->query("SELECT region_id FROM fauna_regions WHERE fauna_id = ?", [$faunaId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Mengambil fauna unggulan / populer (featured)
     */
    public function getFeatured(int $limit = 4): array {
        $sql = "SELECT f.*, 
                       c.nama_kategori,
                       h.nama_habitat,
                       (SELECT fi.image_url FROM fauna_images fi WHERE fi.fauna_id = f.id ORDER BY fi.is_primary DESC, fi.id ASC LIMIT 1) AS primary_image
                FROM fauna f
                LEFT JOIN categories c ON f.kategori_id = c.id
                LEFT JOIN habitats h ON f.habitat_id = h.id
                WHERE f.is_featured = 1
                ORDER BY f.id ASC
                LIMIT {$limit}";
        return $this->fetchAll($sql);
    }

    /**
     * Mengambil fauna berdasarkan zona biogeografi (Asiatis, Peralihan, Australis)
     */
    public function getByZone(string $zona, int $limit = 12): array {
        $sql = "SELECT DISTINCT f.*, 
                       c.nama_kategori,
                       h.nama_habitat,
                       (SELECT fi.image_url FROM fauna_images fi WHERE fi.fauna_id = f.id ORDER BY fi.is_primary DESC, fi.id ASC LIMIT 1) AS primary_image
                FROM fauna f
                JOIN fauna_regions fr ON f.id = fr.fauna_id
                JOIN regions r ON fr.region_id = r.id
                LEFT JOIN categories c ON f.kategori_id = c.id
                LEFT JOIN habitats h ON f.habitat_id = h.id
                WHERE r.zona = ?
                ORDER BY f.nama_lokal ASC
                LIMIT {$limit}";
        return $this->fetchAll($sql, [$zona]);
    }

    /**
     * Mengambil fauna terkait (spesies serupa dalam kategori sama)
     */
    public function getRelated(int $currentId, int $kategoriId, int $limit = 3): array {
        $sql = "SELECT f.*, 
                       c.nama_kategori,
                       h.nama_habitat,
                       (SELECT fi.image_url FROM fauna_images fi WHERE fi.fauna_id = f.id ORDER BY fi.is_primary DESC, fi.id ASC LIMIT 1) AS primary_image
                FROM fauna f
                LEFT JOIN categories c ON f.kategori_id = c.id
                LEFT JOIN habitats h ON f.habitat_id = h.id
                WHERE f.kategori_id = ? AND f.id != ?
                ORDER BY f.created_at DESC
                LIMIT {$limit}";
        return $this->fetchAll($sql, [$kategoriId, $currentId]);
    }

    /**
     * Tambah fauna baru
     */
    public function create(array $data): int {
        $sql = "INSERT INTO fauna (nama_lokal, nama_ilmiah, kategori_id, habitat_id, status_konservasi, deskripsi, karakteristik, makanan, populasi_estimasi, is_featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->query($sql, [
            $data['nama_lokal'],
            $data['nama_ilmiah'],
            $data['kategori_id'],
            $data['habitat_id'],
            $data['status_konservasi'],
            $data['deskripsi'],
            $data['karakteristik'] ?? null,
            $data['makanan'] ?? null,
            $data['populasi_estimasi'] ?? null,
            !empty($data['is_featured']) ? 1 : 0
        ]);
        return (int)$this->lastInsertId();
    }

    /**
     * Perbarui data fauna
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE fauna SET 
                    nama_lokal = ?, 
                    nama_ilmiah = ?, 
                    kategori_id = ?, 
                    habitat_id = ?, 
                    status_konservasi = ?, 
                    deskripsi = ?, 
                    karakteristik = ?, 
                    makanan = ?, 
                    populasi_estimasi = ?, 
                    is_featured = ?
                WHERE id = ?";
        $stmt = $this->query($sql, [
            $data['nama_lokal'],
            $data['nama_ilmiah'],
            $data['kategori_id'],
            $data['habitat_id'],
            $data['status_konservasi'],
            $data['deskripsi'],
            $data['karakteristik'] ?? null,
            $data['makanan'] ?? null,
            $data['populasi_estimasi'] ?? null,
            !empty($data['is_featured']) ? 1 : 0,
            $id
        ]);
        return $stmt->rowCount() >= 0;
    }

    /**
     * Hapus fauna
     */
    public function delete(int $id): bool {
        $stmt = $this->query("DELETE FROM fauna WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Sinkronisasi relasi Many-to-Many fauna_regions
     */
    public function syncRegions(int $faunaId, array $regionIds): void {
        $this->query("DELETE FROM fauna_regions WHERE fauna_id = ?", [$faunaId]);
        if (!empty($regionIds)) {
            $insertSql = "INSERT INTO fauna_regions (fauna_id, region_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($insertSql);
            foreach ($regionIds as $rid) {
                if (!empty($rid)) {
                    $stmt->execute([$faunaId, (int)$rid]);
                }
            }
        }
    }

    /**
     * Simpan gambar fauna
     */
    public function saveImage(int $faunaId, string $imageUrl, string $caption = '', bool $isPrimary = false): int {
        if ($isPrimary) {
            $this->query("UPDATE fauna_images SET is_primary = 0 WHERE fauna_id = ?", [$faunaId]);
        }
        $sql = "INSERT INTO fauna_images (fauna_id, image_url, caption, is_primary) VALUES (?, ?, ?, ?)";
        $this->query($sql, [$faunaId, $imageUrl, $caption, $isPrimary ? 1 : 0]);
        return (int)$this->lastInsertId();
    }
}


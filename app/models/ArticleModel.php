<?php
/**
 * Article Model (Artikel Edukasi)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class ArticleModel extends Model {
    public function getAll(string $status = 'published', int $limit = 0, int $offset = 0, string $search = ''): array {
        $where = ["1=1"];
        $params = [];

        if (!empty($status)) {
            $where[] = "a.status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $where[] = "(a.judul LIKE ? OR a.ringkasan LIKE ? OR a.konten LIKE ?)";
            $keyword = '%' . $search . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT a.*, u.nama AS author_name 
                FROM articles a 
                JOIN users u ON a.user_id = u.id 
                WHERE {$whereClause}
                ORDER BY a.created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return $this->fetchAll($sql, $params);
    }

    public function countAll(string $status = '', string $search = ''): int {
        $where = ["1=1"];
        $params = [];

        if (!empty($status)) {
            $where[] = "status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $where[] = "(judul LIKE ? OR ringkasan LIKE ? OR konten LIKE ?)";
            $keyword = '%' . $search . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $whereClause = implode(' AND ', $where);
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM articles WHERE {$whereClause}", $params);
    }

    public function getBySlug(string $slug): ?array {
        $sql = "SELECT a.*, u.nama AS author_name, u.foto_profil AS author_avatar
                FROM articles a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.slug = ?";
        return $this->fetch($sql, [$slug]);
    }

    public function getById(int $id): ?array {
        return $this->fetch("SELECT * FROM articles WHERE id = ?", [$id]);
    }

    public function getLatest(int $limit = 3): array {
        $sql = "SELECT a.*, u.nama AS author_name 
                FROM articles a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.status = 'published'
                ORDER BY a.created_at DESC 
                LIMIT {$limit}";
        return $this->fetchAll($sql);
    }

    public function incrementViews(int $id): void {
        $this->query("UPDATE articles SET views = views + 1 WHERE id = ?", [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO articles (user_id, judul, slug, ringkasan, konten, thumbnail, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->query($sql, [
            $data['user_id'],
            $data['judul'],
            $data['slug'],
            $data['ringkasan'] ?? null,
            $data['konten'],
            $data['thumbnail'] ?? null,
            $data['status'] ?? 'published'
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE articles SET 
                    judul = ?, 
                    slug = ?, 
                    ringkasan = ?, 
                    konten = ?, 
                    thumbnail = COALESCE(?, thumbnail), 
                    status = ? 
                WHERE id = ?";
        $stmt = $this->query($sql, [
            $data['judul'],
            $data['slug'],
            $data['ringkasan'] ?? null,
            $data['konten'],
            $data['thumbnail'] ?? null,
            $data['status'] ?? 'published',
            $id
        ]);
        return $stmt->rowCount() >= 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->query("DELETE FROM articles WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }
}


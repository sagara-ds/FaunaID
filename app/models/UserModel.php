<?php
/**
 * User Model
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class UserModel extends Model {
    public function getById(int $id): ?array {
        return $this->fetch("SELECT id, nama, email, role, foto_profil, created_at FROM users WHERE id = ?", [$id]);
    }

    public function getByEmail(string $email): ?array {
        return $this->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function getAll(): array {
        return $this->fetchAll("SELECT id, nama, email, role, foto_profil, created_at FROM users ORDER BY created_at DESC");
    }

    public function create(array $data): int {
        $sql = "INSERT INTO users (nama, email, password, role, foto_profil) VALUES (?, ?, ?, ?, ?)";
        $this->query($sql, [
            $data['nama'],
            $data['email'],
            $data['password'],
            $data['role'] ?? 'user',
            $data['foto_profil'] ?? null
        ]);
        return (int)$this->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        $fields = ["nama = ?"];
        $params = [$data['nama']];

        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }

        if (!empty($data['foto_profil'])) {
            $fields[] = "foto_profil = ?";
            $params[] = $data['foto_profil'];
        }

        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() >= 0;
    }

    public function updatePassword(int $id, string $hashedPassword): bool {
        $stmt = $this->query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $id]);
        return $stmt->rowCount() > 0;
    }

    public function updateRole(int $id, string $role): bool {
        $stmt = $this->query("UPDATE users SET role = ? WHERE id = ?", [$role, $id]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->query("DELETE FROM users WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }
}


<?php
/**
 * Region Model (Zona Persebaran Biogeografi)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Model.php';

class RegionModel extends Model {
    public function getAll(): array {
        $sql = "SELECT r.*, COUNT(fr.fauna_id) AS total_fauna 
                FROM regions r 
                LEFT JOIN fauna_regions fr ON r.id = fr.region_id 
                GROUP BY r.id 
                ORDER BY r.zona ASC, r.nama_wilayah ASC";
        return $this->fetchAll($sql);
    }

    public function getById(int $id): ?array {
        return $this->fetch("SELECT * FROM regions WHERE id = ?", [$id]);
    }

    public function getByZone(string $zona): array {
        return $this->fetchAll("SELECT * FROM regions WHERE zona = ? ORDER BY nama_wilayah ASC", [$zona]);
    }

    public function getZonesSummary(): array {
        $sql = "SELECT r.zona, COUNT(DISTINCT fr.fauna_id) AS total_spesies, COUNT(DISTINCT r.id) AS total_wilayah 
                FROM regions r 
                LEFT JOIN fauna_regions fr ON r.id = fr.region_id 
                GROUP BY r.zona";
        return $this->fetchAll($sql);
    }
}


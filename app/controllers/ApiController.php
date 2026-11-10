<?php
/**
 * API Controller (AJAX Endpoints)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class ApiController extends Controller {
    /**
     * Endpoint toggle favorite fauna
     */
    public function favorite(): void {
        if (!isLoggedIn()) {
            $this->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk menyimpan fauna favorit.',
                'need_login' => true
            ], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $faunaId = (int)($input['fauna_id'] ?? ($_POST['fauna_id'] ?? 0));

        if (!$faunaId) {
            $this->json(['success' => false, 'message' => 'ID Fauna tidak valid.'], 400);
        }

        $favoriteModel = $this->model('FavoriteModel');
        $result = $favoriteModel->toggle((int)auth()['id'], $faunaId);

        $this->json([
            'success'     => true,
            'is_favorite' => $result['is_favorite'],
            'status'      => $result['status'],
            'message'     => $result['is_favorite'] ? 'Berhasil ditambahkan ke favorit!' : 'Dihapus dari favorit.'
        ]);
    }

    /**
     * Endpoint data fauna berdasarkan zona (untuk interaksi klik peta)
     */
    public function faunaByZone(): void {
        $zona = trim($_GET['zona'] ?? 'Asiatis');
        $faunaModel = $this->model('FaunaModel');
        $faunaList = $faunaModel->getByZone($zona, 12);

        $formatted = array_map(function($f) {
            return [
                'id'                => $f['id'],
                'nama_lokal'        => $f['nama_lokal'],
                'nama_ilmiah'       => $f['nama_ilmiah'],
                'kategori'          => $f['nama_kategori'],
                'status_konservasi' => $f['status_konservasi'],
                'badge_class'       => getStatusBadgeClass($f['status_konservasi']),
                'primary_image'     => uploadUrl($f['primary_image']),
                'url'               => url('fauna/detail/' . $f['id'])
            ];
        }, $faunaList);

        $this->json([
            'success' => true,
            'zona'    => $zona,
            'total'   => count($formatted),
            'data'    => $formatted
        ]);
    }

    /**
     * Endpoint live search autocomplete
     */
    public function search(): void {
        $keyword = trim($_GET['q'] ?? '');
        if (strlen($keyword) < 2) {
            $this->json(['success' => true, 'data' => []]);
        }

        $faunaModel = $this->model('FaunaModel');
        $results = $faunaModel->getAll(['search' => $keyword], 6);

        $data = array_map(function($f) {
            return [
                'id'            => $f['id'],
                'nama_lokal'    => $f['nama_lokal'],
                'nama_ilmiah'   => $f['nama_ilmiah'],
                'kategori'      => $f['nama_kategori'],
                'primary_image' => uploadUrl($f['primary_image']),
                'url'           => url('fauna/detail/' . $f['id'])
            ];
        }, $results);

        $this->json(['success' => true, 'data' => $data]);
    }
}


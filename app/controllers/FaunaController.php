<?php
/**
 * Fauna Controller (Katalog & Detail Fauna)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class FaunaController extends Controller {
    public function index(): void {
        $faunaModel = $this->model('FaunaModel');
        $categoryModel = $this->model('CategoryModel');
        $habitatModel = $this->model('HabitatModel');
        $regionModel = $this->model('RegionModel');

        // Filter parameter
        $filters = [
            'search'   => trim($_GET['q'] ?? ''),
            'category' => trim($_GET['kategori'] ?? ''),
            'habitat'  => trim($_GET['habitat'] ?? ''),
            'status'   => trim($_GET['status'] ?? ''),
            'zona'     => trim($_GET['zona'] ?? ''),
            'sort'     => trim($_GET['sort'] ?? 'latest')
        ];

        // Pagination
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 9;
        $totalItems = $faunaModel->countAll($filters);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $offset = ($page - 1) * $perPage;

        $faunaList = $faunaModel->getAll($filters, $perPage, $offset);

        $data = [
            'title' => 'Katalog Fauna Indonesia — FaunaID',
            'faunaList' => $faunaList,
            'categories' => $categoryModel->getAll(),
            'habitats' => $habitatModel->getAll(),
            'regions' => $regionModel->getAll(),
            'filters' => $filters,
            'pagination' => [
                'currentPage' => $page,
                'totalPages'  => $totalPages,
                'totalItems'  => $totalItems,
                'perPage'     => $perPage
            ]
        ];

        $this->view('catalog.index', $data);
    }

    public function detail($id = null): void {
        if (!$id || !is_numeric($id)) {
            redirect('fauna');
        }

        $faunaModel = $this->model('FaunaModel');
        $fauna = $faunaModel->getById((int)$id);

        if (!$fauna) {
            setFlash('error', 'Data fauna tidak ditemukan.');
            redirect('fauna');
        }

        $images = $faunaModel->getImages((int)$id);
        $regions = $faunaModel->getRegions((int)$id);
        $related = $faunaModel->getRelated((int)$id, (int)$fauna['kategori_id'], 3);

        $isFavorite = false;
        if (isLoggedIn()) {
            $favoriteModel = $this->model('FavoriteModel');
            $isFavorite = $favoriteModel->isFavorite((int)auth()['id'], (int)$id);
        }

        $data = [
            'title' => e($fauna['nama_lokal']) . ' (' . e($fauna['nama_ilmiah']) . ') — FaunaID',
            'fauna' => $fauna,
            'images' => $images,
            'regions' => $regions,
            'related' => $related,
            'isFavorite' => $isFavorite
        ];

        $this->view('catalog.detail', $data);
    }
}


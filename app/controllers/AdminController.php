<?php
/**
 * Admin Controller (Content Management System)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class AdminController extends Controller {
    public function __construct() {
        // Seluruh method dalam controller ini wajib hak akses Admin
        $this->requireAdmin();
    }

    /**
     * Dashboard Utama Admin
     */
    public function index(): void {
        $faunaModel = $this->model('FaunaModel');
        $categoryModel = $this->model('CategoryModel');
        $habitatModel = $this->model('HabitatModel');
        $articleModel = $this->model('ArticleModel');
        $userModel = $this->model('UserModel');

        $data = [
            'title' => 'Dashboard Administrasi — FaunaID',
            'stats' => [
                'total_fauna'      => $faunaModel->countAll(),
                'total_categories' => count($categoryModel->getAll()),
                'total_habitats'   => count($habitatModel->getAll()),
                'total_articles'   => $articleModel->countAll(),
                'total_users'      => count($userModel->getAll()),
            ],
            'recentFauna'    => $faunaModel->getAll([], 5),
            'recentArticles' => $articleModel->getAll('', 5),
            'recentUsers'    => array_slice($userModel->getAll(), 0, 5)
        ];

        $this->view('admin.dashboard', $data);
    }

    // ==========================================
    // MANAJEMEN FAUNA (CRUD)
    // ==========================================

    public function fauna(): void {
        $faunaModel = $this->model('FaunaModel');
        $categoryModel = $this->model('CategoryModel');

        $search = trim($_GET['q'] ?? '');
        $categoryId = trim($_GET['category'] ?? '');

        $filters = [
            'search'   => $search,
            'category' => $categoryId
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $totalItems = $faunaModel->countAll($filters);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $offset = ($page - 1) * $perPage;

        $faunaList = $faunaModel->getAll($filters, $perPage, $offset);

        $data = [
            'title' => 'Kelola Data Fauna — Admin FaunaID',
            'faunaList' => $faunaList,
            'categories' => $categoryModel->getAll(),
            'search' => $search,
            'categoryId' => $categoryId,
            'pagination' => [
                'currentPage' => $page,
                'totalPages'  => $totalPages,
                'totalItems'  => $totalItems,
                'perPage'     => $perPage
            ]
        ];

        $this->view('admin.fauna.index', $data);
    }

    public function faunaCreate(): void {
        $categoryModel = $this->model('CategoryModel');
        $habitatModel = $this->model('HabitatModel');
        $regionModel = $this->model('RegionModel');

        $data = [
            'title' => 'Tambah Spesies Fauna Baru — Admin FaunaID',
            'categories' => $categoryModel->getAll(),
            'habitats'   => $habitatModel->getAll(),
            'regions'    => $regionModel->getAll(),
            'fauna'      => null,
            'isEdit'     => false
        ];

        $this->view('admin.fauna.form', $data);
    }

    public function faunaStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/fauna');
        }

        if (!verifyCsrf()) {
            setFlash('error', 'Token keamanan tidak valid.');
            redirect('admin/fauna-create');
        }

        $namaLokal = trim($_POST['nama_lokal'] ?? '');
        $namaIlmiah = trim($_POST['nama_ilmiah'] ?? '');
        $kategoriId = (int)($_POST['kategori_id'] ?? 0);
        $habitatId = (int)($_POST['habitat_id'] ?? 0);
        $statusKonservasi = trim($_POST['status_konservasi'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $karakteristik = trim($_POST['karakteristik'] ?? '');
        $makanan = trim($_POST['makanan'] ?? '');
        $populasi = trim($_POST['populasi_estimasi'] ?? '');
        $isFeatured = !empty($_POST['is_featured']) ? 1 : 0;
        $regionIds = $_POST['region_ids'] ?? [];

        if (empty($namaLokal) || empty($namaIlmiah) || !$kategoriId || !$habitatId || empty($statusKonservasi) || empty($deskripsi)) {
            setFlash('error', 'Semua kolom bertanda bintang (*) wajib diisi.');
            redirect('admin/fauna-create');
        }

        $faunaModel = $this->model('FaunaModel');
        $faunaId = $faunaModel->create([
            'nama_lokal'        => $namaLokal,
            'nama_ilmiah'       => $namaIlmiah,
            'kategori_id'       => $kategoriId,
            'habitat_id'        => $habitatId,
            'status_konservasi' => $statusKonservasi,
            'deskripsi'         => $deskripsi,
            'karakteristik'     => $karakteristik,
            'makanan'           => $makanan,
            'populasi_estimasi' => $populasi,
            'is_featured'       => $isFeatured
        ]);

        // Simpan relasi wilayah persebaran
        if (!empty($regionIds) && is_array($regionIds)) {
            $faunaModel->syncRegions($faunaId, $regionIds);
        }

        // Simpan gambar: prioritas upload file lokal, jika kosong gunakan URL gambar
        $imageUrl = '';
        if (!empty($_FILES['image_file']['name'])) {
            $uploaded = $this->handleImageUpload($_FILES['image_file']);
            if ($uploaded) {
                $imageUrl = $uploaded;
            }
        }

        if (empty($imageUrl) && !empty($_POST['image_url'])) {
            $imageUrl = trim($_POST['image_url']);
        }

        if (empty($imageUrl)) {
            $imageUrl = 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1200&q=80';
        }

        $faunaModel->saveImage($faunaId, $imageUrl, $namaLokal, true);

        setFlash('success', "Spesies fauna '{$namaLokal}' berhasil ditambahkan!");
        redirect('admin/fauna');
    }

    public function faunaEdit($id = null): void {
        if (!$id || !is_numeric($id)) {
            redirect('admin/fauna');
        }

        $faunaModel = $this->model('FaunaModel');
        $categoryModel = $this->model('CategoryModel');
        $habitatModel = $this->model('HabitatModel');
        $regionModel = $this->model('RegionModel');

        $fauna = $faunaModel->getById((int)$id);
        if (!$fauna) {
            setFlash('error', 'Data fauna tidak ditemukan.');
            redirect('admin/fauna');
        }

        $data = [
            'title' => 'Edit Spesies: ' . e($fauna['nama_lokal']) . ' — Admin FaunaID',
            'fauna' => $fauna,
            'categories' => $categoryModel->getAll(),
            'habitats'   => $habitatModel->getAll(),
            'regions'    => $regionModel->getAll(),
            'selectedRegions' => $faunaModel->getRegionIds((int)$id),
            'isEdit'     => true
        ];

        $this->view('admin.fauna.form', $data);
    }

    public function faunaUpdate($id = null): void {
        if (!$id || !is_numeric($id) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/fauna');
        }

        if (!verifyCsrf()) {
            setFlash('error', 'Token keamanan tidak valid.');
            redirect('admin/fauna-edit/' . $id);
        }

        $namaLokal = trim($_POST['nama_lokal'] ?? '');
        $namaIlmiah = trim($_POST['nama_ilmiah'] ?? '');
        $kategoriId = (int)($_POST['kategori_id'] ?? 0);
        $habitatId = (int)($_POST['habitat_id'] ?? 0);
        $statusKonservasi = trim($_POST['status_konservasi'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $karakteristik = trim($_POST['karakteristik'] ?? '');
        $makanan = trim($_POST['makanan'] ?? '');
        $populasi = trim($_POST['populasi_estimasi'] ?? '');
        $isFeatured = !empty($_POST['is_featured']) ? 1 : 0;
        $regionIds = $_POST['region_ids'] ?? [];

        $faunaModel = $this->model('FaunaModel');
        $faunaModel->update((int)$id, [
            'nama_lokal'        => $namaLokal,
            'nama_ilmiah'       => $namaIlmiah,
            'kategori_id'       => $kategoriId,
            'habitat_id'        => $habitatId,
            'status_konservasi' => $statusKonservasi,
            'deskripsi'         => $deskripsi,
            'karakteristik'     => $karakteristik,
            'makanan'           => $makanan,
            'populasi_estimasi' => $populasi,
            'is_featured'       => $isFeatured
        ]);

        $faunaModel->syncRegions((int)$id, $regionIds);

        // Upload gambar baru jika ada
        if (!empty($_FILES['image_file']['name'])) {
            $uploaded = $this->handleImageUpload($_FILES['image_file']);
            if ($uploaded) {
                $faunaModel->saveImage((int)$id, $uploaded, $namaLokal, true);
            }
        } elseif (!empty($_POST['image_url'])) {
            $faunaModel->saveImage((int)$id, trim($_POST['image_url']), $namaLokal, true);
        }

        setFlash('success', "Data fauna '{$namaLokal}' berhasil diperbarui!");
        redirect('admin/fauna');
    }

    public function faunaDelete($id = null): void {
        if ($id && is_numeric($id)) {
            $faunaModel = $this->model('FaunaModel');
            $fauna = $faunaModel->getById((int)$id);
            if ($fauna) {
                $faunaModel->delete((int)$id);
                setFlash('success', "Spesies '{$fauna['nama_lokal']}' berhasil dihapus.");
            }
        }
        redirect('admin/fauna');
    }

    // ==========================================
    // MANAJEMEN KATEGORI & HABITAT
    // ==========================================

    public function categories(): void {
        $categoryModel = $this->model('CategoryModel');
        $data = [
            'title' => 'Kelola Kategori Fauna — Admin FaunaID',
            'categories' => $categoryModel->getAll()
        ];
        $this->view('admin.categories.index', $data);
    }

    public function categoryStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf()) {
            redirect('admin/categories');
        }

        $nama = trim($_POST['nama_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $ikon = trim($_POST['ikon'] ?? 'paw');

        if (!empty($nama)) {
            $categoryModel = $this->model('CategoryModel');
            $slug = slugify($nama);
            $categoryModel->create([
                'nama_kategori' => $nama,
                'slug'          => $slug,
                'deskripsi'     => $deskripsi,
                'ikon'          => $ikon
            ]);
            setFlash('success', "Kategori '{$nama}' berhasil ditambahkan!");
        }

        redirect('admin/categories');
    }

    public function categoryDelete($id = null): void {
        if ($id && is_numeric($id)) {
            $categoryModel = $this->model('CategoryModel');
            $categoryModel->delete((int)$id);
            setFlash('success', 'Kategori berhasil dihapus.');
        }
        redirect('admin/categories');
    }

    public function habitats(): void {
        $habitatModel = $this->model('HabitatModel');
        $data = [
            'title' => 'Kelola Tipe Habitat — Admin FaunaID',
            'habitats' => $habitatModel->getAll()
        ];
        $this->view('admin.habitats.index', $data);
    }

    public function habitatStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf()) {
            redirect('admin/habitats');
        }

        $nama = trim($_POST['nama_habitat'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if (!empty($nama)) {
            $habitatModel = $this->model('HabitatModel');
            $habitatModel->create([
                'nama_habitat' => $nama,
                'deskripsi'    => $deskripsi
            ]);
            setFlash('success', "Habitat '{$nama}' berhasil ditambahkan!");
        }

        redirect('admin/habitats');
    }

    public function habitatDelete($id = null): void {
        if ($id && is_numeric($id)) {
            $habitatModel = $this->model('HabitatModel');
            $habitatModel->delete((int)$id);
            setFlash('success', 'Habitat berhasil dihapus.');
        }
        redirect('admin/habitats');
    }

    // ==========================================
    // MANAJEMEN ARTIKEL (CRUD)
    // ==========================================

    public function articles(): void {
        $articleModel = $this->model('ArticleModel');
        $articles = $articleModel->getAll('', 0);

        $data = [
            'title' => 'Kelola Artikel Edukasi — Admin FaunaID',
            'articles' => $articles
        ];

        $this->view('admin.articles.index', $data);
    }

    public function articleCreate(): void {
        $data = [
            'title'   => 'Tulis Artikel Edukasi Baru — Admin FaunaID',
            'article' => null,
            'isEdit'  => false
        ];
        $this->view('admin.articles.form', $data);
    }

    public function articleStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf()) {
            redirect('admin/articles');
        }

        $judul = trim($_POST['judul'] ?? '');
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $konten = trim($_POST['konten'] ?? '');
        $status = $_POST['status'] ?? 'published';

        if (empty($judul) || empty($konten)) {
            setFlash('error', 'Judul dan isi konten artikel wajib diisi.');
            redirect('admin/article-create');
        }

        $thumbnail = '';
        if (!empty($_FILES['thumbnail_file']['name'])) {
            $uploaded = $this->handleImageUpload($_FILES['thumbnail_file']);
            if ($uploaded) {
                $thumbnail = $uploaded;
            }
        }

        if (empty($thumbnail) && !empty($_POST['thumbnail_url'])) {
            $thumbnail = trim($_POST['thumbnail_url']);
        }

        $articleModel = $this->model('ArticleModel');
        $slug = slugify($judul) . '-' . time();

        $articleModel->create([
            'user_id'   => (int)auth()['id'],
            'judul'     => $judul,
            'slug'      => $slug,
            'ringkasan' => $ringkasan,
            'konten'    => $konten,
            'thumbnail' => $thumbnail ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            'status'    => $status
        ]);

        setFlash('success', 'Artikel berhasil diterbitkan!');
        redirect('admin/articles');
    }

    public function articleEdit($id = null): void {
        if (!$id || !is_numeric($id)) {
            redirect('admin/articles');
        }

        $articleModel = $this->model('ArticleModel');
        $article = $articleModel->getById((int)$id);

        if (!$article) {
            setFlash('error', 'Artikel tidak ditemukan.');
            redirect('admin/articles');
        }

        $data = [
            'title'   => 'Edit Artikel: ' . e($article['judul']) . ' — Admin FaunaID',
            'article' => $article,
            'isEdit'  => true
        ];

        $this->view('admin.articles.form', $data);
    }

    public function articleUpdate($id = null): void {
        if (!$id || !is_numeric($id) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf()) {
            redirect('admin/articles');
        }

        $judul = trim($_POST['judul'] ?? '');
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $konten = trim($_POST['konten'] ?? '');
        $status = $_POST['status'] ?? 'published';

        $articleModel = $this->model('ArticleModel');
        $article = $articleModel->getById((int)$id);

        $thumbnail = null;
        if (!empty($_FILES['thumbnail_file']['name'])) {
            $uploaded = $this->handleImageUpload($_FILES['thumbnail_file']);
            if ($uploaded) {
                $thumbnail = $uploaded;
            }
        } elseif (!empty($_POST['thumbnail_url'])) {
            $thumbnail = trim($_POST['thumbnail_url']);
        }

        $slug = slugify($judul);
        $articleModel->update((int)$id, [
            'judul'     => $judul,
            'slug'      => $slug,
            'ringkasan' => $ringkasan,
            'konten'    => $konten,
            'thumbnail' => $thumbnail,
            'status'    => $status
        ]);

        setFlash('success', 'Artikel berhasil diperbarui!');
        redirect('admin/articles');
    }

    public function articleDelete($id = null): void {
        if ($id && is_numeric($id)) {
            $articleModel = $this->model('ArticleModel');
            $articleModel->delete((int)$id);
            setFlash('success', 'Artikel berhasil dihapus.');
        }
        redirect('admin/articles');
    }

    // ==========================================
    // MANAJEMEN PENGGUNA (USERS)
    // ==========================================

    public function users(): void {
        $userModel = $this->model('UserModel');
        $data = [
            'title' => 'Kelola Akun Pengguna — Admin FaunaID',
            'users' => $userModel->getAll()
        ];
        $this->view('admin.users.index', $data);
    }

    public function userChangeRole($id = null): void {
        if ($id && is_numeric($id)) {
            $userModel = $this->model('UserModel');
            $user = $userModel->getById((int)$id);

            if ($user) {
                if ((int)$user['id'] === (int)auth()['id']) {
                    setFlash('error', 'Anda tidak dapat mengubah hak akses akun Anda sendiri.');
                } else {
                    $newRole = $user['role'] === 'admin' ? 'user' : 'admin';
                    $userModel->updateRole((int)$id, $newRole);
                    setFlash('success', "Role pengguna '{$user['nama']}' berhasil diubah menjadi {$newRole}.");
                }
            }
        }
        redirect('admin/users');
    }

    public function userDelete($id = null): void {
        if ($id && is_numeric($id)) {
            $userModel = $this->model('UserModel');
            if ((int)$id === (int)auth()['id']) {
                setFlash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            } else {
                $userModel->delete((int)$id);
                setFlash('success', 'Akun pengguna berhasil dihapus.');
            }
        }
        redirect('admin/users');
    }
}


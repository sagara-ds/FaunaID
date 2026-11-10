<?php
/**
 * Article Controller (Artikel Edukasi)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class ArticleController extends Controller {
    public function index(): void {
        $articleModel = $this->model('ArticleModel');

        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 6;
        $totalItems = $articleModel->countAll('published', $search);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $offset = ($page - 1) * $perPage;

        $articles = $articleModel->getAll('published', $perPage, $offset, $search);

        $data = [
            'title' => 'Artikel & Berita Edukasi Konservasi — FaunaID',
            'articles' => $articles,
            'search' => $search,
            'pagination' => [
                'currentPage' => $page,
                'totalPages'  => $totalPages,
                'totalItems'  => $totalItems,
                'perPage'     => $perPage
            ]
        ];

        $this->view('articles.index', $data);
    }

    public function read($slug = null): void {
        if (!$slug) {
            redirect('articles');
        }

        $articleModel = $this->model('ArticleModel');
        $article = $articleModel->getBySlug($slug);

        if (!$article || $article['status'] !== 'published') {
            setFlash('error', 'Artikel tidak ditemukan.');
            redirect('articles');
        }

        // Tambah jumlah view
        $articleModel->incrementViews((int)$article['id']);

        $latestArticles = $articleModel->getLatest(4);

        $data = [
            'title' => e($article['judul']) . ' — FaunaID',
            'article' => $article,
            'latestArticles' => $latestArticles
        ];

        $this->view('articles.detail', $data);
    }
}


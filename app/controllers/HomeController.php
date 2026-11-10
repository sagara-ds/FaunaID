<?php
/**
 * Home Controller
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class HomeController extends Controller {
    public function index(): void {
        $faunaModel = $this->model('FaunaModel');
        $categoryModel = $this->model('CategoryModel');
        $regionModel = $this->model('RegionModel');
        $articleModel = $this->model('ArticleModel');

        $data = [
            'title' => 'FaunaID — Jelajahi Keanekaragaman Satwa Nusantara',
            'featuredFauna' => $faunaModel->getFeatured(6),
            'categories' => $categoryModel->getAll(),
            'zonesSummary' => $regionModel->getZonesSummary(),
            'latestArticles' => $articleModel->getLatest(3),
            'stats' => [
                'total_fauna' => $faunaModel->countAll(),
                'total_categories' => count($categoryModel->getAll()),
                'total_articles' => $articleModel->countAll('published'),
                'total_regions' => count($regionModel->getAll())
            ]
        ];

        $this->view('home.index', $data);
    }

    public function notFound(): void {
        $this->view('layouts.404', [
            'title' => '404 - Halaman Tidak Ditemukan'
        ]);
    }
}


<?php
/**
 * Base Controller
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

class Controller {
    /**
     * Memuat file view dan meneruskan data
     */
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewPath = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            die("View [{$view}] tidak ditemukan di {$viewPath}");
        }

        require_once $viewPath;
    }

    /**
     * Memuat dan menginisialisasi instance Model
     */
    protected function model(string $model): Model {
        $modelPath = APP_PATH . '/models/' . $model . '.php';

        if (!file_exists($modelPath)) {
            die("Model [{$model}] tidak ditemukan di {$modelPath}");
        }

        require_once $modelPath;
        return new $model();
    }

    /**
     * Mengembalikan response JSON (untuk AJAX/Fetch API)
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /**
     * Wajib login untuk mengakses method
     */
    protected function requireLogin(string $redirectPath = 'auth/login'): void {
        if (!isLoggedIn()) {
            setFlash('error', 'Silakan masuk ke akun Anda terlebih dahulu untuk melanjutkan.');
            redirect($redirectPath);
        }
    }

    /**
     * Wajib hak akses Administrator
     */
    protected function requireAdmin(string $redirectPath = ''): void {
        $this->requireLogin();
        if (!isAdmin()) {
            setFlash('error', 'Akses ditolak. Halaman ini hanya diperuntukkan bagi Administrator.');
            redirect($redirectPath);
        }
    }

    /**
     * Utility upload gambar yang aman
     */
    protected function handleImageUpload(array $file, int $maxSize = 5242880): ?string {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > $maxSize) {
            setFlash('error', 'Ukuran file melebihi batas maksimal 5MB.');
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif'
        ];

        if (!array_key_exists($mime, $allowed)) {
            setFlash('error', 'Format gambar harus berupa JPG, PNG, atau WebP.');
            return null;
        }

        $ext = $allowed[$mime];
        $filename = 'fauna_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $targetDir = UPLOAD_PATH;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $filename;
        }

        return null;
    }
}


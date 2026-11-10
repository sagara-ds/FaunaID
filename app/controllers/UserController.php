<?php
/**
 * User Controller (Profil Pengguna & Koleksi Favorit)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class UserController extends Controller {
    public function profile(): void {
        $this->requireLogin();

        $userModel = $this->model('UserModel');
        $favoriteModel = $this->model('FavoriteModel');
        $user = $userModel->getById((int)auth()['id']);

        $data = [
            'title' => 'Profil Saya — FaunaID',
            'user' => $user,
            'totalFavorites' => $favoriteModel->countUserFavorites((int)auth()['id'])
        ];

        $this->view('user.profile', $data);
    }

    public function updateProfile(): void {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/profile');
        }

        if (!verifyCsrf()) {
            setFlash('error', 'Token keamanan tidak valid.');
            redirect('user/profile');
        }

        $userId = (int)auth()['id'];
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passwordBaru = $_POST['new_password'] ?? '';

        if (empty($nama) || empty($email)) {
            setFlash('error', 'Nama dan email tidak boleh kosong.');
            redirect('user/profile');
        }

        $userModel = $this->model('UserModel');

        // Cek email ganda
        $existing = $userModel->getByEmail($email);
        if ($existing && (int)$existing['id'] !== $userId) {
            setFlash('error', 'Email tersebut sudah digunakan oleh akun lain.');
            redirect('user/profile');
        }

        $updateData = [
            'nama' => $nama,
            'email' => $email
        ];

        // Upload avatar jika ada
        if (!empty($_FILES['avatar']['name'])) {
            $avatarName = $this->handleImageUpload($_FILES['avatar']);
            if ($avatarName) {
                $updateData['foto_profil'] = $avatarName;
                $_SESSION['user']['foto_profil'] = $avatarName;
            }
        }

        $userModel->updateProfile($userId, $updateData);

        // Update password jika diisi
        if (!empty($passwordBaru)) {
            if (strlen($passwordBaru) < 6) {
                setFlash('error', 'Kata sandi baru minimal 6 karakter.');
                redirect('user/profile');
            }
            $userModel->updatePassword($userId, password_hash($passwordBaru, PASSWORD_BCRYPT));
        }

        $_SESSION['user']['nama'] = $nama;
        $_SESSION['user']['email'] = $email;

        setFlash('success', 'Profil Anda berhasil diperbarui!');
        redirect('user/profile');
    }

    public function favorites(): void {
        $this->requireLogin();

        $favoriteModel = $this->model('FavoriteModel');
        $favorites = $favoriteModel->getUserFavorites((int)auth()['id']);

        $data = [
            'title' => 'Fauna Favorit Saya — FaunaID',
            'favorites' => $favorites
        ];

        $this->view('user.favorites', $data);
    }

    public function removeFavorite($faunaId = null): void {
        $this->requireLogin();

        if ($faunaId && is_numeric($faunaId)) {
            $favoriteModel = $this->model('FavoriteModel');
            $favoriteModel->remove((int)auth()['id'], (int)$faunaId);
            setFlash('success', 'Fauna berhasil dihapus dari daftar favorit Anda.');
        }

        redirect('user/favorites');
    }
}


<?php
/**
 * Auth Controller (Login, Register & Logout)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class AuthController extends Controller {
    public function login(): void {
        if (isLoggedIn()) {
            redirect('');
        }

        $data = [
            'title' => 'Masuk ke Akun Anda — FaunaID'
        ];

        $this->view('auth.login', $data);
    }

    public function doLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth/login');
        }

        if (!verifyCsrf()) {
            setFlash('error', 'Token keamanan tidak valid. Silakan coba kembali.');
            redirect('auth/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan kata sandi wajib diisi.');
            redirect('auth/login');
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            setFlash('error', 'Kombinasi email atau kata sandi tidak sesuai.');
            redirect('auth/login');
        }

        // Simpan sesi user
        $_SESSION['user'] = [
            'id'          => $user['id'],
            'nama'        => $user['nama'],
            'email'       => $user['email'],
            'role'        => $user['role'],
            'foto_profil' => $user['foto_profil']
        ];

        setFlash('success', 'Selamat datang kembali, ' . $user['nama'] . '!');

        if ($user['role'] === 'admin') {
            redirect('admin');
        } else {
            redirect('');
        }
    }

    public function register(): void {
        if (isLoggedIn()) {
            redirect('');
        }

        $data = [
            'title' => 'Daftar Akun Baru — FaunaID'
        ];

        $this->view('auth.register', $data);
    }

    public function doRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth/register');
        }

        if (!verifyCsrf()) {
            setFlash('error', 'Token keamanan tidak valid.');
            redirect('auth/register');
        }

        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirmation'] ?? '';

        if (empty($nama) || empty($email) || empty($password)) {
            setFlash('error', 'Seluruh kolom pendaftaran wajib diisi.');
            redirect('auth/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Format alamat email tidak valid.');
            redirect('auth/register');
        }

        if (strlen($password) < 6) {
            setFlash('error', 'Kata sandi minimal harus terdiri dari 6 karakter.');
            redirect('auth/register');
        }

        if ($password !== $confirm) {
            setFlash('error', 'Konfirmasi kata sandi tidak cocok.');
            redirect('auth/register');
        }

        $userModel = $this->model('UserModel');

        if ($userModel->getByEmail($email)) {
            setFlash('error', 'Alamat email tersebut sudah terdaftar. Silakan login.');
            redirect('auth/register');
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $userId = $userModel->create([
            'nama'     => $nama,
            'email'    => $email,
            'password' => $hashed,
            'role'     => 'user'
        ]);

        $_SESSION['user'] = [
            'id'          => $userId,
            'nama'        => $nama,
            'email'       => $email,
            'role'        => 'user',
            'foto_profil' => null
        ];

        setFlash('success', 'Pendaftaran berhasil! Selamat datang di komunitas FaunaID.');
        redirect('');
    }

    public function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        session_start();
        setFlash('info', 'Anda telah berhasil keluar dari akun.');
        redirect('auth/login');
    }
}


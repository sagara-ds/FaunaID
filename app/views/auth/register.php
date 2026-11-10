<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="auth-section">
    <div class="container auth-container">
        <div class="auth-card">
            <div class="auth-card-header">
                <span class="auth-icon-badge"><i class="fa-solid fa-user-plus"></i></span>
                <h1 class="auth-title">Daftar Anggota Baru</h1>
                <p class="auth-subtitle">Bergabung bersama komunitas pelestari dan pecinta fauna Indonesia.</p>
            </div>

            <form action="<?= url('auth/doRegister') ?>" method="POST" class="auth-form">
                <?= csrfField() ?>

                <div class="form-group">
                    <label for="regNama" class="form-label">Nama Lengkap</label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" id="regNama" name="nama" class="form-control" placeholder="Nama Anda..." required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="regEmail" class="form-label">Alamat Email Aktif</label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="regEmail" name="email" class="form-control" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="regPassword" class="form-label">Kata Sandi</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="regPassword" name="password" class="form-control" placeholder="Minimal 6 karakter..." required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="regConfirm" class="form-label">Ulangi Kata Sandi</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-shield-check"></i>
                        <input type="password" id="regConfirm" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi..." required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">
                    <i class="fa-solid fa-user-check"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="auth-footer-text">
                Sudah memiliki akun? <a href="<?= url('auth/login') ?>">Masuk ke sini</a>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


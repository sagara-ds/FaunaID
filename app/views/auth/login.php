<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="auth-section">
    <div class="container auth-container">
        <div class="auth-card">
            <div class="auth-card-header">
                <span class="auth-icon-badge"><i class="fa-solid fa-seedling"></i></span>
                <h1 class="auth-title">Masuk ke FaunaID</h1>
                <p class="auth-subtitle">Kelola fauna favorit dan eksplorasi ensiklopedia nusantara.</p>
            </div>

            <!-- Demo Account Helper Box -->
            <div class="demo-account-box">
                <div class="demo-box-title"><i class="fa-solid fa-key"></i> Akun Demo Tersedia:</div>
                <div class="demo-credentials-grid">
                    <div class="demo-item" onclick="document.getElementById('loginEmail').value='admin@fauna.id'; document.getElementById('loginPass').value='admin123';">
                        <span class="demo-role">Admin:</span>
                        <code>admin@fauna.id</code> / <code>admin123</code>
                        <span class="demo-click-hint">(Klik untuk isi)</span>
                    </div>
                    <div class="demo-item" onclick="document.getElementById('loginEmail').value='budi@gmail.com'; document.getElementById('loginPass').value='user123';">
                        <span class="demo-role">User:</span>
                        <code>budi@gmail.com</code> / <code>user123</code>
                        <span class="demo-click-hint">(Klik untuk isi)</span>
                    </div>
                </div>
            </div>

            <form action="<?= url('auth/doLogin') ?>" method="POST" class="auth-form">
                <?= csrfField() ?>

                <div class="form-group">
                    <label for="loginEmail" class="form-label">Alamat Email</label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="loginEmail" name="email" class="form-control" placeholder="nama@email.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="label-flex">
                        <label for="loginPass" class="form-label">Kata Sandi</label>
                    </div>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="loginPass" name="password" class="form-control" placeholder="Masukkan kata sandi..." required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
                </button>
            </form>

            <div class="auth-footer-text">
                Belum memiliki akun? <a href="<?= url('auth/register') ?>">Daftar akun gratis</a>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


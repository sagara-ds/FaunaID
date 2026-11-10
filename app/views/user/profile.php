<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <span>Profil Pengguna</span>
        </div>
        <h1 class="page-title">Pengaturan Profil Akun</h1>
        <p class="page-desc">Perbarui identitas profil, foto avatar, dan keamanan akun Anda.</p>
    </div>
</section>

<section class="section profile-content-section">
    <div class="container profile-container">
        <div class="profile-grid">
            <!-- Sidebar summary -->
            <div class="profile-sidebar-card">
                <div class="profile-avatar-wrap">
                    <?php if (!empty($user['foto_profil'])): ?>
                        <img src="<?= uploadUrl($user['foto_profil']) ?>" alt="<?= e($user['nama']) ?>" class="profile-avatar-large">
                    <?php else: ?>
                        <div class="profile-avatar-large avatar-initial-large">
                            <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h3 class="profile-name"><?= e($user['nama']) ?></h3>
                <p class="profile-email"><?= e($user['email']) ?></p>
                <span class="badge-role <?= ($user['role'] === 'admin') ? 'badge-role-admin' : '' ?>">
                    <?= ($user['role'] === 'admin') ? 'Administrator' : 'Pengguna Terdaftar' ?>
                </span>

                <hr class="profile-divider">

                <div class="profile-stat-box">
                    <span class="p-num"><?= $totalFavorites ?></span>
                    <span class="p-lbl">Spesies Favorit Tersimpan</span>
                </div>

                <a href="<?= url('user/favorites') ?>" class="btn btn-outline-primary btn-block mt-3">
                    <i class="fa-solid fa-heart"></i> Lihat Daftar Favorit
                </a>
            </div>

            <!-- Form update -->
            <div class="profile-form-card">
                <h2 class="form-section-title"><i class="fa-solid fa-user-pen text-accent"></i> Perbarui Data Akun</h2>

                <form action="<?= url('user/updateProfile') ?>" method="POST" enctype="multipart/form-data" class="profile-form">
                    <?= csrfField() ?>

                    <div class="form-group">
                        <label for="profNama" class="form-label">Nama Lengkap</label>
                        <input type="text" id="profNama" name="nama" value="<?= e($user['nama']) ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="profEmail" class="form-label">Alamat Email</label>
                        <input type="email" id="profEmail" name="email" value="<?= e($user['email']) ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="profAvatar" class="form-label">Foto Profil / Avatar</label>
                        <input type="file" id="profAvatar" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="form-hint">Format JPG, PNG, atau WebP (maksimal 5MB).</small>
                    </div>

                    <hr class="form-divider">

                    <h3 class="form-subheading"><i class="fa-solid fa-lock text-accent"></i> Ganti Kata Sandi (Opsional)</h3>
                    <p class="form-hint mb-3">Biarkan kosong jika Anda tidak ingin mengubah kata sandi.</p>

                    <div class="form-group">
                        <label for="profNewPass" class="form-label">Kata Sandi Baru</label>
                        <input type="password" id="profNewPass" name="new_password" class="form-control" placeholder="Minimal 6 karakter...">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg mt-3">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container not-found-wrapper">
    <div class="not-found-card">
        <div class="not-found-icon">
            <i class="fa-solid fa-compass"></i>
        </div>
        <h1 class="not-found-title">404 — Jejak Tidak Ditemukan</h1>
        <p class="not-found-text">
            Halaman atau spesies fauna yang Anda cari sepertinya sedang bermigrasi atau tautan yang Anda tuju sudah tidak tersedia.
        </p>
        <div class="not-found-actions">
            <a href="<?= url('') ?>" class="btn btn-primary"><i class="fa-solid fa-house"></i> Kembali ke Beranda</a>
            <a href="<?= url('fauna') ?>" class="btn btn-outline-primary"><i class="fa-solid fa-compass"></i> Jelajahi Katalog</a>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


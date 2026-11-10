<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <a href="<?= url('user/profile') ?>">Profil</a> <i class="fa-solid fa-angle-right"></i>
            <span>Fauna Favorit</span>
        </div>
        <h1 class="page-title">Koleksi Fauna Favorit Saya</h1>
        <p class="page-desc">Daftar satwa nusantara yang Anda simpan untuk dipelajari lebih mendalam.</p>
    </div>
</section>

<section class="section favorites-content-section">
    <div class="container">
        <?php if (empty($favorites)): ?>
            <div class="empty-state-card">
                <i class="fa-regular fa-heart fa-3x"></i>
                <h3>Belum Ada Fauna Favorit</h3>
                <p>Jelajahi katalog dan tekan ikon hati pada kartu satwa untuk menyimpannya ke koleksi ini.</p>
                <a href="<?= url('fauna') ?>" class="btn btn-primary mt-3">
                    <i class="fa-solid fa-compass"></i> Buka Katalog Fauna
                </a>
            </div>
        <?php else: ?>
            <div class="favorites-header-flex">
                <span>Total <strong><?= count($favorites) ?></strong> satwa tersimpan di daftar favorit Anda.</span>
                <a href="<?= url('fauna') ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Jelajahi Satwa Lainnya
                </a>
            </div>

            <div class="fauna-grid mt-4">
                <?php foreach ($favorites as $f): ?>
                    <div class="fauna-card">
                        <div class="fauna-img-wrap">
                            <img src="<?= uploadUrl($f['primary_image']) ?>" alt="<?= e($f['nama_lokal']) ?>" loading="lazy">
                            <span class="status-badge <?= getStatusBadgeClass($f['status_konservasi']) ?>">
                                <?= e($f['status_konservasi']) ?>
                            </span>
                            <a href="<?= url('user/removeFavorite/' . $f['id']) ?>" class="fav-remove-btn" title="Hapus dari Favorit" onclick="return confirm('Hapus <?= e($f['nama_lokal']) ?> dari koleksi favorit?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                        <div class="fauna-body">
                            <div class="fauna-meta">
                                <span class="meta-tag"><i class="fa-solid fa-paw"></i> <?= e($f['nama_kategori']) ?></span>
                                <span class="meta-tag"><i class="fa-solid fa-tree"></i> <?= e($f['nama_habitat']) ?></span>
                            </div>
                            <h3 class="fauna-title">
                                <a href="<?= url('fauna/detail/' . $f['id']) ?>"><?= e($f['nama_lokal']) ?></a>
                            </h3>
                            <p class="fauna-scientific"><em><?= e($f['nama_ilmiah']) ?></em></p>
                            <p class="fauna-excerpt"><?= e(limitWords($f['deskripsi'], 16)) ?></p>
                            
                            <div class="fauna-footer">
                                <span class="fauna-pop-est">
                                    <i class="fa-solid fa-clock"></i> Disimpan <?= formatDateIndo($f['favorited_at']) ?>
                                </span>
                                <a href="<?= url('fauna/detail/' . $f['id']) ?>" class="card-arrow" aria-label="Detail <?= e($f['nama_lokal']) ?>">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


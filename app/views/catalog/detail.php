<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="detail-header-section">
    <div class="container">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <a href="<?= url('fauna') ?>">Katalog Fauna</a> <i class="fa-solid fa-angle-right"></i>
            <a href="<?= url('fauna?kategori=' . e($fauna['kategori_slug'])) ?>"><?= e($fauna['nama_kategori']) ?></a> <i class="fa-solid fa-angle-right"></i>
            <span><?= e($fauna['nama_lokal']) ?></span>
        </div>

        <div class="detail-title-flex">
            <div>
                <span class="status-badge <?= getStatusBadgeClass($fauna['status_konservasi']) ?>">
                    <i class="fa-solid fa-shield-halved"></i> Status IUCN: <?= e($fauna['status_konservasi']) ?>
                </span>
                <h1 class="detail-main-title"><?= e($fauna['nama_lokal']) ?></h1>
                <p class="detail-scientific-name">
                    Taksonomi Ilmiah: <em><?= e($fauna['nama_ilmiah']) ?></em>
                </p>
            </div>

            <!-- Action buttons -->
            <div class="detail-actions">
                <?php if (isLoggedIn()): ?>
                    <button class="btn btn-outline-primary btn-favorite-detail <?= $isFavorite ? 'favorited' : '' ?>" id="btnFavoriteDetail" data-id="<?= $fauna['id'] ?>">
                        <i class="<?= $isFavorite ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                        <span id="favText"><?= $isFavorite ? 'Tersimpan di Favorit' : 'Tambah ke Favorit' ?></span>
                    </button>
                <?php else: ?>
                    <a href="<?= url('auth/login') ?>" class="btn btn-outline-primary">
                        <i class="fa-regular fa-heart"></i> Masuk untuk Favoritkan
                    </a>
                <?php endif; ?>
                <button class="btn btn-icon btn-outline-secondary" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berhasil disalin!');" title="Salin Tautan">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="section detail-content-section">
    <div class="container">
        <div class="detail-grid">
            <!-- Left Column: Gallery & Story -->
            <div class="detail-main-col">
                <!-- Gallery Section -->
                <div class="detail-gallery-card">
                    <div class="main-image-viewport">
                        <img id="mainGalleryImg" src="<?= uploadUrl($fauna['primary_image']) ?>" alt="<?= e($fauna['nama_lokal']) ?>">
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="gallery-thumbs">
                            <?php foreach ($images as $img): ?>
                                <button class="gallery-thumb-btn" onclick="document.getElementById('mainGalleryImg').src='<?= uploadUrl($img['image_url']) ?>'">
                                    <img src="<?= uploadUrl($img['image_url']) ?>" alt="<?= e($img['caption'] ?? $fauna['nama_lokal']) ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Editorial Content Blocks -->
                <div class="editorial-card mt-4">
                    <h2 class="editorial-heading"><i class="fa-solid fa-align-left text-accent"></i> Deskripsi & Habitat Alami</h2>
                    <div class="editorial-body">
                        <p><?= nl2br(e($fauna['deskripsi'])) ?></p>
                    </div>

                    <?php if (!empty($fauna['karakteristik'])): ?>
                        <h3 class="editorial-subheading mt-4"><i class="fa-solid fa-fingerprint text-accent"></i> Karakteristik Morfologi & Fisik</h3>
                        <div class="editorial-body">
                            <p><?= nl2br(e($fauna['karakteristik'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($fauna['makanan'])): ?>
                        <h3 class="editorial-subheading mt-4"><i class="fa-solid fa-utensils text-accent"></i> Pola Pangan & Rantai Makanan</h3>
                        <div class="editorial-body">
                            <p><?= nl2br(e($fauna['makanan'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Persebaran Wilayah -->
                <div class="editorial-card mt-4">
                    <h2 class="editorial-heading"><i class="fa-solid fa-map-location-dot text-accent"></i> Wilayah Persebaran di Nusantara</h2>
                    <?php if (!empty($regions)): ?>
                        <div class="distribution-regions-list">
                            <?php foreach ($regions as $r): ?>
                                <div class="region-pill-item">
                                    <span class="region-zone-dot zone-dot-<?= strtolower($r['zona']) ?>"></span>
                                    <div>
                                        <strong><?= e($r['nama_wilayah']) ?></strong>
                                        <span class="zone-label">(Zona <?= e($r['zona']) ?>)</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Informasi persebaran spesifik sedang dalam proses inventarisasi lapangan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Quick Fact Sheet Sidebar -->
            <aside class="detail-sidebar-col">
                <div class="factsheet-card">
                    <h3 class="factsheet-title"><i class="fa-solid fa-clipboard-list"></i> Ringkasan Taksonomi & Spesifikasi</h3>
                    <ul class="factsheet-list">
                        <li>
                            <span class="fact-label">Nama Lokal:</span>
                            <span class="fact-value"><?= e($fauna['nama_lokal']) ?></span>
                        </li>
                        <li>
                            <span class="fact-label">Nama Ilmiah:</span>
                            <span class="fact-value"><em><?= e($fauna['nama_ilmiah']) ?></em></span>
                        </li>
                        <li>
                            <span class="fact-label">Kategori / Kelas:</span>
                            <span class="fact-value">
                                <a href="<?= url('fauna?kategori=' . e($fauna['kategori_slug'])) ?>"><?= e($fauna['nama_kategori']) ?></a>
                            </span>
                        </li>
                        <li>
                            <span class="fact-label">Tipe Habitat:</span>
                            <span class="fact-value"><?= e($fauna['nama_habitat']) ?></span>
                        </li>
                        <li>
                            <span class="fact-label">Status Konservasi:</span>
                            <span class="fact-value">
                                <span class="status-badge <?= getStatusBadgeClass($fauna['status_konservasi']) ?>">
                                    <?= e($fauna['status_konservasi']) ?>
                                </span>
                            </span>
                        </li>
                        <li>
                            <span class="fact-label">Estimasi Populasi:</span>
                            <span class="fact-value"><?= e($fauna['populasi_estimasi'] ?: 'Data dalam pemantauan') ?></span>
                        </li>
                    </ul>

                    <div class="factsheet-alert">
                        <i class="fa-solid fa-shield-cat"></i>
                        <p>Satwa ini dilindungi oleh Undang-Undang Konservasi Sumber Daya Alam Hayati dan Ekosistemnya.</p>
                    </div>

                    <a href="<?= url('map') ?>" class="btn btn-secondary btn-block mt-3">
                        <i class="fa-solid fa-earth-asia"></i> Lihat Peta Biogeografi
                    </a>
                </div>
            </aside>
        </div>

        <!-- Related Species -->
        <?php if (!empty($related)): ?>
            <div class="related-section mt-5">
                <div class="section-header-flex">
                    <div>
                        <span class="section-subtitle">TAKSONOMI SERUPA</span>
                        <h2 class="section-title">Spesies Kerabat di Kelas <?= e($fauna['nama_kategori']) ?></h2>
                    </div>
                    <a href="<?= url('fauna?kategori=' . e($fauna['kategori_slug'])) ?>" class="btn btn-outline-primary">
                        Jelajahi Semua <?= e($fauna['nama_kategori']) ?> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="fauna-grid">
                    <?php foreach ($related as $rel): ?>
                        <div class="fauna-card">
                            <div class="fauna-img-wrap">
                                <img src="<?= uploadUrl($rel['primary_image']) ?>" alt="<?= e($rel['nama_lokal']) ?>" loading="lazy">
                                <span class="status-badge <?= getStatusBadgeClass($rel['status_konservasi']) ?>">
                                    <?= e($rel['status_konservasi']) ?>
                                </span>
                            </div>
                            <div class="fauna-body">
                                <h3 class="fauna-title">
                                    <a href="<?= url('fauna/detail/' . $rel['id']) ?>"><?= e($rel['nama_lokal']) ?></a>
                                </h3>
                                <p class="fauna-scientific"><em><?= e($rel['nama_ilmiah']) ?></em></p>
                                <div class="fauna-footer">
                                    <span class="meta-tag"><i class="fa-solid fa-tree"></i> <?= e($rel['nama_habitat']) ?></span>
                                    <a href="<?= url('fauna/detail/' . $rel['id']) ?>" class="card-arrow" aria-label="Detail <?= e($rel['nama_lokal']) ?>">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


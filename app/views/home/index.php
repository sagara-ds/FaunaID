<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-container">
        <div class="hero-content">
            <div class="hero-pill">
                <i class="fa-solid fa-seedling"></i> Khazanah Satwa Hayati Indonesia
            </div>
            <h1 class="hero-title">
                Mengenal & Menjaga <br>
                <span class="hero-highlight">Fauna Nusantara</span>
            </h1>
            <p class="hero-desc">
                Jelajahi ensiklopedia digital keanekaragaman satwa liar endemik Indonesia yang tersebar di antara Garis Wallace dan Garis Weber — dari rimba lebat Sumatra hingga belantara Papua.
            </p>

            <!-- Search Bar in Hero -->
            <form action="<?= url('fauna') ?>" method="GET" class="hero-search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="q" placeholder="Cari nama satwa (misal: Harimau, Komodo, Cenderawasih)..." autocomplete="off">
                <button type="submit" class="btn btn-primary">Eksplorasi</button>
            </form>

            <!-- Hero Stats Counter -->
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= $stats['total_fauna'] ?>+</span>
                    <span class="stat-label">Spesies Terdokumentasi</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">3</span>
                    <span class="stat-label">Zona Biogeografi</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $stats['total_categories'] ?></span>
                    <span class="stat-label">Kelompok Taksonomi</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $stats['total_articles'] ?></span>
                    <span class="stat-label">Jurnal & Artikel</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-image-card">
                <img src="https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1000&q=80" alt="Harimau Sumatera" class="hero-main-img">
                <div class="hero-floating-badge badge-top">
                    <span class="pulse-dot"></span>
                    <div>
                        <strong>Spesies Endemik Kritis</strong>
                        <p>Harimau Sumatera (Panthera tigris sumatrae)</p>
                    </div>
                </div>
                <div class="hero-floating-badge badge-bottom">
                    <i class="fa-solid fa-map-location-dot text-accent"></i>
                    <div>
                        <strong>Peta Persebaran 3 Zona</strong>
                        <p>Asiatis • Peralihan • Australis</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3 Biogeographic Zones Teaser Section -->
<section class="section zones-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-subtitle">BIOGEOGRAFI NUSANTARA</span>
            <h2 class="section-title">Tiga Wilayah Karakteristik Fauna Indonesia</h2>
            <p class="section-desc">
                Dipisahkan oleh Garis Wallace dan Garis Weber, Indonesia memiliki perpaduan satwa tipe Asia, tipe peralihan endemik murni, dan tipe Australia.
            </p>
        </div>

        <div class="zones-grid">
            <!-- Zona Asiatis -->
            <div class="zone-card zone-asiatis">
                <div class="zone-badge">Zona Barat (Paparan Sunda)</div>
                <h3 class="zone-name">Fauna Asiatis</h3>
                <p class="zone-desc">
                    Meliputi Sumatra, Jawa, Bali, dan Kalimantan. Didominasi oleh mamalia bertubuh besar, primata kanopi lebat, dan kera langka.
                </p>
                <div class="zone-examples">
                    <span>Contoh: Harimau, Badak, Orangutan, Gajah</span>
                </div>
                <a href="<?= url('map?zone=Asiatis') ?>" class="zone-link">
                    Eksplorasi Wilayah Asiatis <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Zona Peralihan -->
            <div class="zone-card zone-peralihan">
                <div class="zone-badge">Zona Tengah (Wallacea)</div>
                <h3 class="zone-name">Fauna Peralihan</h3>
                <p class="zone-desc">
                    Meliputi Sulawesi, Kepulauan Maluku, dan Nusa Tenggara. Memiliki tingkat keunikan dan endemisitas purba tertinggi di dunia.
                </p>
                <div class="zone-examples">
                    <span>Contoh: Komodo, Anoa, Babirusa, Burung Maleo</span>
                </div>
                <a href="<?= url('map?zone=Peralihan') ?>" class="zone-link">
                    Eksplorasi Wilayah Wallacea <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Zona Australis -->
            <div class="zone-card zone-australis">
                <div class="zone-badge">Zona Timur (Paparan Sahul)</div>
                <h3 class="zone-name">Fauna Australis</h3>
                <p class="zone-desc">
                    Mencakup pulau Papua dan pulau satelit sekitarnya. Terkenal dengan satwa berkantung (marsupial) dan burung berbulu surga memesona.
                </p>
                <div class="zone-examples">
                    <span>Contoh: Cenderawasih, Kasuari, Kangguru Pohon</span>
                </div>
                <a href="<?= url('map?zone=Australis') ?>" class="zone-link">
                    Eksplorasi Wilayah Australis <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?= url('map') ?>" class="btn btn-secondary btn-lg">
                <i class="fa-solid fa-map-marked-alt"></i> Buka Peta Interaktif Lengkap
            </a>
        </div>
    </div>
</section>

<!-- Featured Fauna Section -->
<section class="section featured-section bg-cream">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="section-subtitle">SPEKTRA SPESIES</span>
                <h2 class="section-title">Fauna Pilihan & Satwa Kunci</h2>
                <p class="section-desc">Satwa payung yang menjadi indikator kesehatan ekosistem dan kebanggaan alam Indonesia.</p>
            </div>
            <a href="<?= url('fauna') ?>" class="btn btn-outline-primary">
                Lihat Semua Fauna (<?= $stats['total_fauna'] ?>) <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="fauna-grid">
            <?php foreach ($featuredFauna as $f): ?>
                <div class="fauna-card">
                    <div class="fauna-img-wrap">
                        <img src="<?= uploadUrl($f['primary_image']) ?>" alt="<?= e($f['nama_lokal']) ?>" loading="lazy">
                        <span class="status-badge <?= getStatusBadgeClass($f['status_konservasi']) ?>">
                            <?= e($f['status_konservasi']) ?>
                        </span>
                        <?php if (isLoggedIn()): ?>
                            <button class="fav-toggle-btn" data-id="<?= $f['id'] ?>" title="Favoritkan">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        <?php endif; ?>
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
                                <i class="fa-solid fa-users-viewfinder"></i> <?= e($f['populasi_estimasi'] ?: 'Data populasi terpantau') ?>
                            </span>
                            <a href="<?= url('fauna/detail/' . $f['id']) ?>" class="card-arrow" aria-label="Detail <?= e($f['nama_lokal']) ?>">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section categories-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-subtitle">KLASIFIKASI</span>
            <h2 class="section-title">Kategori Taksonomi Satwa</h2>
            <p class="section-desc">Jelajahi fauna berdasarkan kelompok kelas biologi dan habitat alaminya.</p>
        </div>

        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('fauna?kategori=' . $cat['slug']) ?>" class="category-card">
                    <div class="cat-icon-wrap">
                        <i class="fa-solid fa-<?= e($cat['ikon'] ?: 'paw') ?>"></i>
                    </div>
                    <h3 class="cat-title"><?= e($cat['nama_kategori']) ?></h3>
                    <p class="cat-desc"><?= e(limitWords($cat['deskripsi'] ?? '', 12)) ?></p>
                    <span class="cat-count"><?= (int)($cat['total_fauna'] ?? 0) ?> Spesies</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Articles Section -->
<section class="section articles-preview-section bg-cream">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="section-subtitle">WACANA & RISET</span>
                <h2 class="section-title">Artikel Edukasi & Konservasi</h2>
                <p class="section-desc">Kabar terbaru tentang upaya penyelamatan satwa dan kekayaan alam Indonesia.</p>
            </div>
            <a href="<?= url('articles') ?>" class="btn btn-outline-primary">
                Baca Seluruh Artikel <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="articles-grid">
            <?php foreach ($latestArticles as $art): ?>
                <article class="article-card">
                    <div class="article-thumb-wrap">
                        <img src="<?= uploadUrl($art['thumbnail']) ?>" alt="<?= e($art['judul']) ?>" loading="lazy">
                        <span class="article-date"><i class="fa-regular fa-calendar"></i> <?= formatDateIndo($art['created_at']) ?></span>
                    </div>
                    <div class="article-card-body">
                        <h3 class="article-card-title">
                            <a href="<?= url('articles/read/' . $art['slug']) ?>"><?= e($art['judul']) ?></a>
                        </h3>
                        <p class="article-card-excerpt"><?= e(limitWords($art['ringkasan'] ?? $art['konten'], 20)) ?></p>
                        <div class="article-card-footer">
                            <span class="article-author"><i class="fa-solid fa-user-pen"></i> <?= e($art['author_name']) ?></span>
                            <span class="article-views"><i class="fa-regular fa-eye"></i> <?= $art['views'] ?> pembaca</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Community / Pledge Banner -->
<section class="section cta-section">
    <div class="container cta-container">
        <div class="cta-card">
            <div class="cta-content">
                <span class="cta-pill"><i class="fa-solid fa-shield-heart"></i> Bersama Melindungi Alam</span>
                <h2 class="cta-title">Ikut Berperan dalam Edukasi dan Pelestarian Satwa</h2>
                <p class="cta-desc">
                    Simpan fauna favorit Anda, telusuri peta persebaran, dan bantu sebarkan pengetahuan konservasi kepada generasi muda bangsa.
                </p>
                <div class="cta-actions">
                    <?php if (!isLoggedIn()): ?>
                        <a href="<?= url('auth/register') ?>" class="btn btn-accent btn-lg"><i class="fa-solid fa-user-plus"></i> Gabung Gratis Sekarang</a>
                        <a href="<?= url('fauna') ?>" class="btn btn-outline-light btn-lg">Telusuri Katalog</a>
                    <?php else: ?>
                        <a href="<?= url('user/favorites') ?>" class="btn btn-accent btn-lg"><i class="fa-solid fa-heart"></i> Lihat Koleksi Favorit Saya</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


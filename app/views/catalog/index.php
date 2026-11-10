<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <span>Katalog Fauna</span>
        </div>
        <h1 class="page-title">Katalog Keanekaragaman Fauna Indonesia</h1>
        <p class="page-desc">
            Temukan informasi mendalam tentang taksonomi, karakteristik fisik, status konservasi, dan habitat alami satwa kepulauan nusantara.
        </p>
    </div>
</section>

<section class="section catalog-content-section">
    <div class="container">
        <div class="catalog-layout">
            <!-- Filter Sidebar -->
            <aside class="catalog-sidebar">
                <form action="<?= url('fauna') ?>" method="GET" id="filterForm">
                    <div class="sidebar-block">
                        <div class="sidebar-header-flex">
                            <h3 class="sidebar-title"><i class="fa-solid fa-sliders"></i> Filter Satwa</h3>
                            <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['habitat']) || !empty($filters['status']) || !empty($filters['zona'])): ?>
                                <a href="<?= url('fauna') ?>" class="reset-filter-link">Reset</a>
                            <?php endif; ?>
                        </div>

                        <!-- Keyword Search Input -->
                        <div class="filter-group">
                            <label class="filter-label">Cari Kata Kunci</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" name="q" value="<?= e($filters['search']) ?>" placeholder="Nama lokal / ilmiah..." class="form-control">
                            </div>
                        </div>

                        <!-- Zona Biogeografi -->
                        <div class="filter-group">
                            <label class="filter-label">Zona Biogeografi</label>
                            <select name="zona" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Zona</option>
                                <option value="Asiatis" <?= ($filters['zona'] === 'Asiatis') ? 'selected' : '' ?>>Zona Asiatis (Barat)</option>
                                <option value="Peralihan" <?= ($filters['zona'] === 'Peralihan') ? 'selected' : '' ?>>Zona Peralihan (Wallacea)</option>
                                <option value="Australis" <?= ($filters['zona'] === 'Australis') ? 'selected' : '' ?>>Zona Australis (Timur)</option>
                            </select>
                        </div>

                        <!-- Kategori Taksonomi -->
                        <div class="filter-group">
                            <label class="filter-label">Kategori / Kelas</label>
                            <select name="kategori" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= e($cat['slug']) ?>" <?= ($filters['category'] === $cat['slug']) ? 'selected' : '' ?>>
                                        <?= e($cat['nama_kategori']) ?> (<?= (int)$cat['total_fauna'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Habitat -->
                        <div class="filter-group">
                            <label class="filter-label">Tipe Habitat</label>
                            <select name="habitat" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Habitat</option>
                                <?php foreach ($habitats as $hab): ?>
                                    <option value="<?= $hab['id'] ?>" <?= ((string)$filters['habitat'] === (string)$hab['id']) ? 'selected' : '' ?>>
                                        <?= e($hab['nama_habitat']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Status Konservasi IUCN -->
                        <div class="filter-group">
                            <label class="filter-label">Status Konservasi IUCN</label>
                            <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Status</option>
                                <option value="Kritis (Critically Endangered)" <?= str_contains($filters['status'], 'Kritis') ? 'selected' : '' ?>>Kritis (Critically Endangered)</option>
                                <option value="Genting (Endangered)" <?= str_contains($filters['status'], 'Genting') ? 'selected' : '' ?>>Genting (Endangered)</option>
                                <option value="Rentan (Vulnerable)" <?= str_contains($filters['status'], 'Rentan') ? 'selected' : '' ?>>Rentan (Vulnerable)</option>
                                <option value="Risiko Rendah (Least Concern)" <?= str_contains($filters['status'], 'Risiko Rendah') ? 'selected' : '' ?>>Risiko Rendah (Least Concern)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            <i class="fa-solid fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </aside>

            <!-- Main Results Area -->
            <main class="catalog-main">
                <!-- Top Toolbar: Count & Sort -->
                <div class="catalog-toolbar">
                    <div class="catalog-count">
                        Menampilkan <strong><?= count($faunaList) ?></strong> dari total <strong><?= $pagination['totalItems'] ?></strong> spesies
                    </div>
                    <div class="catalog-sort">
                        <label for="sortSelect">Urutkan:</label>
                        <select id="sortSelect" class="form-select form-select-sm" onchange="let url = new URL(window.location.href); url.searchParams.set('sort', this.value); window.location.href = url.toString();">
                            <option value="latest" <?= ($filters['sort'] === 'latest') ? 'selected' : '' ?>>Terbaru</option>
                            <option value="name_asc" <?= ($filters['sort'] === 'name_asc') ? 'selected' : '' ?>>Nama (A - Z)</option>
                            <option value="name_desc" <?= ($filters['sort'] === 'name_desc') ? 'selected' : '' ?>>Nama (Z - A)</option>
                            <option value="oldest" <?= ($filters['sort'] === 'oldest') ? 'selected' : '' ?>>Terlama</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filter Tags -->
                <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['zona']) || !empty($filters['habitat']) || !empty($filters['status'])): ?>
                    <div class="active-filter-pills">
                        <span class="active-label">Filter aktif:</span>
                        <?php if (!empty($filters['search'])): ?>
                            <span class="filter-pill">Cari: "<?= e($filters['search']) ?>"</span>
                        <?php endif; ?>
                        <?php if (!empty($filters['zona'])): ?>
                            <span class="filter-pill">Zona: <?= e($filters['zona']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($filters['category'])): ?>
                            <span class="filter-pill">Kategori: <?= e($filters['category']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($filters['status'])): ?>
                            <span class="filter-pill">Status: <?= e($filters['status']) ?></span>
                        <?php endif; ?>
                        <a href="<?= url('fauna') ?>" class="clear-all-link">Hapus Semua</a>
                    </div>
                <?php endif; ?>

                <!-- Fauna Grid -->
                <?php if (empty($faunaList)): ?>
                    <div class="empty-state-card">
                        <i class="fa-solid fa-tree fa-3x"></i>
                        <h3>Tidak Ada Spesies yang Ditemukan</h3>
                        <p>Coba gunakan kata kunci pencarian lain atau kurangi kombinasi filter yang Anda pilih.</p>
                        <a href="<?= url('fauna') ?>" class="btn btn-outline-primary mt-3">Reset Filter</a>
                    </div>
                <?php else: ?>
                    <div class="fauna-grid">
                        <?php foreach ($faunaList as $f): ?>
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

                    <!-- Pagination -->
                    <?php if ($pagination['totalPages'] > 1): ?>
                        <div class="pagination-wrapper">
                            <?php
                                $queryParams = $_GET;
                                function getPageUrl($p, $params) {
                                    $params['page'] = $p;
                                    return url('fauna?' . http_build_query($params));
                                }
                            ?>
                            <?php if ($pagination['currentPage'] > 1): ?>
                                <a href="<?= getPageUrl($pagination['currentPage'] - 1, $queryParams) ?>" class="page-link page-prev">
                                    <i class="fa-solid fa-chevron-left"></i> Sebelumnya
                                </a>
                            <?php endif; ?>

                            <div class="page-numbers">
                                <?php for ($p = 1; $p <= $pagination['totalPages']; $p++): ?>
                                    <a href="<?= getPageUrl($p, $queryParams) ?>" class="page-link <?= ($p == $pagination['currentPage']) ? 'active' : '' ?>">
                                        <?= $p ?>
                                    </a>
                                <?php endfor; ?>
                            </div>

                            <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                                <a href="<?= getPageUrl($pagination['currentPage'] + 1, $queryParams) ?>" class="page-link page-next">
                                    Berikutnya <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


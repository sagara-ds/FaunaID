<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <span>Artikel Edukasi</span>
        </div>
        <h1 class="page-title">Artikel Edukasi & Jurnal Konservasi Satwa</h1>
        <p class="page-desc">
            Informasi ilmiah, kabar restorasi ekosistem, serta cerita inspiratif para jagawana dan pegiat alam di seluruh penjuru nusantara.
        </p>

        <!-- Search in articles -->
        <form action="<?= url('articles') ?>" method="GET" class="article-search-bar mt-4">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari artikel seputar konservasi, habitat, satwa...">
            <button type="submit" class="btn btn-primary">Cari Artikel</button>
        </form>
    </div>
</section>

<section class="section articles-content-section">
    <div class="container">
        <?php if (!empty($search)): ?>
            <div class="search-result-info mb-4">
                Hasil pencarian untuk: <strong>"<?= e($search) ?>"</strong> (<?= $pagination['totalItems'] ?> artikel ditemukan)
                <a href="<?= url('articles') ?>" class="text-accent ml-2">[Hapus Pencarian]</a>
            </div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div class="empty-state-card">
                <i class="fa-solid fa-newspaper fa-3x"></i>
                <h3>Belum Ada Artikel yang Ditemukan</h3>
                <p>Silakan coba kata kunci lain atau periksa kembali ejaan pencarian Anda.</p>
                <a href="<?= url('articles') ?>" class="btn btn-outline-primary mt-3">Tampilkan Semua Artikel</a>
            </div>
        <?php else: ?>
            <div class="articles-grid">
                <?php foreach ($articles as $art): ?>
                    <article class="article-card">
                        <div class="article-thumb-wrap">
                            <img src="<?= uploadUrl($art['thumbnail']) ?>" alt="<?= e($art['judul']) ?>" loading="lazy">
                            <span class="article-date"><i class="fa-regular fa-calendar"></i> <?= formatDateIndo($art['created_at']) ?></span>
                        </div>
                        <div class="article-card-body">
                            <h2 class="article-card-title">
                                <a href="<?= url('articles/read/' . $art['slug']) ?>"><?= e($art['judul']) ?></a>
                            </h2>
                            <p class="article-card-excerpt"><?= e(limitWords($art['ringkasan'] ?? $art['konten'], 22)) ?></p>
                            <div class="article-card-footer">
                                <span class="article-author"><i class="fa-solid fa-user-pen"></i> <?= e($art['author_name']) ?></span>
                                <span class="article-views"><i class="fa-regular fa-eye"></i> <?= $art['views'] ?> views</span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['totalPages'] > 1): ?>
                <div class="pagination-wrapper mt-5">
                    <?php for ($p = 1; $p <= $pagination['totalPages']; $p++): ?>
                        <a href="<?= url('articles?page=' . $p . (!empty($search) ? '&q=' . urlencode($search) : '')) ?>" class="page-link <?= ($p == $pagination['currentPage']) ? 'active' : '' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


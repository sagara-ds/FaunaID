<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="page-header article-detail-header">
    <div class="container article-container-narrow">
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <a href="<?= url('articles') ?>">Artikel Edukasi</a> <i class="fa-solid fa-angle-right"></i>
            <span>Baca Artikel</span>
        </div>

        <h1 class="article-headline"><?= e($article['judul']) ?></h1>

        <div class="article-meta-header">
            <div class="author-info-block">
                <div class="author-avatar-circle">
                    <?php if (!empty($article['author_avatar'])): ?>
                        <img src="<?= uploadUrl($article['author_avatar']) ?>" alt="<?= e($article['author_name']) ?>">
                    <?php else: ?>
                        <span><?= strtoupper(substr($article['author_name'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <strong><?= e($article['author_name']) ?></strong>
                    <span class="meta-sub">Penulis Kontributor FaunaID</span>
                </div>
            </div>

            <div class="article-stats-right">
                <span><i class="fa-regular fa-calendar"></i> <?= formatDateIndo($article['created_at']) ?></span>
                <span><i class="fa-regular fa-eye"></i> <?= (int)$article['views'] ?> kali dibaca</span>
            </div>
        </div>
    </div>
</section>

<section class="section article-body-section">
    <div class="container article-container-narrow">
        <!-- Main Featured Image -->
        <?php if (!empty($article['thumbnail'])): ?>
            <div class="article-hero-image">
                <img src="<?= uploadUrl($article['thumbnail']) ?>" alt="<?= e($article['judul']) ?>">
            </div>
        <?php endif; ?>

        <!-- Content Body -->
        <article class="article-prose">
            <?= $article['konten'] ?>
        </article>

        <!-- Social Share & Return Button -->
        <div class="article-bottom-bar">
            <a href="<?= url('articles') ?>" class="btn btn-outline-primary">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Artikel
            </a>
            <div class="share-buttons">
                <span class="share-text">Bagikan wawasan ini:</span>
                <button class="btn btn-icon btn-outline-secondary" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan artikel berhasil disalin!');" title="Salin Tautan">
                    <i class="fa-solid fa-link"></i>
                </button>
            </div>
        </div>

        <!-- Latest Other Articles -->
        <?php if (!empty($latestArticles)): ?>
            <div class="other-articles-box mt-5">
                <h3 class="other-articles-title">Artikel Edukasi Lainnya</h3>
                <div class="articles-grid">
                    <?php foreach ($latestArticles as $other): ?>
                        <?php if ((int)$other['id'] !== (int)$article['id']): ?>
                            <div class="article-card">
                                <div class="article-thumb-wrap">
                                    <img src="<?= uploadUrl($other['thumbnail']) ?>" alt="<?= e($other['judul']) ?>">
                                </div>
                                <div class="article-card-body">
                                    <h4 class="article-card-title">
                                        <a href="<?= url('articles/read/' . $other['slug']) ?>"><?= e($other['judul']) ?></a>
                                    </h4>
                                    <span class="article-date"><i class="fa-regular fa-calendar"></i> <?= formatDateIndo($other['created_at']) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<!-- Top Stat Cards -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="stat-icon-wrap bg-primary-soft">
            <i class="fa-solid fa-paw"></i>
        </div>
        <div class="stat-info">
            <span class="stat-num"><?= $stats['total_fauna'] ?></span>
            <span class="stat-text">Total Spesies Fauna</span>
        </div>
        <a href="<?= url('admin/fauna') ?>" class="stat-card-link"><i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="admin-stat-card">
        <div class="stat-icon-wrap bg-accent-soft">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="stat-info">
            <span class="stat-num"><?= $stats['total_categories'] ?></span>
            <span class="stat-text">Kategori Taksonomi</span>
        </div>
        <a href="<?= url('admin/categories') ?>" class="stat-card-link"><i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="admin-stat-card">
        <div class="stat-icon-wrap bg-secondary-soft">
            <i class="fa-solid fa-tree"></i>
        </div>
        <div class="stat-info">
            <span class="stat-num"><?= $stats['total_habitats'] ?></span>
            <span class="stat-text">Tipe Habitat Alam</span>
        </div>
        <a href="<?= url('admin/habitats') ?>" class="stat-card-link"><i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="admin-stat-card">
        <div class="stat-icon-wrap bg-info-soft">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <span class="stat-num"><?= $stats['total_articles'] ?></span>
            <span class="stat-text">Artikel Edukasi</span>
        </div>
        <a href="<?= url('admin/articles') ?>" class="stat-card-link"><i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="admin-stat-card">
        <div class="stat-icon-wrap bg-warning-soft">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <span class="stat-num"><?= $stats['total_users'] ?></span>
            <span class="stat-text">Pengguna Terdaftar</span>
        </div>
        <a href="<?= url('admin/users') ?>" class="stat-card-link"><i class="fa-solid fa-arrow-right"></i></a>
    </div>
</div>

<!-- Quick Actions Banner -->
<div class="admin-quick-actions-bar mt-4">
    <span class="quick-title"><i class="fa-solid fa-bolt"></i> Aksi Cepat:</span>
    <a href="<?= url('admin/fauna-create') ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Fauna Baru</a>
    <a href="<?= url('admin/article-create') ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen-nib"></i> Tulis Artikel Baru</a>
    <a href="<?= url('admin/categories') ?>" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-tags"></i> Kelola Kategori</a>
</div>

<!-- Two Columns Data Tables -->
<div class="admin-grid-2 mt-4">
    <!-- Recent Fauna -->
    <div class="admin-panel-card">
        <div class="admin-panel-header">
            <h3 class="panel-heading"><i class="fa-solid fa-paw text-accent"></i> Fauna Baru Ditambahkan</h3>
            <a href="<?= url('admin/fauna') ?>" class="panel-view-all">Kelola Semua &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Spesies</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentFauna as $f): ?>
                        <tr>
                            <td>
                                <div class="table-item-flex">
                                    <img src="<?= uploadUrl($f['primary_image']) ?>" alt="" class="table-thumb">
                                    <div>
                                        <strong><?= e($f['nama_lokal']) ?></strong>
                                        <small class="d-block text-muted"><em><?= e($f['nama_ilmiah']) ?></em></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= e($f['nama_kategori']) ?></td>
                            <td>
                                <span class="status-badge <?= getStatusBadgeClass($f['status_konservasi']) ?>">
                                    <?= e($f['status_konservasi']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= url('admin/fauna-edit/' . $f['id']) ?>" class="btn btn-icon btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Articles -->
    <div class="admin-panel-card">
        <div class="admin-panel-header">
            <h3 class="panel-heading"><i class="fa-solid fa-newspaper text-accent"></i> Artikel Edukasi Terkini</h3>
            <a href="<?= url('admin/articles') ?>" class="panel-view-all">Kelola Semua &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentArticles as $art): ?>
                        <tr>
                            <td>
                                <strong><?= e(limitWords($art['judul'], 8)) ?></strong>
                                <small class="d-block text-muted"><?= formatDateIndo($art['created_at']) ?></small>
                            </td>
                            <td><?= e($art['author_name']) ?></td>
                            <td>
                                <span class="badge <?= ($art['status'] === 'published') ? 'badge-success' : 'badge-warning' ?>">
                                    <?= ucfirst($art['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= url('admin/article-edit/' . $art['id']) ?>" class="btn btn-icon btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/admin_footer.php'; ?>


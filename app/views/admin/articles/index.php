<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Artikel Edukasi</h1>
        <p class="admin-page-subtitle">Publikasikan karya tulis ilmiah, liputan konservasi, dan kisah inspiratif seputar satwa nusantara.</p>
    </div>
    <div>
        <a href="<?= url('admin/article-create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-pen-nib"></i> Tulis Artikel Baru
        </a>
    </div>
</div>

<div class="admin-panel-card mt-4">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Sampul</th>
                    <th>Judul Artikel</th>
                    <th>Penulis</th>
                    <th class="text-center">Pembaca</th>
                    <th class="text-center">Status</th>
                    <th>Tanggal Terbit</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Belum ada artikel edukasi yang dibuat.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articles as $art): ?>
                        <tr>
                            <td>#<?= $art['id'] ?></td>
                            <td>
                                <img src="<?= uploadUrl($art['thumbnail']) ?>" alt="" class="table-thumb">
                            </td>
                            <td>
                                <strong><?= e($art['judul']) ?></strong>
                                <small class="d-block text-muted">slug: <code><?= e($art['slug']) ?></code></small>
                            </td>
                            <td><?= e($art['author_name']) ?></td>
                            <td class="text-center"><?= (int)$art['views'] ?> views</td>
                            <td class="text-center">
                                <span class="badge <?= ($art['status'] === 'published') ? 'badge-success' : 'badge-warning' ?>">
                                    <?= ucfirst($art['status']) ?>
                                </span>
                            </td>
                            <td><?= formatDateIndo($art['created_at']) ?></td>
                            <td class="text-center">
                                <div class="action-btn-group">
                                    <a href="<?= url('articles/read/' . $art['slug']) ?>" target="_blank" class="btn btn-icon btn-sm btn-outline-secondary" title="Lihat di Web">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?= url('admin/article-edit/' . $art['id']) ?>" class="btn btn-icon btn-sm btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="<?= url('admin/article-delete/' . $art['id']) ?>" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/admin_footer.php'; ?>


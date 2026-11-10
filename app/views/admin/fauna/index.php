<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Data Fauna</h1>
        <p class="admin-page-subtitle">Kelola seluruh basis data spesies satwa liar, taksonomi, dan wilayah persebaran.</p>
    </div>
    <div>
        <a href="<?= url('admin/fauna-create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Spesies Baru
        </a>
    </div>
</div>

<div class="admin-panel-card mt-4">
    <!-- Filter & Search Toolbar -->
    <div class="admin-toolbar-flex">
        <form action="<?= url('admin/fauna') ?>" method="GET" class="admin-search-form">
            <div class="input-with-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari nama lokal / nama ilmiah..." class="form-control form-control-sm">
            </div>

            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ((string)$categoryId === (string)$cat['id']) ? 'selected' : '' ?>>
                        <?= e($cat['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
            <?php if (!empty($search) || !empty($categoryId)): ?>
                <a href="<?= url('admin/fauna') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            <?php endif; ?>
        </form>

        <div class="admin-toolbar-meta">
            Total: <strong><?= $pagination['totalItems'] ?></strong> spesies
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive mt-3">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Foto</th>
                    <th>Nama Satwa</th>
                    <th>Kategori</th>
                    <th>Tipe Habitat</th>
                    <th>Status Konservasi</th>
                    <th>Unggulan</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faunaList)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Tidak ada data fauna yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faunaList as $f): ?>
                        <tr>
                            <td>#<?= $f['id'] ?></td>
                            <td>
                                <img src="<?= uploadUrl($f['primary_image']) ?>" alt="" class="table-thumb">
                            </td>
                            <td>
                                <strong><?= e($f['nama_lokal']) ?></strong>
                                <small class="d-block text-muted"><em><?= e($f['nama_ilmiah']) ?></em></small>
                            </td>
                            <td><?= e($f['nama_kategori']) ?></td>
                            <td><?= e($f['nama_habitat']) ?></td>
                            <td>
                                <span class="status-badge <?= getStatusBadgeClass($f['status_konservasi']) ?>">
                                    <?= e($f['status_konservasi']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($f['is_featured'])): ?>
                                    <span class="badge badge-success"><i class="fa-solid fa-star"></i> Featured</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="action-btn-group">
                                    <a href="<?= url('fauna/detail/' . $f['id']) ?>" target="_blank" class="btn btn-icon btn-sm btn-outline-secondary" title="Lihat di Web">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?= url('admin/fauna-edit/' . $f['id']) ?>" class="btn btn-icon btn-sm btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="<?= url('admin/fauna-delete/' . $f['id']) ?>" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data spesies <?= e($f['nama_lokal']) ?>?');">
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

    <!-- Pagination -->
    <?php if ($pagination['totalPages'] > 1): ?>
        <div class="pagination-wrapper mt-4">
            <?php for ($p = 1; $p <= $pagination['totalPages']; $p++): ?>
                <a href="<?= url('admin/fauna?page=' . $p . (!empty($search) ? '&q=' . urlencode($search) : '') . (!empty($categoryId) ? '&category=' . $categoryId : '')) ?>" class="page-link <?= ($p == $pagination['currentPage']) ? 'active' : '' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/admin_footer.php'; ?>


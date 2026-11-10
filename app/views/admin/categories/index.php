<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Kategori Fauna</h1>
        <p class="admin-page-subtitle">Kelola klasifikasi taksonomi kelas satwa (Mamalia, Aves, Reptil, dll).</p>
    </div>
</div>

<div class="admin-grid-2 mt-4">
    <!-- Form Tambah Kategori -->
    <div class="admin-panel-card">
        <h3 class="panel-heading"><i class="fa-solid fa-plus text-accent"></i> Tambah Kategori Baru</h3>
        <form action="<?= url('admin/categoryStore') ?>" method="POST" class="mt-3">
            <?= csrfField() ?>

            <div class="form-group">
                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" placeholder="Contoh: Amfibi" required>
            </div>

            <div class="form-group">
                <label for="ikon" class="form-label">Ikon Font Awesome</label>
                <input type="text" id="ikon" name="ikon" class="form-control" placeholder="Contoh: frog, paw, feather, fish" value="paw">
                <small class="form-hint">Nama ikon FontAwesome (misal: paw, feather, fish, droplet, shield).</small>
            </div>

            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control" placeholder="Uraian singkat mengenai kategori ini..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Kategori
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="admin-panel-card">
        <h3 class="panel-heading"><i class="fa-solid fa-layer-group text-accent"></i> Daftar Kategori Tersedia</h3>
        <div class="table-responsive mt-3">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="40">Ikon</th>
                        <th>Kategori</th>
                        <th>Slug</th>
                        <th class="text-center">Jumlah Satwa</th>
                        <th width="80" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="text-center"><i class="fa-solid fa-<?= e($cat['ikon'] ?: 'paw') ?> text-accent"></i></td>
                            <td><strong><?= e($cat['nama_kategori']) ?></strong></td>
                            <td><code><?= e($cat['slug']) ?></code></td>
                            <td class="text-center"><span class="badge badge-info"><?= (int)$cat['total_fauna'] ?> spesies</span></td>
                            <td class="text-center">
                                <a href="<?= url('admin/categoryDelete/' . $cat['id']) ?>" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus Kategori" onclick="return confirm('Hapus kategori <?= e($cat['nama_kategori']) ?>? Seluruh fauna terkait akan terpengaruh!');">
                                    <i class="fa-solid fa-trash-can"></i>
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


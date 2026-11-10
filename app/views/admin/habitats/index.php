<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Tipe Habitat</h1>
        <p class="admin-page-subtitle">Kelola ekosistem lingkungan hidup alami satwa (Hutan Hujan, Sabana, Mangrove, dll).</p>
    </div>
</div>

<div class="admin-grid-2 mt-4">
    <!-- Form Tambah Habitat -->
    <div class="admin-panel-card">
        <h3 class="panel-heading"><i class="fa-solid fa-plus text-accent"></i> Tambah Habitat Baru</h3>
        <form action="<?= url('admin/habitatStore') ?>" method="POST" class="mt-3">
            <?= csrfField() ?>

            <div class="form-group">
                <label for="nama_habitat" class="form-label">Nama Habitat <span class="text-danger">*</span></label>
                <input type="text" id="nama_habitat" name="nama_habitat" class="form-control" placeholder="Contoh: Rawa Gambut Tropis" required>
            </div>

            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi Lingkungan</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control" placeholder="Uraian kondisi iklim, vegetasi, dan karakteristik ekosistem..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Habitat
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Habitat -->
    <div class="admin-panel-card">
        <h3 class="panel-heading"><i class="fa-solid fa-tree text-accent"></i> Daftar Habitat Tersedia</h3>
        <div class="table-responsive mt-3">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tipe Habitat</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Satwa</th>
                        <th width="80" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($habitats as $hab): ?>
                        <tr>
                            <td><strong><?= e($hab['nama_habitat']) ?></strong></td>
                            <td><small class="text-muted"><?= e(limitWords($hab['deskripsi'] ?? '', 10)) ?></small></td>
                            <td class="text-center"><span class="badge badge-info"><?= (int)$hab['total_fauna'] ?> spesies</span></td>
                            <td class="text-center">
                                <a href="<?= url('admin/habitatDelete/' . $hab['id']) ?>" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus Habitat" onclick="return confirm('Hapus habitat <?= e($hab['nama_habitat']) ?>?');">
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


<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Akun Pengguna</h1>
        <p class="admin-page-subtitle">Daftar seluruh anggota dan kontrol hak akses pengguna (*Role-Based Access Control*).</p>
    </div>
</div>

<div class="admin-panel-card mt-4">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Pengguna</th>
                    <th>Alamat Email</th>
                    <th class="text-center">Hak Akses (Role)</th>
                    <th>Terdaftar Sejak</th>
                    <th width="160" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td>
                            <div class="table-item-flex">
                                <div class="table-avatar-mini">
                                    <?php if (!empty($u['foto_profil'])): ?>
                                        <img src="<?= uploadUrl($u['foto_profil']) ?>" alt="" class="table-thumb">
                                    <?php else: ?>
                                        <span class="avatar-initial-table"><?= strtoupper(substr($u['nama'], 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong><?= e($u['nama']) ?></strong>
                                    <?php if ((int)$u['id'] === (int)auth()['id']): ?>
                                        <span class="badge badge-secondary ml-1">(Anda)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?= e($u['email']) ?></td>
                        <td class="text-center">
                            <span class="badge-role <?= ($u['role'] === 'admin') ? 'badge-role-admin' : '' ?>">
                                <?= ($u['role'] === 'admin') ? 'Administrator' : 'Pengguna Biasa' ?>
                            </span>
                        </td>
                        <td><?= formatDateIndo($u['created_at']) ?></td>
                        <td class="text-center">
                            <?php if ((int)$u['id'] !== (int)auth()['id']): ?>
                                <div class="action-btn-group">
                                    <a href="<?= url('admin/userChangeRole/' . $u['id']) ?>" class="btn btn-sm btn-outline-primary" title="Ubah Hak Akses" onclick="return confirm('Ubah role pengguna <?= e($u['nama']) ?>?');">
                                        <i class="fa-solid fa-arrows-rotate"></i> <?= ($u['role'] === 'admin') ? 'Jadikan User' : 'Jadikan Admin' ?>
                                    </a>
                                    <a href="<?= url('admin/userDelete/' . $u['id']) ?>" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus Akun" onclick="return confirm('Apakah Anda yakin ingin menghapus akun <?= e($u['nama']) ?>?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted"><i class="fa-solid fa-lock"></i> Akun Anda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/admin_footer.php'; ?>


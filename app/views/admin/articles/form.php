<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <a href="<?= url('admin/articles') ?>" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Artikel</a>
        <h1 class="admin-page-title"><?= $isEdit ? 'Edit Artikel: ' . e($article['judul']) : 'Tulis Artikel Edukasi Baru' ?></h1>
        <p class="admin-page-subtitle">Sajikan wawasan konservasi satwa dan keanekaragaman alam nusantara.</p>
    </div>
</div>

<div class="admin-panel-card mt-4">
    <form action="<?= $isEdit ? url('admin/articleUpdate/' . $article['id']) : url('admin/articleStore') ?>" method="POST" enctype="multipart/form-data" class="admin-form">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
            <input type="text" id="judul" name="judul" value="<?= e($article['judul'] ?? '') ?>" class="form-control" placeholder="Contoh: Menjaga Benteng Terakhir Habitat Harimau Sumatera" required>
        </div>

        <div class="form-group">
            <label for="ringkasan" class="form-label">Ringkasan / Sinopsis Singkat</label>
            <textarea id="ringkasan" name="ringkasan" rows="2" class="form-control" placeholder="Tuliskan 1-2 kalimat pengantar yang memikat pembaca..."><?= e($article['ringkasan'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="konten" class="form-label">Isi Konten Artikel <span class="text-danger">*</span></label>
            <textarea id="konten" name="konten" rows="10" class="form-control font-monospace" placeholder="Tuliskan konten artikel secara lengkap. Mendukung paragraf HTML seperti <p>, <h3>, <strong>, <em>, dll..." required><?= e($article['konten'] ?? '') ?></textarea>
            <small class="form-hint">Mendukung format HTML standar untuk struktur teks (paragraf, subjudul, poin-poin).</small>
        </div>

        <div class="form-row">
            <!-- Thumbnail File -->
            <div class="form-group col-md-6">
                <label for="thumbnail_file" class="form-label">Unggah Foto Sampul</label>
                <input type="file" id="thumbnail_file" name="thumbnail_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="form-hint">Format JPG, PNG, atau WebP (maksimal 5MB).</small>
            </div>

            <!-- Thumbnail URL -->
            <div class="form-group col-md-6">
                <label for="thumbnail_url" class="form-label">Atau URL Gambar Sampul</label>
                <input type="url" id="thumbnail_url" name="thumbnail_url" value="<?= e($article['thumbnail'] ?? '') ?>" class="form-control" placeholder="https://images.unsplash.com/...">
            </div>
        </div>

        <div class="form-group col-md-4 mt-3">
            <label for="status" class="form-label">Status Publikasi</label>
            <select id="status" name="status" class="form-select">
                <option value="published" <?= (isset($article['status']) && $article['status'] === 'published') ? 'selected' : '' ?>>Terbitkan Sekarang (Published)</option>
                <option value="draft" <?= (isset($article['status']) && $article['status'] === 'draft') ? 'selected' : '' ?>>Simpan sebagai Draf (Draft)</option>
            </select>
        </div>

        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-paper-plane"></i> <?= $isEdit ? 'Perbarui Artikel' : 'Publikasikan Artikel' ?>
            </button>
            <a href="<?= url('admin/articles') ?>" class="btn btn-outline-secondary btn-lg">Batal</a>
        </div>
    </form>
</div>

<?php require_once APP_PATH . '/views/layouts/admin_footer.php'; ?>


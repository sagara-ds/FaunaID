<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <div>
        <a href="<?= url('admin/fauna') ?>" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Fauna</a>
        <h1 class="admin-page-title"><?= $isEdit ? 'Edit Spesies: ' . e($fauna['nama_lokal']) : 'Tambah Spesies Fauna Baru' ?></h1>
        <p class="admin-page-subtitle">Lengkapi informasi taksonomi, karakteristik fisik, dan persebaran wilayah satwa.</p>
    </div>
</div>

<div class="admin-panel-card mt-4">
    <form action="<?= $isEdit ? url('admin/faunaUpdate/' . $fauna['id']) : url('admin/faunaStore') ?>" method="POST" enctype="multipart/form-data" class="admin-form">
        <?= csrfField() ?>

        <div class="form-row">
            <!-- Nama Lokal -->
            <div class="form-group col-md-6">
                <label for="nama_lokal" class="form-label">Nama Lokal / Umum <span class="text-danger">*</span></label>
                <input type="text" id="nama_lokal" name="nama_lokal" value="<?= e($fauna['nama_lokal'] ?? '') ?>" class="form-control" placeholder="Contoh: Harimau Sumatera" required>
            </div>

            <!-- Nama Ilmiah -->
            <div class="form-group col-md-6">
                <label for="nama_ilmiah" class="form-label">Nama Ilmiah (Latin) <span class="text-danger">*</span></label>
                <input type="text" id="nama_ilmiah" name="nama_ilmiah" value="<?= e($fauna['nama_ilmiah'] ?? '') ?>" class="form-control" placeholder="Contoh: Panthera tigris sumatrae" required>
            </div>
        </div>

        <div class="form-row">
            <!-- Kategori -->
            <div class="form-group col-md-4">
                <label for="kategori_id" class="form-label">Kategori / Kelas <span class="text-danger">*</span></label>
                <select id="kategori_id" name="kategori_id" class="form-select" required>
                    <option value="">Pilih Kategori...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($fauna['kategori_id']) && $fauna['kategori_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= e($cat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Habitat -->
            <div class="form-group col-md-4">
                <label for="habitat_id" class="form-label">Tipe Habitat Alami <span class="text-danger">*</span></label>
                <select id="habitat_id" name="habitat_id" class="form-select" required>
                    <option value="">Pilih Habitat...</option>
                    <?php foreach ($habitats as $hab): ?>
                        <option value="<?= $hab['id'] ?>" <?= (isset($fauna['habitat_id']) && $fauna['habitat_id'] == $hab['id']) ? 'selected' : '' ?>>
                            <?= e($hab['nama_habitat']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Konservasi -->
            <div class="form-group col-md-4">
                <label for="status_konservasi" class="form-label">Status Konservasi IUCN <span class="text-danger">*</span></label>
                <select id="status_konservasi" name="status_konservasi" class="form-select" required>
                    <option value="">Pilih Status...</option>
                    <?php
                        $statuses = [
                            'Kritis (Critically Endangered)',
                            'Genting (Endangered)',
                            'Rentan (Vulnerable)',
                            'Hampir Terancam (Near Threatened)',
                            'Risiko Rendah (Least Concern)'
                        ];
                        foreach ($statuses as $st):
                    ?>
                        <option value="<?= $st ?>" <?= (isset($fauna['status_konservasi']) && $fauna['status_konservasi'] === $st) ? 'selected' : '' ?>>
                            <?= $st ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="form-group">
            <label for="deskripsi" class="form-label">Deskripsi Lengkap & Peran Ekologis <span class="text-danger">*</span></label>
            <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control" placeholder="Tuliskan ulasan mendalam mengenai spesies ini..." required><?= e($fauna['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <!-- Karakteristik Morfologi -->
            <div class="form-group col-md-6">
                <label for="karakteristik" class="form-label">Ciri Fisik & Karakteristik Khusus</label>
                <textarea id="karakteristik" name="karakteristik" rows="3" class="form-control" placeholder="Ukuran tubuh, warna bulu/sisik, senjata adaptasi..."><?= e($fauna['karakteristik'] ?? '') ?></textarea>
            </div>

            <!-- Makanan & Diet -->
            <div class="form-group col-md-6">
                <label for="makanan" class="form-label">Pola Pangan & Makanan</label>
                <textarea id="makanan" name="makanan" rows="3" class="form-control" placeholder="Karnivora/Herbivora/Omnivora dan jenis mangsa/tumbuhan..."><?= e($fauna['makanan'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-row">
            <!-- Estimasi Populasi -->
            <div class="form-group col-md-6">
                <label for="populasi_estimasi" class="form-label">Estimasi Populasi di Alam Bebas</label>
                <input type="text" id="populasi_estimasi" name="populasi_estimasi" value="<?= e($fauna['populasi_estimasi'] ?? '') ?>" class="form-control" placeholder="Contoh: Kurang dari 400 - 600 ekor di alam liar">
            </div>

            <!-- Is Featured Toggle -->
            <div class="form-group col-md-6 d-flex align-items-center mt-4">
                <div class="form-check-custom">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= (!empty($fauna['is_featured'])) ? 'checked' : '' ?>>
                    <label for="is_featured">Tampilkan sebagai Fauna Unggulan di Halaman Utama (Featured)</label>
                </div>
            </div>
        </div>

        <!-- Wilayah Persebaran (Many to Many) -->
        <div class="form-group mt-3">
            <label class="form-label">Wilayah Persebaran Biogeografi (Pilih Pulau / Kawasan)</label>
            <div class="region-checkbox-grid">
                <?php foreach ($regions as $reg): ?>
                    <?php 
                        $isChecked = $isEdit && isset($selectedRegions) && in_array($reg['id'], $selectedRegions);
                    ?>
                    <label class="region-check-card <?= $isChecked ? 'selected' : '' ?>">
                        <input type="checkbox" name="region_ids[]" value="<?= $reg['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                        <span class="region-check-name"><?= e($reg['nama_wilayah']) ?></span>
                        <span class="region-check-zone zone-pill-<?= strtolower($reg['zona']) ?>">Zona <?= e($reg['zona']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Media Gambar -->
        <hr class="form-divider my-4">
        <h3 class="form-subheading"><i class="fa-regular fa-image text-accent"></i> Gambar Spesies</h3>

        <?php if ($isEdit && !empty($fauna['primary_image'])): ?>
            <div class="current-image-preview mb-3">
                <label class="form-label">Foto Saat Ini:</label>
                <div class="preview-box">
                    <img src="<?= uploadUrl($fauna['primary_image']) ?>" alt="Preview">
                </div>
            </div>
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="image_file" class="form-label">Unggah File Foto (Komputer Lokal)</label>
                <input type="file" id="image_file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="form-hint">Format JPG, PNG, atau WebP (maksimal 5MB).</small>
            </div>

            <div class="form-group col-md-6">
                <label for="image_url" class="form-label">Atau Masukkan Tautan / URL Gambar</label>
                <input type="url" id="image_url" name="image_url" class="form-control" placeholder="https://images.unsplash.com/...">
                <small class="form-hint">Digunakan jika tidak mengunggah file lokal.</small>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-floppy-disk"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambahkan Spesies' ?>
            </button>
            <a href="<?= url('admin/fauna') ?>" class="btn btn-outline-secondary btn-lg">Batal</a>
        </div>
    </form>
</div>

<?php require_once APP_PATH . '/views/layouts/admin_footer.php'; ?>


</main>

<footer class="footer-wrapper">
    <div class="container footer-content">
        <div class="footer-grid">
            <!-- Brand & Mission -->
            <div class="footer-col footer-about">
                <div class="footer-brand">
                    <span class="brand-icon"><i class="fa-solid fa-seedling"></i></span>
                    <span class="brand-title">Fauna<span class="brand-accent">ID</span></span>
                </div>
                <p class="footer-desc">
                    Ensiklopedia digital dan media edukasi keanekaragaman fauna Indonesia. Berkomitmen menumbuhkan kesadaran kolektif dalam menjaga kelestarian satwa dan habitat nusantara.
                </p>
                <div class="footer-badges">
                    <span class="pill-badge"><i class="fa-solid fa-earth-asia"></i> 3 Zona Biogeografi</span>
                    <span class="pill-badge"><i class="fa-solid fa-shield-cat"></i> Konservasi Satwa</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="footer-col">
                <h4 class="footer-heading">Navigasi Utama</h4>
                <ul class="footer-links">
                    <li><a href="<?= url('') ?>">Beranda</a></li>
                    <li><a href="<?= url('fauna') ?>">Katalog Lengkap</a></li>
                    <li><a href="<?= url('map') ?>">Peta Interaktif 3 Zona</a></li>
                    <li><a href="<?= url('articles') ?>">Artikel & Jurnal Edukasi</a></li>
                    <?php if (!isLoggedIn()): ?>
                        <li><a href="<?= url('auth/login') ?>">Masuk Akun</a></li>
                        <li><a href="<?= url('auth/register') ?>">Daftar Anggota</a></li>
                    <?php else: ?>
                        <li><a href="<?= url('user/favorites') ?>">Koleksi Favorit</a></li>
                        <li><a href="<?= url('user/profile') ?>">Profil Akun</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- 3 Biogeographic Zones -->
            <div class="footer-col">
                <h4 class="footer-heading">Zona Persebaran</h4>
                <ul class="footer-links">
                    <li><a href="<?= url('fauna?zona=Asiatis') ?>"><span class="dot-indicator dot-asiatis"></span> Zona Asiatis (Paparan Sunda)</a></li>
                    <li><a href="<?= url('fauna?zona=Peralihan') ?>"><span class="dot-indicator dot-peralihan"></span> Zona Peralihan (Wallacea)</a></li>
                    <li><a href="<?= url('fauna?zona=Australis') ?>"><span class="dot-indicator dot-australis"></span> Zona Australis (Paparan Sahul)</a></li>
                </ul>
                <h4 class="footer-heading mt-4">Kategori Satwa</h4>
                <div class="category-tags">
                    <a href="<?= url('fauna?kategori=mamalia') ?>">Mamalia</a>
                    <a href="<?= url('fauna?kategori=burung') ?>">Aves</a>
                    <a href="<?= url('fauna?kategori=reptil') ?>">Reptil</a>
                    <a href="<?= url('fauna?kategori=biota-laut') ?>">Biota Laut</a>
                </div>
            </div>

            <!-- Conservation Commitment -->
            <div class="footer-col footer-pledge">
                <h4 class="footer-heading">Komitmen Pelestarian</h4>
                <p class="pledge-text">
                    "Setiap spesies memiliki peran krusial dalam menjaga keseimbangan rantai makanan dan ekosistem bumi nusantara."
                </p>
                <div class="status-summary-mini">
                    <div class="mini-status-item">
                        <span class="mini-indicator bg-critical"></span>
                        <span>Kritis (CR)</span>
                    </div>
                    <div class="mini-status-item">
                        <span class="mini-indicator bg-endangered"></span>
                        <span>Genting (EN)</span>
                    </div>
                    <div class="mini-status-item">
                        <span class="mini-indicator bg-vulnerable"></span>
                        <span>Rentan (VU)</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <strong>FaunaID</strong>. Platform Edukasi Keanekaragaman Satwa Nusantara.</p>
            <div class="footer-bottom-links">
                <span>Dibuat dengan PHP MVC Native & MySQL</span>
            </div>
        </div>
    </div>
</footer>

<!-- Core Script JS -->
<script src="<?= asset('js/main.js') ?>"></script>
<?php if (isset($extraScripts)): ?>
    <?= $extraScripts ?>
<?php endif; ?>

</body>
</html>


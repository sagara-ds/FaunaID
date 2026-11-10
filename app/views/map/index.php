<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= url('') ?>">Beranda</a> <i class="fa-solid fa-angle-right"></i>
            <span>Peta Persebaran Interaktif</span>
        </div>
        <h1 class="page-title">Peta Persebaran Biogeografi Fauna Nusantara</h1>
        <p class="page-desc">
            Pelajari garis demarkasi historis Alfred Russel Wallace dan Max Wilhelm Carl Weber yang membagi keanekaragaman hayati Indonesia menjadi tiga zona evolusi satwa.
        </p>
    </div>
</section>

<section class="section map-explorer-section">
    <div class="container">
        <!-- Zone Selector Navigation Tabs -->
        <div class="map-zone-tabs" id="zoneTabs">
            <button class="zone-tab-btn active" data-zone="all">
                <i class="fa-solid fa-layer-group"></i> Seluruh Nusantara
            </button>
            <button class="zone-tab-btn tab-asiatis" data-zone="Asiatis">
                <span class="zone-color-indicator bg-asiatis"></span> Zona Asiatis (Barat)
            </button>
            <button class="zone-tab-btn tab-peralihan" data-zone="Peralihan">
                <span class="zone-color-indicator bg-peralihan"></span> Zona Peralihan (Wallacea)
            </button>
            <button class="zone-tab-btn tab-australis" data-zone="Australis">
                <span class="zone-color-indicator bg-australis"></span> Zona Australis (Timur)
            </button>
        </div>

        <!-- Interactive Map Container Card -->
        <div class="interactive-map-card">
            <!-- Map Vector (SVG) -->
            <div class="svg-map-wrapper">
                <svg id="indonesiaMapSvg" viewBox="0 0 1000 480" xmlns="http://www.w3.org/2000/svg" class="indonesia-svg">
                    <defs>
                        <!-- Gradients for subtle depth -->
                        <linearGradient id="gradAsiatis" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#1b4d3e" />
                            <stop offset="100%" stop-color="#2a725d" />
                        </linearGradient>
                        <linearGradient id="gradPeralihan" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#d99b58" />
                            <stop offset="100%" stop-color="#ba752b" />
                        </linearGradient>
                        <linearGradient id="gradAustralis" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8c5835" />
                            <stop offset="100%" stop-color="#5a351d" />
                        </linearGradient>
                    </defs>

                    <!-- Lautan Latar Belakang -->
                    <rect width="1000" height="480" fill="#eaf2f1" rx="16"/>

                    <!-- Lines of Wallace & Weber -->
                    <!-- Garis Wallace (Antara Kalimantan/Bali dan Sulawesi/Lombok) -->
                    <path d="M 440 30 L 440 220 L 410 320 L 410 440" stroke="#c0392b" stroke-width="2.5" stroke-dasharray="6,4" fill="none" class="boundary-line wallace-line"/>
                    <text x="445" y="60" fill="#c0392b" font-size="11" font-weight="700">Garis Wallace</text>

                    <!-- Garis Weber (Antara Sulawesi/Nusa Tenggara dan Maluku/Papua) -->
                    <path d="M 680 30 L 670 170 L 640 280 L 630 440" stroke="#8e44ad" stroke-width="2.5" stroke-dasharray="6,4" fill="none" class="boundary-line weber-line"/>
                    <text x="685" y="60" fill="#8e44ad" font-size="11" font-weight="700">Garis Weber</text>

                    <!-- ============================================== -->
                    <!-- ZONA ASIATIS (SUMATRA, KALIMANTAN, JAWA, BALI) -->
                    <!-- ============================================== -->
                    
                    <!-- Pulau Sumatra -->
                    <g class="map-region-group zone-asiatis-group" data-region="Sumatra" data-zone="Asiatis">
                        <path d="M 90 90 L 160 130 L 220 220 L 250 280 L 230 300 L 180 250 L 110 160 L 70 100 Z" class="map-island island-sumatra" />
                        <text x="140" y="190" class="map-label">Sumatra</text>
                    </g>

                    <!-- Pulau Kalimantan -->
                    <g class="map-region-group zone-asiatis-group" data-region="Kalimantan" data-zone="Asiatis">
                        <path d="M 280 150 L 330 110 L 400 120 L 430 180 L 420 240 L 360 260 L 300 240 L 270 190 Z" class="map-island island-kalimantan" />
                        <text x="330" y="195" class="map-label">Kalimantan</text>
                    </g>

                    <!-- Pulau Jawa -->
                    <g class="map-region-group zone-asiatis-group" data-region="Jawa & Bali" data-zone="Asiatis">
                        <path d="M 240 330 L 380 340 L 390 355 L 245 350 Z" class="map-island island-jawa" />
                        <text x="290" y="342" class="map-label">Jawa</text>
                    </g>

                    <!-- Pulau Bali -->
                    <g class="map-region-group zone-asiatis-group" data-region="Jawa & Bali" data-zone="Asiatis">
                        <ellipse cx="400" cy="350" rx="10" ry="7" class="map-island island-bali" />
                        <text x="395" y="375" class="map-label label-small">Bali</text>
                    </g>

                    <!-- ============================================== -->
                    <!-- ZONA PERALIHAN (SULAWESI, NUSA TENGGARA, MALUKU) -->
                    <!-- ============================================== -->

                    <!-- Pulau Sulawesi (Bentuk Khas 4 Semenanjung) -->
                    <g class="map-region-group zone-peralihan-group" data-region="Sulawesi" data-zone="Peralihan">
                        <path d="M 480 140 L 530 135 L 560 170 L 520 185 L 500 215 L 540 235 L 535 255 L 495 240 L 480 270 L 465 240 L 475 190 Z" class="map-island island-sulawesi" />
                        <text x="485" y="210" class="map-label">Sulawesi</text>
                    </g>

                    <!-- Kepulauan Nusa Tenggara (Lombok, Sumbawa, Flores, Komodo, Timor) -->
                    <g class="map-region-group zone-peralihan-group" data-region="Kepulauan Nusa Tenggara" data-zone="Peralihan">
                        <path d="M 425 355 L 450 355 L 485 350 L 540 345 L 590 355 L 610 370 L 580 370 L 530 365 L 430 368 Z" class="map-island island-nustra" />
                        <text x="490" y="385" class="map-label">Nusa Tenggara</text>
                    </g>

                    <!-- Kepulauan Maluku -->
                    <g class="map-region-group zone-peralihan-group" data-region="Kepulauan Maluku" data-zone="Peralihan">
                        <path d="M 610 130 L 630 120 L 635 160 L 615 170 Z M 600 200 L 640 210 L 620 235 Z M 640 270 L 660 280 L 650 310 Z" class="map-island island-maluku" />
                        <text x="610" y="185" class="map-label">Maluku</text>
                    </g>

                    <!-- ============================================== -->
                    <!-- ZONA AUSTRALIS (PAPUA & PULAU SATELIT) -->
                    <!-- ============================================== -->

                    <!-- Pulau Papua (Burung Cenderawasih) -->
                    <g class="map-region-group zone-australis-group" data-region="Papua & Sekitarnya" data-zone="Australis">
                        <path d="M 700 190 L 730 170 L 760 195 L 820 190 L 920 220 L 930 310 L 880 320 L 810 290 L 750 250 L 710 220 Z" class="map-island island-papua" />
                        <text x="810" y="250" class="map-label">Papua</text>
                    </g>
                </svg>
            </div>

            <!-- Map Interactive Information Panel -->
            <div class="map-info-panel" id="mapInfoPanel">
                <div class="map-panel-header">
                    <span class="panel-zone-badge" id="panelZoneBadge">Seluruh Wilayah</span>
                    <h3 class="panel-zone-title" id="panelZoneTitle">Keanekaragaman Hayati Nusantara</h3>
                </div>
                <p class="panel-zone-desc" id="panelZoneDesc">
                    Klik salah satu pulau atau zona di atas untuk melihat karakteristik satwa, habitat khas, serta daftar hewan endemik yang mendiaminya.
                </p>
                <div class="panel-zone-stats" id="panelZoneStats">
                    <div class="p-stat">
                        <strong id="statSpesiesCount"><?= count($asiatisFauna) + count($peralihanFauna) + count($australisFauna) ?>+</strong>
                        <span>Spesies Terdata</span>
                    </div>
                    <div class="p-stat">
                        <strong id="statWilayahCount">7</strong>
                        <span>Pulau & Gugusan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Fauna List by Selected Zone -->
        <div class="zone-fauna-section mt-5">
            <div class="section-header-flex">
                <div>
                    <span class="section-subtitle">KATALOG WILAYAH</span>
                    <h2 class="section-title" id="zoneFaunaTitle">Fauna Khas Zona Terpilih</h2>
                    <p class="section-desc" id="zoneFaunaSubtitle">Daftar satwa yang mendiami kawasan biogeografi ini.</p>
                </div>
                <div class="zone-quick-count" id="zoneQuickCount">
                    Menampilkan <strong><?= count($asiatisFauna) + count($peralihanFauna) + count($australisFauna) ?></strong> satwa
                </div>
            </div>

            <!-- Fauna Grid dynamically populated or filtered -->
            <div class="fauna-grid" id="zoneFaunaGrid">
                <!-- Data Awal Digabung -->
                <?php 
                    $allZoneFauna = array_merge($asiatisFauna, $peralihanFauna, $australisFauna);
                    // Hilangkan duplikat ID jika ada
                    $uniqueFauna = [];
                    foreach ($allZoneFauna as $item) {
                        $uniqueFauna[$item['id']] = $item;
                    }
                ?>
                <?php foreach ($uniqueFauna as $f): ?>
                    <div class="fauna-card" data-zone-item="<?= e($f['zona'] ?? 'Asiatis') ?>">
                        <div class="fauna-img-wrap">
                            <img src="<?= uploadUrl($f['primary_image']) ?>" alt="<?= e($f['nama_lokal']) ?>" loading="lazy">
                            <span class="status-badge <?= getStatusBadgeClass($f['status_konservasi']) ?>">
                                <?= e($f['status_konservasi']) ?>
                            </span>
                        </div>
                        <div class="fauna-body">
                            <div class="fauna-meta">
                                <span class="meta-tag"><i class="fa-solid fa-paw"></i> <?= e($f['nama_kategori']) ?></span>
                                <span class="meta-tag"><i class="fa-solid fa-tree"></i> <?= e($f['nama_habitat']) ?></span>
                            </div>
                            <h3 class="fauna-title">
                                <a href="<?= url('fauna/detail/' . $f['id']) ?>"><?= e($f['nama_lokal']) ?></a>
                            </h3>
                            <p class="fauna-scientific"><em><?= e($f['nama_ilmiah']) ?></em></p>
                            <p class="fauna-excerpt"><?= e(limitWords($f['deskripsi'], 15)) ?></p>
                            
                            <div class="fauna-footer">
                                <span class="fauna-pop-est"><i class="fa-solid fa-location-dot"></i> Endemik Nusantara</span>
                                <a href="<?= url('fauna/detail/' . $f['id']) ?>" class="card-arrow" aria-label="Detail <?= e($f['nama_lokal']) ?>">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Include Interactive Map Logic -->
<script src="<?= asset('js/map.js') ?>"></script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


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
                    <path d="M 458 30 L 456 120 L 452 200 L 446 260 L 430 300 L 424 340 L 422 440" stroke="#c0392b" stroke-width="2.5" stroke-dasharray="6,4" fill="none" class="boundary-line wallace-line"/>
                    <text x="462" y="56" fill="#c0392b" font-size="11" font-weight="700">Garis Wallace</text>

                    <!-- Garis Weber (Antara Sulawesi/Maluku-Pasca dan Papua) -->
                    <path d="M 715 30 L 712 110 L 715 200 L 718 300 L 712 440" stroke="#8e44ad" stroke-width="2.5" stroke-dasharray="6,4" fill="none" class="boundary-line weber-line"/>
                    <text x="688" y="56" fill="#8e44ad" font-size="11" font-weight="700" text-anchor="end">Garis Weber</text>

                    <!-- ============================================== -->
                    <!-- ZONA ASIATIS (SUMATRA, KALIMANTAN, JAWA, BALI) -->
                    <!-- ============================================== -->

                    <!-- Pulau Sumatra + Bangka-Belitung + Mentawai -->
                    <g class="map-region-group zone-asiatis-group" data-region="Sumatra" data-zone="Asiatis">
                        <path d="M 62 86 L 84 64 L 112 72 L 136 98 L 168 132 L 204 172 L 232 214 L 254 252 L 262 278 L 252 296 L 230 298 L 208 276 L 180 242 L 148 204 L 116 166 L 88 130 L 66 106 Z" class="map-island island-sumatra"/>
                        <ellipse cx="210" cy="320" rx="14" ry="9" class="map-island"/>
                        <ellipse cx="178" cy="332" rx="9" ry="6" class="map-island"/>
                        <ellipse cx="44" cy="150" rx="4" ry="9" class="map-island"/>
                        <ellipse cx="36" cy="192" rx="4" ry="10" class="map-island"/>
                        <ellipse cx="28" cy="236" rx="3" ry="8" class="map-island"/>
                        <text x="128" y="182" class="map-label">Sumatra</text>
                    </g>

                    <!-- Pulau Kalimantan -->
                    <g class="map-region-group zone-asiatis-group" data-region="Kalimantan" data-zone="Asiatis">
                        <path d="M 300 118 L 330 104 L 366 106 L 396 118 L 426 136 L 442 166 L 436 202 L 420 232 L 394 254 L 358 264 L 328 258 L 304 244 L 287 224 L 274 194 L 270 164 L 279 136 Z" class="map-island island-kalimantan"/>
                        <text x="318" y="196" class="map-label">Kalimantan</text>
                    </g>

                    <!-- Pulau Jawa + Madura -->
                    <g class="map-region-group zone-asiatis-group" data-region="Jawa & Bali" data-zone="Asiatis">
                        <path d="M 150 348 L 172 341 L 204 343 L 236 341 L 268 340 L 300 339 L 332 339 L 356 342 L 376 348 L 384 358 L 370 364 L 338 362 L 300 360 L 258 360 L 218 361 L 178 363 L 156 359 Z" class="map-island island-jawa"/>
                        <ellipse cx="400" cy="354" rx="11" ry="5" class="map-island"/>
                        <text x="238" y="358" class="map-label label-small">Jawa</text>
                    </g>

                    <!-- Pulau Bali -->
                    <g class="map-region-group zone-asiatis-group" data-region="Jawa & Bali" data-zone="Asiatis">
                        <ellipse cx="412" cy="366" rx="9" ry="7" class="map-island island-bali"/>
                        <text x="400" y="390" class="map-label label-small">Bali</text>
                    </g>

                    <!-- ============================================== -->
                    <!-- ZONA PERALIHAN (SULAWESI, NUSA TENGGARA, MALUKU) -->
                    <!-- ============================================== -->

                    <!-- Pulau Sulawesi (Bentuk K khas 4 Semenanjung) -->
                    <g class="map-region-group zone-peralihan-group" data-region="Sulawesi" data-zone="Peralihan">
                        <path d="M 552 98 L 576 90 L 586 100 L 574 122 L 560 146 L 548 162 L 536 170 L 516 182 L 502 198 L 494 218 L 496 238 L 492 254 Q 484 274 496 292 L 510 306 L 518 302 L 514 286 L 508 268 L 508 250 L 514 234 L 526 228 L 540 236 L 550 258 Q 558 280 572 300 L 582 314 L 590 310 L 586 296 L 572 272 L 562 250 L 558 238 L 566 228 L 584 222 L 600 214 L 616 208 Q 626 202 620 192 L 606 190 L 590 198 L 574 206 L 566 192 L 558 168 L 556 146 L 552 122 Z" class="map-island island-sulawesi"/>
                        <text x="500" y="222" class="map-label">Sulawesi</text>
                    </g>

                    <!-- Kepulauan Nusa Tenggara (Lombok, Sumbawa, Komodo, Flores, Sumba, Timor) -->
                    <g class="map-region-group zone-peralihan-group" data-region="Kepulauan Nusa Tenggara" data-zone="Peralihan">
                        <ellipse cx="432" cy="366" rx="8" ry="6" class="map-island"/>
                        <path d="M 446 360 L 478 357 L 484 364 L 448 366 Z" class="map-island"/>
                        <ellipse cx="492" cy="361" rx="4" ry="3" class="map-island"/>
                        <path d="M 502 358 L 556 354 L 562 362 L 504 364 Z" class="map-island"/>
                        <path d="M 520 382 L 554 380 L 558 389 L 524 391 Z" class="map-island"/>
                        <path d="M 578 377 L 626 371 L 642 380 L 606 387 L 580 385 Z" class="map-island"/>
                        <text x="486" y="416" class="map-label label-small">Nusa Tenggara</text>
                    </g>

                    <!-- Kepulauan Maluku (Halmahera, Morotai, Seram, Buru) -->
                    <g class="map-region-group zone-peralihan-group" data-region="Kepulauan Maluku" data-zone="Peralihan">
                        <path d="M 660 118 L 674 108 L 680 122 L 668 130 L 676 140 L 690 134 L 702 138 L 700 150 L 688 150 L 678 158 L 668 152 Q 656 146 658 132 Z" class="map-island island-maluku"/>
                        <path d="M 656 96 L 666 92 L 664 104 L 654 104 Z" class="map-island"/>
                        <ellipse cx="688" cy="242" rx="21" ry="7" class="map-island"/>
                        <ellipse cx="644" cy="234" rx="12" ry="8" class="map-island"/>
                        <text x="628" y="200" class="map-label">Maluku</text>
                    </g>

                    <!-- ============================================== -->
                    <!-- ZONA AUSTRALIS (PAPUA & PULAU SATELIT) -->
                    <!-- ============================================== -->

                    <!-- Pulau Papua (kepala burung, teluk Cenderawasih, semenanjung tenggara) -->
                    <g class="map-region-group zone-australis-group" data-region="Papua & Sekitarnya" data-zone="Australis">
                        <path d="M 738 156 L 756 132 L 784 126 L 804 136 L 800 156 L 780 164 L 770 176 L 792 176 L 828 170 L 872 172 L 920 184 L 938 204 L 942 234 L 930 258 L 906 282 L 918 312 L 908 324 L 878 302 L 848 288 L 810 286 L 780 268 L 758 240 L 748 214 L 736 186 Z" class="map-island island-papua"/>
                        <ellipse cx="724" cy="122" rx="9" ry="5" class="map-island"/>
                        <ellipse cx="942" cy="308" rx="8" ry="4" class="map-island"/>
                        <text x="822" y="232" class="map-label">Papua</text>
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


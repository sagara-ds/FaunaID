/**
 * FaunaID - Interactive Map Explorer Script
 * 3 Biogeographic Zones: Asiatis, Peralihan, Australis
 */

document.addEventListener('DOMContentLoaded', () => {
    const zoneData = {
        all: {
            badge: 'Seluruh Nusantara',
            title: 'Keanekaragaman Hayati Tiga Zona Indonesia',
            desc: 'Dipisahkan oleh Garis Wallace dan Garis Weber, Indonesia memiliki perpaduan satwa tipe benua Asia di barat, tipe peralihan endemik purba di tengah, dan tipe benua Australia di timur.',
            spesies: '12+',
            wilayah: '7 Kawasan Utama'
        },
        Asiatis: {
            badge: 'Zona Asiatis (Paparan Sunda)',
            title: 'Karakteristik Fauna Indonesia Barat',
            desc: 'Meliputi pulau Sumatra, Jawa, Bali, dan Kalimantan. Dihuni oleh mamalia berukuran besar (harimau, gajah, badak), berbagai spesies kera dan orangutan, serta burung bersuara merdu dengan bulu tidak terlalu mencolok.',
            spesies: '6+',
            wilayah: 'Sumatra, Jawa, Bali, Kalimantan'
        },
        Peralihan: {
            badge: 'Zona Peralihan (Wallacea)',
            title: 'Karakteristik Fauna Kepulauan Wallacea',
            desc: 'Meliputi Sulawesi, Kepulauan Nusa Tenggara, dan Maluku. Karena tidak pernah terhubung langsung dengan daratan benua Asia maupun Australia, kawasan ini melahirkan satwa-satwa purba dan endemik murni yang tidak ditemukan di belahan bumi manapun.',
            spesies: '4+',
            wilayah: 'Sulawesi, Nusa Tenggara, Maluku'
        },
        Australis: {
            badge: 'Zona Australis (Paparan Sahul)',
            title: 'Karakteristik Fauna Indonesia Timur',
            desc: 'Meliputi Papua dan pulau-pulau di sekitarnya. Memiliki kemiripan evolusi dengan benua Australia: didominasi mamalia berkantung kecil, kuskus, serta burung-burung bermahkota bulu surga yang sangat memikat.',
            spesies: '3+',
            wilayah: 'Papua & Kepulauan Sekitarnya'
        }
    };

    const zoneTabs = document.querySelectorAll('.zone-tab-btn');
    const mapGroups = document.querySelectorAll('.map-region-group');
    const panelBadge = document.getElementById('panelZoneBadge');
    const panelTitle = document.getElementById('panelZoneTitle');
    const panelDesc = document.getElementById('panelZoneDesc');
    const statSpesies = document.getElementById('statSpesiesCount');
    const statWilayah = document.getElementById('statWilayahCount');
    const zoneFaunaTitle = document.getElementById('zoneFaunaTitle');
    const zoneFaunaSubtitle = document.getElementById('zoneFaunaSubtitle');
    const zoneQuickCount = document.getElementById('zoneQuickCount');
    const zoneFaunaGrid = document.getElementById('zoneFaunaGrid');

    // 1. Function to activate a zone
    function activateZone(zoneName, regionName = '') {
        // Update tab active state
        zoneTabs.forEach(tab => {
            if (tab.dataset.zone === zoneName) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });

        // Update SVG visual highlight
        mapGroups.forEach(group => {
            const gZone = group.dataset.zone;
            const gRegion = group.dataset.region;

            if (zoneName === 'all' || gZone === zoneName) {
                group.style.opacity = '1';
                if (regionName && gRegion === regionName) {
                    group.querySelector('.map-island').style.filter = 'brightness(1.4) drop-shadow(0 0 8px rgba(217, 155, 88, 0.8))';
                } else {
                    group.querySelector('.map-island').style.filter = 'none';
                }
            } else {
                group.style.opacity = '0.35';
                group.querySelector('.map-island').style.filter = 'none';
            }
        });

        // Update Info Panel
        const info = zoneData[zoneName] || zoneData.all;
        if (panelBadge) panelBadge.textContent = regionName ? `${info.badge} • ${regionName}` : info.badge;
        if (panelTitle) panelTitle.textContent = regionName ? `Wilayah ${regionName}` : info.title;
        if (panelDesc) panelDesc.textContent = info.desc;
        if (statSpesies) statSpesies.textContent = info.spesies;
        if (statWilayah) statWilayah.textContent = regionName ? '1' : (zoneName === 'all' ? '7' : info.wilayah.split(',').length);

        if (zoneFaunaTitle) {
            zoneFaunaTitle.textContent = zoneName === 'all' ? 'Fauna Seluruh Nusantara' : `Fauna Khas ${info.badge}`;
        }
        if (zoneFaunaSubtitle) {
            zoneFaunaSubtitle.textContent = `Daftar satwa endemik yang mendiami kawasan ${zoneName === 'all' ? 'Indonesia' : zoneName}.`;
        }

        // Fetch / Filter Cards
        loadFaunaByZone(zoneName);
    }

    // 2. Fetch or filter fauna cards
    function loadFaunaByZone(zoneName) {
        if (!zoneFaunaGrid) return;

        if (zoneName === 'all') {
            // Tampilkan semua card yang sudah di-render PHP
            const cards = zoneFaunaGrid.querySelectorAll('.fauna-card');
            cards.forEach(c => c.style.display = 'flex');
            if (zoneQuickCount) {
                zoneQuickCount.innerHTML = `Menampilkan <strong>${cards.length}</strong> satwa`;
            }
            return;
        }

        // Tampilkan indikator memuat
        zoneFaunaGrid.style.opacity = '0.5';

        fetch(`${window.BASE_URL}/api/faunaByZone?zona=${encodeURIComponent(zoneName)}`)
            .then(res => res.json())
            .then(data => {
                zoneFaunaGrid.style.opacity = '1';
                if (data.success && data.data.length > 0) {
                    if (zoneQuickCount) {
                        zoneQuickCount.innerHTML = `Menampilkan <strong>${data.total}</strong> satwa`;
                    }
                    zoneFaunaGrid.innerHTML = data.data.map(f => `
                        <div class="fauna-card">
                            <div class="fauna-img-wrap">
                                <img src="${f.primary_image}" alt="${f.nama_lokal}" loading="lazy">
                                <span class="status-badge ${f.badge_class}">
                                    ${f.status_konservasi}
                                </span>
                            </div>
                            <div class="fauna-body">
                                <div class="fauna-meta">
                                    <span class="meta-tag"><i class="fa-solid fa-paw"></i> ${f.kategori}</span>
                                    <span class="meta-tag"><i class="fa-solid fa-location-dot"></i> Zona ${zoneName}</span>
                                </div>
                                <h3 class="fauna-title">
                                    <a href="${f.url}">${f.nama_lokal}</a>
                                </h3>
                                <p class="fauna-scientific"><em>${f.nama_ilmiah}</em></p>
                                
                                <div class="fauna-footer">
                                    <span class="fauna-pop-est"><i class="fa-solid fa-earth-asia"></i> Endemik Kawasan</span>
                                    <a href="${f.url}" class="card-arrow" aria-label="Detail ${f.nama_lokal}">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    if (zoneQuickCount) zoneQuickCount.innerHTML = `0 satwa`;
                    zoneFaunaGrid.innerHTML = `
                        <div class="empty-state-card" style="grid-column: 1 / -1;">
                            <i class="fa-solid fa-tree fa-3x"></i>
                            <h3>Belum Ada Data Satwa untuk Zona Ini</h3>
                            <p>Data inventarisasi satwa sedang diperbarui oleh tim peneliti kami.</p>
                        </div>
                    `;
                }
            })
            .catch(err => {
                zoneFaunaGrid.style.opacity = '1';
                console.error('Error fetching zone fauna:', err);
            });
    }

    // 3. Bind click events on zone tabs
    zoneTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const zone = this.dataset.zone;
            activateZone(zone);
        });
    });

    // 4. Bind click events on SVG island elements
    mapGroups.forEach(group => {
        group.addEventListener('click', function() {
            const zone = this.dataset.zone;
            const region = this.dataset.region;
            activateZone(zone, region);
        });
    });

    // 5. Cek parameter URL ?zone=
    const urlParams = new URLSearchParams(window.location.search);
    const initialZone = urlParams.get('zone');
    if (initialZone && ['Asiatis', 'Peralihan', 'Australis'].includes(initialZone)) {
        activateZone(initialZone);
    }
});


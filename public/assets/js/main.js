/**
 * FaunaID - Main Frontend Script
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            navMenu.classList.toggle('show');
            const icon = mobileToggle.querySelector('i');
            if (navMenu.classList.contains('show')) {
                icon.className = 'fa-solid fa-xmark';
            } else {
                icon.className = 'fa-solid fa-bars';
            }
        });

        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                navMenu.classList.remove('show');
                const icon = mobileToggle.querySelector('i');
                if (icon) icon.className = 'fa-solid fa-bars';
            }
        });
    }

    // 2. User Menu Dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            userMenuBtn.setAttribute('aria-expanded', userDropdown.classList.contains('show'));
        });

        document.addEventListener('click', (e) => {
            if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                userDropdown.classList.remove('show');
                userMenuBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // 3. Live Search Autocomplete in Navbar
    const searchInput = document.getElementById('liveSearchInput');
    const searchResults = document.getElementById('liveSearchResults');
    let debounceTimer;

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.classList.remove('active');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`${window.BASE_URL}/api/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.data.length > 0) {
                            searchResults.innerHTML = data.data.map(item => `
                                <a href="${item.url}" class="live-search-item">
                                    <img src="${item.primary_image}" alt="" class="live-search-thumb">
                                    <div class="live-search-text">
                                        <strong>${item.nama_lokal}</strong>
                                        <small><em>${item.nama_ilmiah}</em> • ${item.kategori}</small>
                                    </div>
                                </a>
                            `).join('');
                            searchResults.classList.add('active');
                        } else {
                            searchResults.innerHTML = `<div class="p-3 text-muted text-center" style="font-size: 0.85rem;">Tidak ada spesies yang cocok.</div>`;
                            searchResults.classList.add('active');
                        }
                    })
                    .catch(() => {
                        searchResults.classList.remove('active');
                    });
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.classList.remove('active');
            }
        });
    }

    // 4. AJAX Toggle Favorite for Card Buttons & Detail Page Button
    function setupFavoriteButtons() {
        const favBtns = document.querySelectorAll('.fav-toggle-btn');
        favBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const faunaId = this.dataset.id;
                toggleFavorite(faunaId, this);
            });
        });

        const detailFavBtn = document.getElementById('btnFavoriteDetail');
        if (detailFavBtn) {
            detailFavBtn.addEventListener('click', function() {
                const faunaId = this.dataset.id;
                toggleFavorite(faunaId, this, true);
            });
        }
    }

    function toggleFavorite(faunaId, btnElement, isDetail = false) {
        fetch(`${window.BASE_URL}/api/favorite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ fauna_id: faunaId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (isDetail) {
                    const icon = btnElement.querySelector('i');
                    const text = document.getElementById('favText');
                    if (data.is_favorite) {
                        btnElement.classList.add('favorited');
                        icon.className = 'fa-solid fa-heart';
                        if (text) text.textContent = 'Tersimpan di Favorit';
                    } else {
                        btnElement.classList.remove('favorited');
                        icon.className = 'fa-regular fa-heart';
                        if (text) text.textContent = 'Tambah ke Favorit';
                    }
                } else {
                    const icon = btnElement.querySelector('i');
                    if (data.is_favorite) {
                        btnElement.classList.add('favorited');
                        icon.className = 'fa-solid fa-heart';
                    } else {
                        btnElement.classList.remove('favorited');
                        icon.className = 'fa-regular fa-heart';
                    }
                }
            } else if (data.need_login) {
                alert(data.message);
                window.location.href = `${window.BASE_URL}/auth/login`;
            }
        })
        .catch(err => {
            console.error('Error toggling favorite:', err);
        });
    }

    setupFavoriteButtons();

    // 5. Auto dismiss flash alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(al => {
                al.style.opacity = '0';
                al.style.transition = 'opacity 0.4s ease';
                setTimeout(() => al.remove(), 400);
            });
        }, 5000);
    }
});


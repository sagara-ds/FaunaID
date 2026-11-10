<header class="navbar-wrapper">
    <div class="container navbar-container">
        <!-- Brand Logo -->
        <a href="<?= url('') ?>" class="navbar-brand">
            <span class="brand-icon"><i class="fa-solid fa-seedling"></i></span>
            <div class="brand-text">
                <span class="brand-title">Fauna<span class="brand-accent">ID</span></span>
                <span class="brand-subtitle">Ensiklopedia Satwa Nusantara</span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="nav-menu" id="navMenu">
            <a href="<?= url('') ?>" class="nav-link <?= empty($_GET['url']) ? 'active' : '' ?>">Beranda</a>
            <a href="<?= url('fauna') ?>" class="nav-link <?= (str_starts_with($_GET['url'] ?? '', 'fauna')) ? 'active' : '' ?>">Katalog Fauna</a>
            <a href="<?= url('map') ?>" class="nav-link <?= (str_starts_with($_GET['url'] ?? '', 'map')) ? 'active' : '' ?>">Peta Persebaran</a>
            <a href="<?= url('articles') ?>" class="nav-link <?= (str_starts_with($_GET['url'] ?? '', 'articles')) ? 'active' : '' ?>">Artikel Edukasi</a>
        </nav>

        <!-- Navbar Actions (Search & Auth) -->
        <div class="navbar-actions">
            <!-- Quick Search Bar -->
            <div class="navbar-search">
                <form action="<?= url('fauna') ?>" method="GET" class="search-form-mini">
                    <input type="text" name="q" placeholder="Cari satwa nusantara..." value="<?= e($_GET['q'] ?? '') ?>" autocomplete="off" id="liveSearchInput">
                    <button type="submit" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <div class="live-search-results" id="liveSearchResults"></div>
            </div>

            <!-- User Auth Dropdown / Buttons -->
            <?php if (isLoggedIn()): ?>
                <div class="user-dropdown-wrapper">
                    <button class="user-btn" id="userMenuBtn" aria-expanded="false">
                        <div class="user-avatar-mini">
                            <?php if (!empty(auth()['foto_profil'])): ?>
                                <img src="<?= uploadUrl(auth()['foto_profil']) ?>" alt="<?= e(auth()['nama']) ?>">
                            <?php else: ?>
                                <span class="avatar-initial"><?= strtoupper(substr(auth()['nama'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="user-name"><?= e(explode(' ', auth()['nama'])[0]) ?></span>
                        <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="user-menu-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <strong><?= e(auth()['nama']) ?></strong>
                            <span class="badge-role <?= isAdmin() ? 'badge-role-admin' : '' ?>"><?= isAdmin() ? 'Administrator' : 'Pengguna' ?></span>
                        </div>
                        <hr class="dropdown-divider">
                        <?php if (isAdmin()): ?>
                            <a href="<?= url('admin') ?>" class="dropdown-item">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard Admin
                            </a>
                            <a href="<?= url('admin/fauna') ?>" class="dropdown-item">
                                <i class="fa-solid fa-paw"></i> Kelola Fauna
                            </a>
                            <hr class="dropdown-divider">
                        <?php endif; ?>
                        <a href="<?= url('user/favorites') ?>" class="dropdown-item">
                            <i class="fa-solid fa-heart"></i> Fauna Favorit
                        </a>
                        <a href="<?= url('user/profile') ?>" class="dropdown-item">
                            <i class="fa-solid fa-user-gear"></i> Pengaturan Profil
                        </a>
                        <hr class="dropdown-divider">
                        <a href="<?= url('auth/logout') ?>" class="dropdown-item text-danger">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="<?= url('auth/login') ?>" class="btn btn-outline-primary btn-sm">Masuk</a>
                    <a href="<?= url('auth/register') ?>" class="btn btn-primary btn-sm">Daftar</a>
                </div>
            <?php endif; ?>

            <!-- Mobile Menu Toggle Button -->
            <button class="mobile-toggle" id="mobileToggle" aria-label="Buka Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</header>


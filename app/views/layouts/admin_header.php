<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) : 'Dashboard Admin — FaunaID' ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Admin & Main CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <a href="<?= url('admin') ?>" class="admin-brand">
                <span class="brand-icon"><i class="fa-solid fa-leaf"></i></span>
                <span class="brand-text">Fauna<strong>Admin</strong></span>
            </a>
            <button class="sidebar-close-btn" id="sidebarCloseBtn"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <nav class="admin-nav">
            <div class="nav-section-label">UTAMA</div>
            <a href="<?= url('admin') ?>" class="admin-nav-item <?= empty($_GET['url']) || $_GET['url'] === 'admin' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>

            <div class="nav-section-label">DATA MASTER & FAUNA</div>
            <a href="<?= url('admin/fauna') ?>" class="admin-nav-item <?= str_starts_with($_GET['url'] ?? '', 'admin/fauna') ? 'active' : '' ?>">
                <i class="fa-solid fa-paw"></i> Data Fauna
            </a>
            <a href="<?= url('admin/categories') ?>" class="admin-nav-item <?= str_starts_with($_GET['url'] ?? '', 'admin/categories') ? 'active' : '' ?>">
                <i class="fa-solid fa-layer-group"></i> Kategori Fauna
            </a>
            <a href="<?= url('admin/habitats') ?>" class="admin-nav-item <?= str_starts_with($_GET['url'] ?? '', 'admin/habitats') ? 'active' : '' ?>">
                <i class="fa-solid fa-tree"></i> Tipe Habitat
            </a>

            <div class="nav-section-label">KONTEN & PENGGUNA</div>
            <a href="<?= url('admin/articles') ?>" class="admin-nav-item <?= str_starts_with($_GET['url'] ?? '', 'admin/articles') ? 'active' : '' ?>">
                <i class="fa-solid fa-newspaper"></i> Artikel Edukasi
            </a>
            <a href="<?= url('admin/users') ?>" class="admin-nav-item <?= str_starts_with($_GET['url'] ?? '', 'admin/users') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Pengguna Terdaftar
            </a>

            <div class="nav-section-label">SISTEM</div>
            <a href="<?= url('') ?>" target="_blank" class="admin-nav-item">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website Publik
            </a>
            <a href="<?= url('auth/logout') ?>" class="admin-nav-item text-danger">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar Sesi
            </a>
        </nav>
    </aside>

    <!-- Admin Main Container -->
    <div class="admin-main">
        <!-- Admin Topbar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="topbar-toggle" id="sidebarToggleBtn" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 class="topbar-title"><?= isset($title) ? e($title) : 'Dashboard Administrator' ?></h2>
            </div>
            <div class="topbar-right">
                <div class="admin-user-info">
                    <span class="user-greeting">Halo, <strong><?= e(explode(' ', auth()['nama'])[0]) ?></strong></span>
                    <div class="admin-avatar">
                        <?php if (!empty(auth()['foto_profil'])): ?>
                            <img src="<?= uploadUrl(auth()['foto_profil']) ?>" alt="<?= e(auth()['nama']) ?>">
                        <?php else: ?>
                            <span>A</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Message Alerts -->
        <div class="admin-flash-wrapper">
            <?php if (hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= e(getFlash('success')) ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            <?php if (hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= e(getFlash('error')) ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Content Area -->
        <div class="admin-content-area">


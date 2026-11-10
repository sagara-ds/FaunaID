<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) : 'FaunaID — Ensiklopedia Fauna Nusantara' ?></title>
    
    <!-- Meta SEO -->
    <meta name="description" content="FaunaID adalah platform ensiklopedia digital dan edukasi keanekaragaman fauna Indonesia terlengkap, peta persebaran interaktif, dan pelestarian satwa.">
    <meta name="theme-color" content="#173F35">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Core Style CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body>

<?php require_once APP_PATH . '/views/layouts/navbar.php'; ?>

<!-- Flash Messages -->
<div class="flash-container">
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
    <?php if (hasFlash('info')): ?>
        <div class="alert alert-info alert-dismissible">
            <i class="fa-solid fa-circle-info"></i>
            <span><?= e(getFlash('info')) ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
</div>

<main class="main-content">


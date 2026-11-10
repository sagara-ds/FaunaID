<?php
/**
 * Utility Helper Functions
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $path = ltrim($path, '/');
        // Mendukung mode pretty URL atau query param jika berjalan tanpa mod_rewrite
        if (defined('USE_QUERY_ROUTING') && USE_QUERY_ROUTING) {
            return empty($path) ? BASE_URL : BASE_URL . '/index.php?url=' . $path;
        }
        return empty($path) ? BASE_URL : BASE_URL . '/' . $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return BASE_URL . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('uploadUrl')) {
    function uploadUrl(?string $filename, string $fallback = 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1200&q=80'): string {
        if (empty($filename)) {
            return $fallback;
        }
        // Jika sudah berupa URL eksternal (Unsplash, dll)
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }
        $localPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($localPath)) {
            return BASE_URL . '/uploads/' . $filename;
        }
        return $fallback;
    }
}

if (!function_exists('e')) {
    function e(?string $str): string {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'item-' . time() : $text;
    }
}

if (!function_exists('setFlash')) {
    function setFlash(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }
}

if (!function_exists('getFlash')) {
    function getFlash(string $type): ?string {
        if (isset($_SESSION['flash'][$type])) {
            $msg = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $msg;
        }
        return null;
    }
}

if (!function_exists('hasFlash')) {
    function hasFlash(string $type): bool {
        return isset($_SESSION['flash'][$type]);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('auth')) {
    function auth(): ?array {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool {
        return isLoggedIn() && (($_SESSION['user']['role'] ?? '') === 'admin');
    }
}

if (!function_exists('csrfToken')) {
    function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrfField')) {
    function csrfField(): string {
        return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
    }
}

if (!function_exists('verifyCsrf')) {
    function verifyCsrf(): bool {
        $token = $_POST['csrf_token'] ?? '';
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}

if (!function_exists('getStatusBadgeClass')) {
    function getStatusBadgeClass(string $status): string {
        $statusLower = strtolower($status);
        if (str_contains($statusLower, 'kritis') || str_contains($statusLower, 'critically')) {
            return 'badge-critical';
        }
        if (str_contains($statusLower, 'genting') || str_contains($statusLower, 'endangered')) {
            return 'badge-endangered';
        }
        if (str_contains($statusLower, 'rentan') || str_contains($statusLower, 'vulnerable')) {
            return 'badge-vulnerable';
        }
        if (str_contains($statusLower, 'hampir') || str_contains($statusLower, 'near')) {
            return 'badge-near-threatened';
        }
        return 'badge-least-concern';
    }
}

if (!function_exists('formatDateIndo')) {
    function formatDateIndo(?string $datetime): string {
        if (empty($datetime)) return '-';
        $timestamp = strtotime($datetime);
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $tgl = date('j', $timestamp);
        $bln = $bulan[(int)date('n', $timestamp)];
        $thn = date('Y', $timestamp);
        return "{$tgl} {$bln} {$thn}";
    }
}

if (!function_exists('limitWords')) {
    function limitWords(string $text, int $limit = 25): string {
        $clean = strip_tags($text);
        $words = explode(' ', $clean);
        if (count($words) <= $limit) {
            return $clean;
        }
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
}


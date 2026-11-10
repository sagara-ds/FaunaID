<?php
/**
 * Application Router & Request Dispatcher
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

class App {
    protected string $controller = 'HomeController';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Tentukan Controller
        if (!empty($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // Cek kemungkinan bentuk tunggal (misal: articles -> ArticleController)
                $singularName = rtrim(ucfirst($url[0]), 's') . 'Controller';
                $singularFile = APP_PATH . '/controllers/' . $singularName . '.php';
                if (file_exists($singularFile)) {
                    $this->controller = $singularName;
                    unset($url[0]);
                }
            }
        }

        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        $controllerInstance = new $this->controller();

        // 2. Tentukan Method
        if (!empty($url[1])) {
            $candidateMethod = $url[1];
            // Format camelCase jika method mengandung tanda strip
            $candidateMethod = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $candidateMethod))));

            if (method_exists($controllerInstance, $candidateMethod)) {
                $this->method = $candidateMethod;
                unset($url[1]);
            }
        }

        // 3. Tentukan Parameter
        $this->params = $url ? array_values($url) : [];

        // 4. Eksekusi Controller & Method
        if (method_exists($controllerInstance, $this->method)) {
            call_user_func_array([$controllerInstance, $this->method], $this->params);
        } else {
            // Halaman 404 jika method tidak ditemukan
            http_response_code(404);
            $homeFile = APP_PATH . '/controllers/HomeController.php';
            require_once $homeFile;
            $fallback = new HomeController();
            $fallback->notFound();
        }
    }

    /**
     * Parsing URL dari request
     */
    protected function parseUrl(): array {
        // Ambil dari query param ?url=
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        // Fallback dari REQUEST_URI untuk routing tanpa .htaccess
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        
        // Hapus query string jika ada
        if (false !== ($pos = strpos($uri, '?'))) {
            $uri = substr($uri, 0, $pos);
        }

        $baseDir = dirname($script);
        if ($baseDir !== '/' && $baseDir !== '\\' && strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }

        $uri = trim($uri, '/');
        // Hapus index.php jika ada
        if (strpos($uri, 'index.php') === 0) {
            $uri = trim(substr($uri, 9), '/');
        }

        if (!empty($uri)) {
            return explode('/', filter_var($uri, FILTER_SANITIZE_URL));
        }

        return [];
    }
}

<?php
if (php_sapi_name() === 'cli-server') {
    $filePath = _DIR_ . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if (is_file($filePath)) {
        return false;
    }
}

require __DIR__ . '../../public/index.php';
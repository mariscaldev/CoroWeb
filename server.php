<?php

// Este archivo le dice a php -S qué hacer con las rutas
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false; // Deja que php -S sirva archivos existentes (CSS, JS, imágenes)
}

require_once __DIR__.'/public/index.php'; // Si no existe, pasa a Laravel

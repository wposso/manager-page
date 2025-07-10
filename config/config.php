<?php
// https://localhost/CLONE-PAGE/
define('BASE_URL', '/CLONE-PAGE/');
define('SESSION_LIFETIME', 3600); // 1 hora

session_set_cookie_params([
    'lifetime' => SESSION_LIFETIME,
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();

// Redirecciona al login si no está autenticado
function ensureLoggedIn()
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . 'views/loginview.php');
        exit;
    }
}

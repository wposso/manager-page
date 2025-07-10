<?php
class LogoutController
{
    public function __construct()
    {
        session_start();
    }

    public function logout()
    {
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Borrar la cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destruir la sesión
        session_destroy();

        // Redirigir al login
        header('Location: /login.php');
        exit();
    }
}

// Ejecutar el controlador
$logoutController = new LogoutController();
$logoutController->logout();
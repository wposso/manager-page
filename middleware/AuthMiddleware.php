<?php
require_once __DIR__ . '/../config/Security.php';

class AuthMiddleware
{
    // Tiempo de inactividad en segundos (30 minutos)
    const INACTIVITY_TIMEOUT = 1800;

    /**
     * Verifica si el usuario está autenticado
     * @throws Exception Si la sesión no es válida
     */
    public static function authenticate()
    {
        session_start();

        // 1. Verificar si hay una sesión activa
        if (!self::isUserLoggedIn()) {
            self::handleUnauthenticated();
        }

        // 2. Verificar validez de la sesión
        if (!self::isSessionValid()) {
            self::destroySession();
            throw new Exception("Sesión inválida detectada");
        }

        // 3. Verificar inactividad
        if (self::isSessionExpired()) {
            self::destroySession();
            header('Location: /login.php?timeout=1');
            exit();
        }

        // 4. Actualizar marca de tiempo de actividad
        self::updateLastActivity();

        // 5. Regenerar ID de sesión periódicamente
        if (self::shouldRegenerateSession()) {
            session_regenerate_id(true);
            $_SESSION['session_regen_time'] = time();
        }
    }

    /**
     * Verifica roles de usuario
     * @param array $allowedRoles Roles permitidos
     * @throws Exception Si el acceso no está autorizado
     */
    public static function authorize($allowedRoles = [])
    {
        if (!self::hasRequiredRole($allowedRoles)) {
            self::logAccessViolation();
            header('Location: /unauthorized.php');
            exit();
        }
    }

    /**
     * Manejo simplificado para proteger rutas
     */
    public static function protect($allowedRoles = [])
    {
        try {
            self::authenticate();
            self::authorize($allowedRoles);
        } catch (Exception $e) {
            error_log("Auth error: " . $e->getMessage());
            self::destroySession();
            header('Location: /login.php?error=1');
            exit();
        }
    }

    /* Métodos auxiliares */

    private static function isUserLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    private static function isSessionValid()
    {
        return isset($_SESSION['user_id'], $_SESSION['user_ip']) &&
            $_SESSION['user_ip'] === $_SERVER['REMOTE_ADDR'] &&
            $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT'];
    }

    private static function isSessionExpired()
    {
        return isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > self::INACTIVITY_TIMEOUT);
    }

    private static function updateLastActivity()
    {
        $_SESSION['last_activity'] = time();
    }

    private static function shouldRegenerateSession()
    {
        $regenInterval = 1800; // 30 minutos
        return !isset($_SESSION['session_regen_time']) ||
            (time() - $_SESSION['session_regen_time'] > $regenInterval);
    }

    private static function hasRequiredRole($allowedRoles)
    {
        return empty($allowedRoles) ||
            (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $allowedRoles));
    }

    private static function handleUnauthenticated()
    {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: /login.php');
        exit();
    }

    private static function logAccessViolation()
    {
        $userId = $_SESSION['user_id'] ?? 'unknown';
        $attemptedUrl = $_SERVER['REQUEST_URI'];
        $message = sprintf(
            "Intento de acceso no autorizado. Usuario ID: %s, Rol: %s, URL: %s",
            $userId,
            $_SESSION['user_role'] ?? 'none',
            $attemptedUrl
        );
        error_log($message);
    }

    public static function destroySession()
    {
        $_SESSION = [];
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
        session_destroy();
    }
}
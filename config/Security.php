<?php
class Security
{
    // Configuración de seguridad
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOGIN_ATTEMPT_WINDOW = 3600; // 1 hora en segundos
    const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;
    const PASSWORD_OPTIONS = ['cost' => 12];
    const CSRF_TOKEN_LIFETIME = 3600; // 1 hora

    /**
     * Hashea una contraseña usando el algoritmo configurado
     */
    public static function hashPassword($password)
    {
        if (empty($password)) {
            throw new InvalidArgumentException("La contraseña no puede estar vacía");
        }
        return password_hash($password, self::PASSWORD_ALGORITHM, self::PASSWORD_OPTIONS);
    }

    /**
     * Verifica si una contraseña necesita ser rehasheada
     */
    public static function needsRehash($hash)
    {
        return password_needs_rehash($hash, self::PASSWORD_ALGORITHM, self::PASSWORD_OPTIONS);
    }

    /**
     * Genera y almacena un token CSRF
     */
    public static function generateCSRFToken($identifier = 'default')
    {
        if (empty($_SESSION['csrf_tokens'][$identifier])) {
            $_SESSION['csrf_tokens'][$identifier] = [
                'token' => bin2hex(random_bytes(32)),
                'created_at' => time()
            ];
        }
        return $_SESSION['csrf_tokens'][$identifier]['token'];
    }

    /**
     * Verifica un token CSRF
     */
    public static function verifyCSRFToken($token, $identifier = 'default')
    {
        if (empty($_SESSION['csrf_tokens'][$identifier])) {
            return false;
        }

        $storedToken = $_SESSION['csrf_tokens'][$identifier];

        // Eliminar token después de verificación (single-use)
        unset($_SESSION['csrf_tokens'][$identifier]);

        // Verificar coincidencia y vigencia
        return hash_equals($storedToken['token'], $token) &&
            (time() - $storedToken['created_at'] < self::CSRF_TOKEN_LIFETIME);
    }

    /**
     * Limpia y sanitiza datos de entrada
     */
    public static function sanitizeInput($input, $type = 'string')
    {
        if (is_array($input)) {
            return array_map(function ($item) use ($type) {
                return self::sanitizeInput($item, $type);
            }, $input);
        }

        switch ($type) {
            case 'email':
                return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'url':
                return filter_var(trim($input), FILTER_SANITIZE_URL);
            case 'string':
            default:
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * Genera un token seguro para recuperación de contraseña
     */
    public static function generatePasswordResetToken()
    {
        return bin2hex(random_bytes(32)) . time();
    }

    /**
     * Valida un token de recuperación de contraseña
     */
    public static function validatePasswordResetToken($token)
    {
        if (strlen($token) < 64)
            return false;

        $timestamp = substr($token, 64);
        if (!is_numeric($timestamp))
            return false;

        // El token es válido por 24 horas
        return (time() - $timestamp) < 86400;
    }

    /**
     * Prevención básica contra XSS
     */
    public static function xssClean($data)
    {
        if (is_array($data)) {
            return array_map([self, 'xssClean'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Headers de seguridad para respuestas HTTP
     */
    public static function setSecurityHeaders()
    {
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; img-src 'self' data:;");
    }

    /**
     * Protección contra clickjacking
     */
    public static function preventClickjacking()
    {
        header('X-Frame-Options: DENY');
    }

    /**
     * Limpia el buffer de salida y aplica filtros XSS
     */
    public static function cleanOutput($output)
    {
        return self::xssClean($output);
    }
}
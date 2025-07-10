<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../config/Security.php';

class LoginController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
        session_start();
    }
    
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $this->showLoginForm();
        }
    }
    
    private function processLogin() {
        $email = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_EMAIL);
        $password = $_POST['clave'] ?? '';
        
        // Validación básica
        if (empty($email) || empty($password)) {
            $this->redirectWithError('Usuario y contraseña son requeridos');
            return;
        }
        
        // Protección contra fuerza bruta
        $failedAttempts = $this->userModel->getFailedAttempts($email);
        if ($failedAttempts >= Security::MAX_LOGIN_ATTEMPTS) {
            $this->redirectWithError('Demasiados intentos fallidos. Por favor intente más tarde.');
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && $this->verifyPassword($password, $user['contraseña'])) {
            $this->loginSuccess($user);
        } else {
            $this->loginFailed($email);
        }
    }
    
    private function verifyPassword($inputPassword, $hashedPassword) {
        return password_verify($inputPassword, $hashedPassword);
    }
    
    private function loginSuccess($user) {
        // Registrar intento exitoso
        $this->userModel->recordLoginAttempt($user['id'], true);
        
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['correo'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_role'] = $user['rol'];
        $_SESSION['logged_in'] = true;
        
        // Regenerar ID de sesión para prevenir fijación
        session_regenerate_id(true);
        
        // Redirigir al dashboard
        header('Location: /dashboard.php');
        exit();
    }
    
    private function loginFailed($email) {
        // Registrar intento fallido
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            $this->userModel->recordLoginAttempt($user['id'], false);
        }
        
        $this->redirectWithError('Credenciales inválidas');
    }
    
    private function redirectWithError($message) {
        $_SESSION['login_error'] = $message;
        header('Location: /login.php');
        exit();
    }
    
    private function showLoginForm() {
        // Mostrar el formulario de login (tu HTML actual)
        require_once __DIR__ . '/../view/auth/login.php';
    }
}

// Ejecutar el controlador
$loginController = new LoginController();
$loginController->handleLogin();
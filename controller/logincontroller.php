<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/dbconnection.php';
require_once __DIR__ . '/../functions/Security.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    // Validación de CSRF
    if (!Security::verifyCSRFToken($csrf_token)) {
        echo "<script>alert('Token CSRF inválido.'); window.location.href = '../views/loginview.php';</script>";
        exit();
    }

    // Conexión y consulta
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT id, nombre, correo, contraseña, rol, activo FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($clave, $user['contraseña'])) {
        if ((int) $user['activo'] !== 1) {
            echo "<script>alert('Usuario inactivo.'); window.location.href = '../views/loginview.php';</script>";
            exit;
        }

        // Establecer sesión
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['correo'] = $user['correo'];
        $_SESSION['rol'] = $user['rol']; // admin, operador, etc.

        header("Location: ../server.php");
        exit;
    } else {
        echo "<script>alert('Credenciales inválidas.'); window.location.href = '../views/loginview.php';</script>";
        exit;
    }
}

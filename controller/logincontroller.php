<?php
require_once __DIR__ . '/../database/dbconnection.php';
require_once __DIR__ . '/../functions/Security.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!Security::verifyCSRFToken($csrf_token)) {
        die("Token CSRF inválido.");
    }

    $conn = db_connect();
    $stmt = $conn->prepare("SELECT id, nombre, correo, contraseña, rol, activo FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($clave, $user['contraseña'])) {
        if (!$user['activo']) {
            echo "<script>alert('Usuario inactivo'); window.location.href = '../views/loginview.php';</script>";
            exit();
        }

        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];

        header("Location: ../server.php");
        exit();
    } else {
        echo "<script>alert('Credenciales inválidas'); window.location.href = '../views/loginview.php';</script>";
        exit();
    }
}

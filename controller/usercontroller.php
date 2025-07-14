<?php
require_once __DIR__ . '/../functions/getusers.php';
require_once __DIR__ . '/../database/dbconnection.php';

function getUsers()
{
    return getAllUsersFromDb();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $accion = $_POST['action'] ?? '';

    if ($accion === 'add') {
        $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
        $correo = $conn->real_escape_string($_POST['email'] ?? '');
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        $rol = $conn->real_escape_string($_POST['rol'] ?? '');

        $stmt = $conn->prepare("INSERT INTO usuario (nombre, correo, contraseña, rol, activo, creado_en, actualizado_en) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
        $stmt->bind_param("ssss", $nombre, $correo, $password, $rol);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Usuario registrado correctamente." : "Error al registrar usuario.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Administrador';
        </script>";
        exit;
    }

    if ($accion === 'delete') {
        $correo = $conn->real_escape_string($_POST['email'] ?? '');

        $stmt = $conn->prepare("DELETE FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Usuario eliminado correctamente." : "Error al eliminar usuario.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Administrador';
        </script>";
        exit;
    }
}

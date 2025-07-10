<?php
require_once __DIR__ . '/../database/dbconnection.php';

function validarCredenciales($correo, $clave)
{
    $conn = db_connect();

    $stmt = $conn->prepare("
        SELECT id, nombre, correo, contraseña, rol 
        FROM usuario 
        WHERE correo = ? AND activo = 1
    ");
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($clave, $row['contraseña'])) {
            unset($row['contraseña']);
            return $row;
        }
    }
    return false;
}

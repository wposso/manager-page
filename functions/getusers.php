<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllUsersFromDb()
{
    $conn = db_connect();
    $usuarios = [];

    $query = "SELECT id, nombre, correo as email, rol, creado_en, actualizado_en FROM usuario";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    return $usuarios;
}

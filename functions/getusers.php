<?php
require_once __DIR__ . "/../database/dbconnection.php";
function getAllUsersFromDb()
{
    $conn = db_connect();

    if ($conn->connect_error) {
        return die("Error al conectarse a la base de datos");
    }

    $response = $conn->query("SELECT id, rol, nombre, email, creado_en, actualizado_en password FROM usuarios");
    $users = [];

    while ($row = $response->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}
?>
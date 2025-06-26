<?php
require_once __DIR__ . "/../database/dbconnection.php";

function getAllSuppliersFromDb()
{
    $conn = db_connect();
    if ($conn->connect_error) {
        return die("Error en la conexión");
    }

    $response = $conn->query("SELECT id, nombre, contacto, telefono, correo, direccion, estado FROM proveedores");

    if (!$response) {
        return die("Error en consulta");
    }

    $suppliers = [];
    while ($row = $response->fetch_assoc()) {
        $suppliers[] = $row;
    }

    return $suppliers;
}
?>
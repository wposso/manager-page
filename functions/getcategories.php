<?php
require_once __DIR__ . "/../database/dbconnection.php";

function getAllCategoriesFromDb()
{
    $conn = db_connect();

    if ($conn->connect_error) {
        return die("Conexión interrumpida");
    }

    $categoria = [];
    $response = $conn->query("SELECT id, nombre, descripcion, estado FROM categorias");

    while ($row = $response->fetch_assoc()) {
        $categoria[] = $row;
    }

    return $categoria;
}
?>
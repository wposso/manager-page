<?php
require_once __DIR__ . "/../database/dbconnection.php";

function getAllNewsFromDb()
{
    $conn = db_connect();
    if ($conn->connect_error) {
        return die("Error en conexión" . $conn->connect_error);
    }

    $query = $conn->query("SELECT * FROM novedades");
    if ($query == null) {
        return die("Error en la consulta");
    }
    $news = [];

    while ($row = $query->fetch_assoc()) {
        $news[] = $row;
    }

    return $news;
}
?>
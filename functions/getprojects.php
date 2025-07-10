<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllProjectsFromDb()
{
    $conn = db_connect();
    $result = $conn->query("SELECT id, nombre, ubicacion FROM proyecto ORDER BY id DESC");

    $projects = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    $conn->close();
    return $projects;
}

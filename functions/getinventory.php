<?php
require_once __DIR__ . "/../database/dbconnection.php";

function getAllUsersFromDb()
{
    $conn = db_connect();

    $response = $conn->query("SELECT * FROM productos");
    $users = [];

    while ($row = $response->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}
?>
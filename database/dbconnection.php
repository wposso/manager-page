<?php
function db_connect()
{
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "db_produccion";

    $conn = new mysqli(
        $hostname,
        $username,
        $password,
        $database
    );

    if ($conn->connect_error) {
        die("Has an error ocurred" . $conn->connect_error);
    }

    return $conn;
}
?>
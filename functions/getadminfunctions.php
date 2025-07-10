<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getUsers()
{
    $conn = db_connect();
    $stmt = $conn->query("SELECT id, nombre, correo as email, rol, creado_en, actualizado_en FROM usuario");
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function getProjects()
{
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM proyecto ORDER BY nombre");
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function getWarehouses()
{
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM bodega ORDER BY nombre");
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function getLogs()
{
    $conn = db_connect();
    $stmt = $conn->query("SELECT usuario, accion, fecha FROM logs ORDER BY fecha DESC LIMIT 50");
    return $stmt->fetch_all(MYSQLI_ASSOC);
}
?>
<?php
require_once __DIR__ . '/../database/dbconnection.php';

function logAction($usuario_id, $accion)
{
    if (!$usuario_id || !$accion)
        return;

    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO log (usuario_id, accion, fecha) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $usuario_id, $accion);
    $stmt->execute();
    $stmt->close();
}

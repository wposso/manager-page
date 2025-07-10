<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getLogs() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT usuario_id, accion, fecha FROM logs ORDER BY fecha DESC LIMIT 50");
    return $stmt->fetch_all(MYSQLI_ASSOC);
}
?>
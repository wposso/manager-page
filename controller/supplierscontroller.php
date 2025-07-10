<?php
require_once __DIR__ . '/../database/dbconnection.php';

function handlesuppliers()
{
    $conn = db_connect();
    $query = "SELECT id, nombre, contacto, telefono, correo FROM proveedor ORDER BY id DESC";
    $result = $conn->query($query);

    $proveedores = [];
    while ($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
    }

    $conn->close();
    return $proveedores;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $conn = db_connect();

    if ($accion === 'agregar') {
        $nombre = $_POST['nombre'] ?? '';
        $contacto = $_POST['contacto'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $nit = $_POST['nit'] ?? '';
        $direccion = $_POST['direccion'] ?? '';

        $stmt = $conn->prepare("INSERT INTO proveedor (nombre, contacto, telefono, correo, nit, direccion, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssss", $nombre, $contacto, $telefono, $correo, $nit, $direccion);

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? 'Proveedor registrado exitosamente' : 'Error al registrar proveedor';
        echo "<script>
            window.location.href = '../server.php#Proveedores::" . urlencode($msg) . "';
        </script>";
        exit;
    }

    if ($accion === 'eliminar') {
        $identificador = $_POST['identificador'];

        if (is_numeric($identificador)) {
            $stmt = $conn->prepare("DELETE FROM proveedor WHERE id = ?");
            $stmt->bind_param("i", $identificador);
        } else {
            $stmt = $conn->prepare("DELETE FROM proveedor WHERE nit = ?");
            $stmt->bind_param("s", $identificador);
        }

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? 'Proveedor eliminado exitosamente' : 'Error al eliminar proveedor';
        echo "<script>
            window.location.href = '../server.php#Proveedores::" . urlencode($msg) . "';
        </script>";
        exit;
    }
}

<?php
require_once __DIR__ . '/../database/dbconnection.php';

function obtenerProveedores()
{
    $conn = db_connect();
    $stmt = $conn->query("SELECT id, nit, nombre, contacto, telefono, correo FROM proveedor ORDER BY id DESC");
    $proveedores = [];

    while ($row = $stmt->fetch_assoc()) {
        $proveedores[] = $row;
    }

    return $proveedores;
}

function agregarProveedor($data)
{
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO proveedor (nombre, contacto, telefono, correo, nit, direccion, creado_en, actualizado_en)
                            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");

    $stmt->bind_param(
        "ssssss",
        $data['nombre'],
        $data['contacto'],
        $data['telefono'],
        $data['correo'],
        $data['nit'],
        $data['direccion']
    );

    if ($stmt->execute()) {
        return "Proveedor agregado exitosamente";
    } else {
        return "Error al agregar proveedor";
    }
}

function eliminarProveedor($identificador)
{
    $conn = db_connect();
    if (is_numeric($identificador)) {
        $stmt = $conn->prepare("DELETE FROM proveedor WHERE id = ?");
        $stmt->bind_param("i", $identificador);
    } else {
        $stmt = $conn->prepare("DELETE FROM proveedor WHERE nit = ?");
        $stmt->bind_param("s", $identificador);
    }

    if ($stmt->execute()) {
        return "Proveedor eliminado correctamente";
    } else {
        return "Error al eliminar proveedor";
    }
}

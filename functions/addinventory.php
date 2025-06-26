<?php

require_once __DIR__ . "/../database/dbconnection.php";

function addToInventory($name, $category, $price, $unit, $stock, $description)
{
    if (!$name || !$category || !$price || !$unit || !$stock || !$description) {
        return [
            "success" => false,
            "message" => "Todos los datos son obligatorios"
        ];
    }

    $conn = db_connect();

    try {
        $sql = "INSERT INTO productos 
                (nombre, categoria, precio, unidad, stock, descripcion) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return [
                "success" => false,
                "message" => "Error en la preparación: " . $conn->error
            ];
        }

        $stmt->bind_param("ssdsis", $name, $category, $price, $unit, $stock, $description);

        $stmt->execute();

        return [
            "success" => true,
            "message" => "Producto añadido exitosamente al inventario"
        ];

    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => "Error al añadir producto: " . $e->getMessage()
        ];
    }
}

?>
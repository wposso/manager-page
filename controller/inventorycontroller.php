<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/dbconnection.php';
require_once __DIR__ . '/../functions/getinventory.php';

function handleGetInventoryByBodega($bodega_id)
{
    return getProductsByBodega($bodega_id);
}

function handleGetInventoryByProject($proyecto_id)
{
    return getProductsByProject($proyecto_id);
}

function handleGetInventory()
{
    return getAllProducts();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $action = $_POST['action'] ?? '';

    // === AGREGAR PRODUCTO ===
    if ($action === 'add') {
        $codigo = $conn->real_escape_string($_POST['codigo']);
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $cantidad = intval($_POST['cantidad']);
        $categoria_id = intval($_POST['categoria_id']);
        $subcategoria_id = intval($_POST['subcategoria_id']);
        $proveedor_id = intval($_POST['proveedor_id']);
        $estado = intval($_POST['estado']);
        $proyecto_id = $_POST['proyecto_id'] !== '' ? intval($_POST['proyecto_id']) : null;
        $bodega_id = $_POST['bodega_id'] !== '' ? intval($_POST['bodega_id']) : null;

        $stmt = $conn->prepare("INSERT INTO inventario 
            (codigo, nombre, cantidad, categoria_id, subcategoria_id, proveedor_id, proyecto_id, bodega_id, estado, creado_en, actualizado_en)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

        $stmt->bind_param(
            "ssiiiiiii",
            $codigo,
            $nombre,
            $cantidad,
            $categoria_id,
            $subcategoria_id,
            $proveedor_id,
            $proyecto_id,
            $bodega_id,
            $estado
        );

        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Producto registrado correctamente." : "No se pudo registrar el producto.";
        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Inventario';
        </script>";
        exit;
    }

    // === ELIMINAR PRODUCTO ===
    if ($action === 'delete') {
        $codigo = $conn->real_escape_string($_POST['codigo']);
        $stmt = $conn->prepare("DELETE FROM inventario WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Producto eliminado correctamente." : "No se pudo eliminar el producto.";
        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Inventario';
        </script>";
        exit;
    }

    // === TRANSFERIR PRODUCTO ===
    if ($action === 'transfer') {
        $producto_id = intval($_POST['producto_id']);
        $cantidad = intval($_POST['cantidad']);
        $motivo = $conn->real_escape_string($_POST['motivo']);
        list($origen_tipo, $origen_id) = explode('_', $_POST['origen_tipo_id']);
        list($destino_tipo, $destino_id) = explode('_', $_POST['destino_tipo_id']);

        // Validar existencia
        $check = $conn->prepare("SELECT cantidad FROM inventario WHERE id = ?");
        $check->bind_param("i", $producto_id);
        $check->execute();
        $res = $check->get_result();
        $producto = $res->fetch_assoc();
        $check->close();

        if (!$producto || $producto['cantidad'] < $cantidad) {
            echo "<script>
                alert('Cantidad no disponible para la transferencia.');
                window.location.href = '../server.php#Inventario';
            </script>";
            exit;
        }

        // Descontar cantidad del origen
        $update = $conn->prepare("UPDATE inventario SET cantidad = cantidad - ? WHERE id = ?");
        $update->bind_param("ii", $cantidad, $producto_id);
        $update->execute();
        $update->close();

        $productoInfo = getProductById($producto_id);

        // Verificar existencia en destino
        $query = "SELECT id FROM inventario 
                  WHERE codigo = ? AND nombre = ? AND categoria_id = ? AND subcategoria_id = ? 
                        AND proveedor_id = ? AND estado = ? AND " .
            ($destino_tipo === 'proyecto' ? "proyecto_id = ?" : "bodega_id = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssiiiis",
            $productoInfo['codigo'],
            $productoInfo['nombre'],
            $productoInfo['categoria_id'],
            $productoInfo['subcategoria_id'],
            $productoInfo['proveedor_id'],
            $productoInfo['estado'],
            $destino_id
        );
        $stmt->execute();
        $res = $stmt->get_result();
        $existe = $res->fetch_assoc();
        $stmt->close();

        if ($existe) {
            $upd = $conn->prepare("UPDATE inventario SET cantidad = cantidad + ?, actualizado_en = NOW() WHERE id = ?");
            $upd->bind_param("ii", $cantidad, $existe['id']);
            $upd->execute();
            $upd->close();
        } else {
            $destino_proyecto_id = $destino_tipo === 'proyecto' ? $destino_id : null;
            $destino_bodega_id = $destino_tipo === 'bodega' ? $destino_id : null;

            $insert = $conn->prepare("INSERT INTO inventario 
                (codigo, nombre, cantidad, categoria_id, subcategoria_id, proveedor_id, estado, proyecto_id, bodega_id, creado_en, actualizado_en)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

            $insert->bind_param(
                "ssiiiiiii",
                $productoInfo['codigo'],
                $productoInfo['nombre'],
                $cantidad,
                $productoInfo['categoria_id'],
                $productoInfo['subcategoria_id'],
                $productoInfo['proveedor_id'],
                $productoInfo['estado'],
                $destino_proyecto_id,
                $destino_bodega_id
            );
            $insert->execute();
            $insert->close();
        }

        // Registrar movimiento
        $usuario = $_SESSION['usuario_id'] ?? null;
        $origen_bodega_id = $origen_tipo === 'bodega' ? intval($origen_id) : null;
        $origen_proyecto_id = $origen_tipo === 'proyecto' ? intval($origen_id) : null;
        $destino_bodega_id = $destino_tipo === 'bodega' ? intval($destino_id) : null;
        $destino_proyecto_id = $destino_tipo === 'proyecto' ? intval($destino_id) : null;

        $stmt = $conn->prepare("INSERT INTO movimiento (
            tipo, producto_id, cantidad, bodega_origen_id, proyecto_origen_id,
            bodega_destino_id, proyecto_destino_id, motivo, usuario_responsable, fecha
        ) VALUES (
            'transferencia', ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )");

        $stmt->bind_param(
            "iiiiiiis",
            $producto_id,
            $cantidad,
            $origen_bodega_id,
            $origen_proyecto_id,
            $destino_bodega_id,
            $destino_proyecto_id,
            $motivo,
            $usuario
        );

        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "<script>
            alert('Transferencia realizada correctamente.');
            window.location.href = '../server.php#Inventario';
        </script>";
        exit;
    }
}

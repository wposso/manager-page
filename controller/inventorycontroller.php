<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/getinventory.php';
require_once __DIR__ . '/../database/dbconnection.php';

$products = getAllProducts();
$categorias = getCategorias();
$subcategorias = getSubcategorias();
$proveedores = getProveedores();
$bodegas = getBodegas();

function handleGetInventory()
{
    global $products;
    return $products;
}

function handleGetInventoryByProject($proyecto_id)
{
    return getProductsByProject($proyecto_id);
}

function handleGetInventoryByBodega($bodega_id)
{
    return getProductsByBodega($bodega_id);
}

function getCatalogosForSelects()
{
    global $categorias, $subcategorias, $proveedores, $bodegas;
    return compact('categorias', 'subcategorias', 'proveedores', 'bodegas');
}

function eliminarProductoSiCantidadCero($conn, $producto_id, $bodega_id)
{
    $stmt = $conn->prepare("SELECT cantidad FROM inventario WHERE id = ? AND bodega_id = ?");
    $stmt->bind_param("ii", $producto_id, $bodega_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && $result['cantidad'] <= 0) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM movimiento WHERE producto_id = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res['total'] == 0) {
            $stmt = $conn->prepare("DELETE FROM inventario WHERE id = ? AND bodega_id = ?");
            $stmt->bind_param("ii", $producto_id, $bodega_id);
            $stmt->execute();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'delete') {
        $redirect_to = $_POST['redirect_to'] ?? '../../server.php#Inventario';
    }

    if ($action === 'add') {
        $data = [
            'codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'cantidad' => $_POST['cantidad'],
            'categoria_id' => $_POST['categoria_id'],
            'subcategoria_id' => $_POST['subcategoria_id'],
            'proveedor_id' => $_POST['proveedor_id'],
            'bodega_id' => $_POST['bodega_id'],
            'estado' => $_POST['estado']
        ];

        $msg = $data ? 'Producto registrado exitosamente' : 'Error al registrar el producto';
        echo "<script>alert('$msg'); window.location.href = '{$redirect_to}';</script>";
        exit;
    }

    if ($action === 'delete') {
        $codigo = $_POST['codigo'];
        $success = deleteProduct($codigo);
        $msg = $success ? 'Producto eliminado exitosamente' : 'Error al eliminar el producto';
        echo "<script>alert('$msg'); window.location.href = '{$redirect_to}';</script>";
        exit;
    }

    if ($action === 'transfer') {
        $producto_id = $_POST['producto_id'];
        $origen = $_POST['bodega_origen_id'];
        $destino = $_POST['bodega_destino_id'];
        $cantidad = (int) $_POST['cantidad'];
        $motivo = $_POST['motivo'];
        $usuario = $_SESSION['nombre'] ?? 'Sistema';

        $vista_origen = isset($_POST['proyecto_id']) ? 'proyecto' : 'bodega';
        $id_origen = $_POST[$vista_origen . '_id'];

        if ($origen === $destino) {
            echo "<script>alert('La bodega origen y destino no pueden ser la misma.'); 
                window.location.href = '../server.php#Transferencia::{$vista_origen}::{$id_origen}';</script>";
            exit;
        }

        $conn = db_connect();
        $conn->begin_transaction();

        try {
            // Verificar disponibilidad
            $stmt = $conn->prepare("SELECT cantidad, codigo FROM inventario WHERE id = ? AND bodega_id = ?");
            $stmt->bind_param("ii", $producto_id, $origen);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if (!$result || $result['cantidad'] < $cantidad) {
                throw new Exception("Cantidad insuficiente en la bodega origen.");
            }

            $codigo = $result['codigo'];

            // Descontar en origen
            $stmt = $conn->prepare("UPDATE inventario SET cantidad = cantidad - ? WHERE id = ? AND bodega_id = ?");
            $stmt->bind_param("iii", $cantidad, $producto_id, $origen);
            $stmt->execute();

            // Registrar el movimiento ANTES de eliminar
            $stmt = $conn->prepare("INSERT INTO movimiento (tipo, producto_id, cantidad, bodega_origen_id, bodega_destino_id, motivo, usuario_responsable, fecha) VALUES ('TRASLADO', ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiiiss", $producto_id, $cantidad, $origen, $destino, $motivo, $usuario);
            $stmt->execute();

            // Eliminar si es necesario
            eliminarProductoSiCantidadCero($conn, $producto_id, $origen);

            // Revisar si ya existe en destino
            $stmt = $conn->prepare("SELECT id FROM inventario WHERE codigo = ? AND bodega_id = ?");
            $stmt->bind_param("si", $codigo, $destino);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                // Sumar si existe
                $stmt = $conn->prepare("UPDATE inventario SET cantidad = cantidad + ? WHERE codigo = ? AND bodega_id = ?");
                $stmt->bind_param("isi", $cantidad, $codigo, $destino);
                $stmt->execute();
            } else {
                // Clonar si no existe
                $stmt = $conn->prepare("SELECT nombre, categoria_id, subcategoria_id, proveedor_id, estado FROM inventario WHERE codigo = ? AND bodega_id = ?");
                $stmt->bind_param("si", $codigo, $origen);
                $stmt->execute();
                $producto = $stmt->get_result()->fetch_assoc();

                if (!$producto) {
                    throw new Exception("No se encontró el producto original para clonar.");
                }

                $stmt = $conn->prepare("INSERT INTO inventario (codigo, nombre, cantidad, categoria_id, subcategoria_id, proveedor_id, bodega_id, estado, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $stmt->bind_param("ssiiiiii", $codigo, $producto['nombre'], $cantidad, $producto['categoria_id'], $producto['subcategoria_id'], $producto['proveedor_id'], $destino, $producto['estado']);
                $stmt->execute();
            }

            $conn->commit();

            // Alerta con hash para mostrar en frontend sin pantalla en blanco
            $msg = json_encode([
                "title" => "Transferencia exitosa",
                "message" => "El inventario fue transferido correctamente",
                "type" => "success"
            ]);

            echo "<script>
                const msg = encodeURIComponent('$msg');
                window.location.href = '../server.php#Transferencia::{$vista_origen}::{$id_origen}::' + msg;
            </script>";
        } catch (Exception $e) {
            $conn->rollback();
            $error = addslashes($e->getMessage());
            echo "<script>alert('Error en la transferencia: {$error}'); 
                window.location.href = '../server.php#Transferencia::{$vista_origen}::{$id_origen}';</script>";
        }
        exit;
    }

    if ($action === 'update_quantity') {
        $producto_id = $_POST['producto_id'];
        $bodega_id = $_POST['bodega_id'];
        $nueva_cantidad = (int) $_POST['nueva_cantidad'];

        $conn = db_connect();
        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("UPDATE inventario SET cantidad = ? WHERE id = ? AND bodega_id = ?");
            $stmt->bind_param("iii", $nueva_cantidad, $producto_id, $bodega_id);
            $stmt->execute();

            eliminarProductoSiCantidadCero($conn, $producto_id, $bodega_id);

            $conn->commit();
            echo "<script>alert('Cantidad actualizada correctamente'); window.location.href = '../../server.php#Inventario';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            $msg = addslashes($e->getMessage());
            echo "<script>alert('Error al actualizar cantidad: {$msg}'); window.location.href = '../../server.php#Inventario';</script>";
        }
        exit;
    }
}

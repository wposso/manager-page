<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

require_once __DIR__ . "/../controller/movementscontroller.php";
$movimientos = handlemovements();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Movimientos</title>
    <link rel="stylesheet" href="./css/movements.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <h2>Movimientos</h2>

    <div class="m_buttons">
        <input type="search" name="searchbar" placeholder="Buscar movimientos...">
        <input type="button" value="Exportar PDF">
        <input type="button" value="Exportar CSV">
        <input type="button" value="Imprimir">
    </div>

    <div class="m_table_container">
        <table class="m_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m["id"]) ?></td>
                        <td><?= ucfirst(strtolower($m["tipo"])) ?></td>
                        <td><?= htmlspecialchars($m["producto"]) ?></td>
                        <td><?= htmlspecialchars($m["cantidad"]) ?></td>
                        <td><?= htmlspecialchars($m["responsable_nombre"]) ?></td>
                        <td><?= htmlspecialchars($m["fecha"]) ?></td>
                        <td>
                            <?php
                            $observaciones = [];

                            if (!empty($m["bodega_origen"])) {
                                $observaciones[] = "Origen: " . htmlspecialchars($m["bodega_origen"]);
                            } elseif (!empty($m["proyecto_origen"])) {
                                $observaciones[] = "Origen: " . htmlspecialchars($m["proyecto_origen"]);
                            }

                            if (!empty($m["bodega_destino"])) {
                                $observaciones[] = "Destino: " . htmlspecialchars($m["bodega_destino"]);
                            } elseif (!empty($m["proyecto_destino"])) {
                                $observaciones[] = "Destino: " . htmlspecialchars($m["proyecto_destino"]);
                            }

                            if (!empty($m["motivo"])) {
                                $observaciones[] = "Motivo: " . htmlspecialchars($m["motivo"]);
                            }

                            echo implode("<br>", $observaciones);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
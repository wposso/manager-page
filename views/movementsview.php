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
                    <th>Fecha</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?= $m["id"] ?></td>
                        <td><?= ucfirst(strtolower($m["tipo"])) ?></td>
                        <td><?= $m["producto"] ?></td>
                        <td><?= $m["cantidad"] ?></td>
                        <td><?= $m["fecha"] ?></td>
                        <td>
                            <?php
                            $info = [];
                            if ($m["bodega_origen"])
                                $info[] = "Origen: " . $m["bodega_origen"];
                            if ($m["bodega_destino"])
                                $info[] = "Destino: " . $m["bodega_destino"];
                            if ($m["motivo"])
                                $info[] = "Motivo: " . $m["motivo"];
                            echo implode("<br>", $info);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</body>

</html>
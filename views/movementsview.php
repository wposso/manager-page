<?php require_once __DIR__ . "/../controller/movementscontroller.php";
$x = handlemovements(); ?>
<html>
<link rel="stylesheet" href="./css/movements.css">
<h2>Movimientos</h2>
<div class="m_buttons">
    <input type="search" name="searchbar" id="" placeholder="Buscar Movimientos...">
    <input type="button" value="Exportar PDF">
    <input type="button" value="Exportar CVS">
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
                <th>Observacion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($x as $a): ?>
                <tr>
                    <td><?= $a["id"] ?></td>
                    <td><?= $a["tipo"] ?></td>
                    <td><?= $a["producto"] ?></td>
                    <td><?= $a["cantidad"] ?></td>
                    <td><?= $a["fecha"] ?></td>
                    <td><?= $a["observacion"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</html>
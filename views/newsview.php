<?php require_once __DIR__ . "/../controller/newscontroller.php";
$x = handlegetnews(); ?>
<html>
<link rel="stylesheet" href="./css/news.css">
<h2>Novedades</h2>

<div class="n_buttons">
    <input type="search" name="search" id="" placeholder="Buscar Registro...">
    <div class="x">
        <input type="button" value="Opciones">
    </div>
    <input type="button" value="Exportar PDF">
</div>

<div class="n_table_container">
    <table class="n_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Descripcion</th>
                <th>Fecha</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($x as $k): ?>
                <tr>
                    <td><?= $k["id"] ?></td>
                    <td><?= $k["titulo"] ?></td>
                    <td><?= $k["descripcion"] ?></td>
                    <td><?= $k["fecha"] ?></td>
                    <td><?= $k["tipo"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</html>
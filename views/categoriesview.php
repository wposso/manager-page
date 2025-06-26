<?php
require_once __DIR__ . "/../controller/categoriescontroller.php";
$response = handlecategories();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link rel="stylesheet" href="./css/categories.css">
</head>

<body>

    <h2>Categorías</h2>

    <div class="buttons">
        <input type="search" name="searchbar" placeholder="Buscar categoría...">
        <div class="x">
            <input type="button" value="Opciones">
        </div>
        <input type="button" value="Exportar PDF">
    </div>

    <div class="c_table_container">
        <table class="c_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($response as $x): ?>
                    <tr>
                        <td><?= $x["id"] ?></td>
                        <td><?= $x["nombre"] ?></td>
                        <td><?= $x["descripcion"] ?></td>
                        <td><?= $x["estado"] ? 'Activo' : 'Inactivo' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
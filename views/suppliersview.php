<?php
require_once __DIR__ . "/../controller/supplierscontroller.php";
$response = handlesuppliers();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>
    <link rel="stylesheet" href="./css/suppliers.css">
</head>

<body>

    <h2>Proveedores</h2>

    <div class="s_buttons">
        <input type="search" name="searchbar" placeholder="Buscar proveedor...">
        <div class="x">
            <input type="button" value="Opciones">
        </div>
        <input type="button" value="Exportar PDF">
    </div>

    <div class="s_table_container">
        <table class="s_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($response as $p): ?>
                    <tr>
                        <td><?= $p["id"] ?></td>
                        <td><?= $p["nombre"] ?></td>
                        <td><?= $p["contacto"] ?></td>
                        <td><?= $p["telefono"] ?></td>
                        <td><?= $p["correo"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
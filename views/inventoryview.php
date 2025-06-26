<?php
require_once __DIR__ . "/../controller/inventorycontroller.php";
$users = getAllProducts();
?>

<h2>Inventario General</h2>
<div class="buttons">
    <input type="search" name="search" placeholder="Buscar producto...">
    <div class="dropdown">
        <button class="dropdown-button">Opciones</button>
        <div class="dropdown-content">
            <a href="#" id="openModalBtn">Añadir registro</a>
            <a href="#">Editar registro</a>
            <a href="#">Eliminar registro</a>
        </div>
    </div>
    <input type="button" value="Exportar PDF">
    <input type="button" value="Exportar CSV">
    <input type="button" value="Imprimir">
</div>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>id</th>
                <th>nombre</th>
                <th>categoria</th>
                <th>precio</th>
                <th>unidad</th>
                <th>stock</th>
                <th>descripcion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['nombre'] ?></td>
                    <td><?= $user['categoria'] ?></td>
                    <td><?= $user['precio'] ?></td>
                    <td><?= $user['unidad'] ?></td>
                    <td><?= $user['stock'] ?></td>
                    <td><?= $user['descripcion'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
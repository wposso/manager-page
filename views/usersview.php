<?php
require_once __DIR__ . "/../controller/userscontroller.php";
$usuarios = getUsers();
?>

<?php
require_once __DIR__ . "/../controller/userscontroller.php";
$usuarios = getUsers();
?>

<link rel="stylesheet" href="./css/users.css">

<h2 class="admin-heading">Panel de Administración de Usuarios</h2>

<div class="row">
    <div class="searchbar">
        <input type="search" name="search" id="" placeholder="Buscar usuario...">
    </div>
    <div class="buttons">
        <input type="button" value="Añadir">
        <input type="button" value="Eliminar">
    </div>
</div>

<table class="u-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Rol</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Creado</th>
            <th>Actualizado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario["id"] ?></td>
                <td><?= ucfirst($usuario["rol"]) ?></td>
                <td><?= $usuario["nombre"] ?></td>
                <td><?= $usuario["email"] ?></td>
                <td><?= $usuario["creado_en"] ?></td>
                <td><?= $usuario["actualizado_en"] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
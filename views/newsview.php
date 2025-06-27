<?php require_once __DIR__ . "/../controller/newscontroller.php";
$x = handlegetnews(); ?>
<html>
<link rel="stylesheet" href="./css/news.css">
<style>
    .n_dropdown {
        position: relative;
        display: inline-block;
    }

    .n_dropdown_content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 180px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 2;
        border-radius: 5px;
    }

    .n_dropdown_content button {
        width: 100%;
        padding: 10px;
        background: white;
        border: none;
        text-align: left;
        cursor: pointer;
    }

    .n_dropdown_content button:hover {
        background-color: #f1f1f1;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 10;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        width: 400px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .modal-content input,
    .modal-content select {
        width: 100%;
        padding: 8px;
        margin: 5px 0 10px 0;
    }

    .modal-content button[type="submit"] {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        margin-right: 10px;
        cursor: pointer;
    }

    .modal-content button[type="button"] {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<h2>Novedades</h2>

<div class="n_buttons">
    <input type="search" name="search" placeholder="Buscar Registro...">
    <div class="n_dropdown">
        <input type="button" value="Opciones" onclick="toggleDropdown()">
        <div class="n_dropdown_content" id="dropdownMenu">
            <button onclick="openModal('registerModal')">Registrar novedad</button>
            <button onclick="openModal('deleteModal')">Eliminar novedad</button>
        </div>
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

<!-- Modal Registrar -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <h3>Registrar Novedad</h3>
        <form action="../controller/newscontroller.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="titulo" placeholder="Título" required>
            <input type="text" name="descripcion" placeholder="Descripción" required>
            <input type="date" name="fecha" required>
            <select name="tipo" required>
                <option value="">Seleccione tipo</option>
                <option value="Urgente">Urgente</option>
                <option value="General">General</option>
            </select>
            <button type="submit">Guardar</button>
            <button type="button" onclick="closeModal('registerModal')">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Eliminar Novedad</h3>
        <form action="../controller/newscontroller.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="number" name="id" placeholder="ID de la novedad" required>
            <button type="submit">Eliminar</button>
            <button type="button" onclick="closeModal('deleteModal')">Cancelar</button>
        </form>
    </div>
</div>

</html>
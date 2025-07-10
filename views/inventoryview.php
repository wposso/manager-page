<?php
require_once __DIR__ . "/../controller/inventorycontroller.php";
$products = handleGetInventory();
$catalogos = getCatalogosForSelects();
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}
?>
<link rel="stylesheet" href="./css/inventory.css">

<h2>Inventario General</h2>

<div class="i_buttons">
    <input type="search" name="search" placeholder="Buscar producto...">

    <div class="dropdown">
        <button class="dropdown-button">Opciones</button>
        <div class="dropdown-content">
            <button type="button" onclick="openModal('addModal')">Añadir registro</button>
            <button type="button" onclick="openModal('deleteModal')">Eliminar registro</button>
        </div>
    </div>

    <input type="button" value="Exportar PDF">
    <input type="button" value="Exportar CSV">
    <input type="button" value="Imprimir">
</div>

<div class="i_table_container">
    <table class="i_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Proveedor</th>
                <th>Bodega</th>
                <th>Cantidad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['codigo'] ?></td>
                    <td><?= $p['nombre'] ?></td>
                    <td><?= $p['categoria'] ?></td>
                    <td><?= $p['subcategoria'] ?></td>
                    <td><?= $p['proveedor'] ?></td>
                    <td><?= $p['bodega'] ?></td>
                    <td><?= $p['cantidad'] ?></td>
                    <td><?= $p['estado'] ? 'Activo' : 'Inactivo' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Añadir -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addModal')">&times;</span>
        <div class="form-container">
            <h2>Añadir Producto</h2>
            <form method="post" action="./controller/inventorycontroller.php">
                <input type="hidden" name="action" value="add">

                <input type="text" name="codigo" placeholder="Código del producto" required>
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <input type="number" name="cantidad" placeholder="Cantidad" required>

                <select name="categoria_id" required>
                    <option value="" disabled selected hidden>Categoría</option>
                    <?php foreach ($catalogos['categorias'] as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="subcategoria_id" required>
                    <option value="" disabled selected hidden>Subcategoría</option>
                    <?php foreach ($catalogos['subcategorias'] as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="proveedor_id" required>
                    <option value="" disabled selected hidden>Proveedor</option>
                    <?php foreach ($catalogos['proveedores'] as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="bodega_id" required>
                    <option value="" disabled selected hidden>Bodega</option>
                    <?php foreach ($catalogos['bodegas'] as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= $b['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="estado" required>
                    <option value="" disabled selected hidden>Estado</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>

                <div class="form-actions">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="closeModal('addModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <div class="form-container">
            <h2>Eliminar Producto</h2>
            <form method="post" action="./controller/inventorycontroller.php">
                <input type="hidden" name="action" value="delete">
                <input type="text" name="codigo" placeholder="Código del producto" required>
                <div class="form-actions">
                    <button type="submit" class="delete">Eliminar</button>
                    <button type="button" onclick="closeModal('deleteModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function (e) {
        ['addModal', 'deleteModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    }
</script>
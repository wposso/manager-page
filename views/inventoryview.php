<?php
require_once __DIR__ . "/../controller/inventorycontroller.php";
$users = getAllProducts();
?>
<link rel="stylesheet" href="./css/inventory.css">

<h2>Inventario General</h2>

<div class="i_buttons">
    <input type="search" name="search" placeholder="Buscar producto...">
    <div class="dropdown">
        <button class="dropdown-button">Opciones</button>
        <div class="dropdown-content">
            <a href="#" onclick="openModal('addModal')">Añadir registro</a>
            <!-- <a href="#" onclick="openModal('editModal')">Editar registro</a> -->
            <a href="#" onclick="openModal('deleteModal')">Eliminar registro</a>
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
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Unidad</th>
                <th>Stock</th>
                <th>Descripción</th>
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
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addModal')">&times;</span>
        <div class="form-container">
            <h2>Añadir Producto</h2>
            <form id="add-inventory-form" method="post">
                <input type="text" name="codigo" placeholder="Código del producto" required>
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <input type="number" name="cantidad" placeholder="Cantidad" required>

                <select name="categoria_id" required>
                    <option value="" disabled selected hidden>Categoría</option>
                </select>

                <select name="subcategoria_id" required>
                    <option value="" disabled selected hidden>Subcategoría</option>
                </select>

                <select name="proveedor_id" required>
                    <option value="" disabled selected hidden>Proveedor</option>
                </select>

                <select name="bodega_id" required>
                    <option value="" disabled selected hidden>Bodega</option>
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

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <div class="form-container">
            <h2>Editar Producto</h2>
            <form id="edit-inventory-form" method="post">
                <input type="text" name="codigo" placeholder="Código del producto" required>
                <input type="text" name="nombre" placeholder="Nuevo nombre">
                <input type="number" name="cantidad" placeholder="Nueva cantidad">

                <select name="categoria_id">
                    <option value="" disabled selected hidden>Categoría</option>
                </select>

                <select name="subcategoria_id">
                    <option value="" disabled selected hidden>Subcategoría</option>
                </select>

                <select name="proveedor_id">
                    <option value="" disabled selected hidden>Proveedor</option>
                </select>

                <select name="bodega_id">
                    <option value="" disabled selected hidden>Bodega</option>
                </select>

                <select name="estado">
                    <option value="" disabled selected hidden>Estado</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>

                <div class="form-actions">
                    <button type="submit">Actualizar</button>
                    <button type="button" onclick="closeModal('editModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <div class="form-container">
            <h2>Eliminar Producto</h2>
            <form id="delete-inventory-form" method="post">
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
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function (e) {
        ['addModal', 'editModal', 'deleteModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    }

    function toggleDropdown() {
        const dropdowns = document.querySelectorAll(".dropdown-content");
        dropdowns.forEach(d => d.classList.toggle("show"));
    }
</script>
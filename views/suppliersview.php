<?php
require_once __DIR__ . "/../controller/supplierscontroller.php";
$response = handlesuppliers();
?>
<!DOCTYPE html>
<html lang="es">

<meta charset="UTF-8">
<title>Proveedores</title>
<link rel="stylesheet" href="./css/suppliers.css">
<h2>Proveedores</h2>

<div class="s_buttons">
    <input type="search" name="searchbar" placeholder="Buscar proveedor...">
    <div class="dropdown">
        <input type="button" value="Opciones" onclick="toggleDropdown()">
        <div class="dropdown-content" id="dropdownOpciones">
            <a href="#" onclick="openModal('registerProveedorModal')">Registrar proveedor</a>
            <!-- <a href="#" onclick="openModal('updateProveedorModal')">Actualizar proveedor</a> -->
            <a href="#" onclick="openModal('deleteProveedorModal')">Eliminar proveedor</a>
        </div>
    </div>
    <input type="button" value="Exportar PDF">
    <input type="button" value="Exportar CSV">
    <input type="button" value="Imprimir">
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

<!-- Modal: Registrar proveedor -->
<div id="registerProveedorModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerProveedorModal')">&times;</span>
        <div class="form-container">
            <h2>Registrar Proveedor</h2>
            <form action="./controller/supplierscontroller.php" method="POST">
                <input type="hidden" name="accion" value="agregar">
                <input type="text" name="nombre" placeholder="Nombre del proveedor" required>
                <input type="text" name="contacto" placeholder="Nombre del contacto">
                <input type="text" name="telefono" placeholder="Teléfono">
                <input type="email" name="correo" placeholder="Correo electrónico">
                <input type="text" name="nit" placeholder="NIT">
                <input type="text" name="direccion" placeholder="Dirección">
                <div class="form-actions">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="closeModal('registerProveedorModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Actualizar proveedor -->
<div id="updateProveedorModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('updateProveedorModal')">&times;</span>
        <div class="form-container">
            <h2>Actualizar Proveedor</h2>
            <form action="./controller/supplierscontroller.php" method="POST">
                <input type="hidden" name="accion" value="actualizar">
                <input type="text" name="id" placeholder="ID del proveedor" required>
                <input type="text" name="nombre" placeholder="Nuevo nombre del proveedor">
                <input type="text" name="contacto" placeholder="Nuevo contacto">
                <input type="text" name="telefono" placeholder="Nuevo teléfono">
                <input type="email" name="correo" placeholder="Nuevo correo electrónico">
                <input type="text" name="nit" placeholder="Nuevo NIT">
                <input type="text" name="direccion" placeholder="Nueva dirección">
                <div class="form-actions">
                    <button type="submit">Actualizar</button>
                    <button type="button" onclick="closeModal('updateProveedorModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Eliminar proveedor -->
<div id="deleteProveedorModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteProveedorModal')">&times;</span>
        <div class="form-container">
            <h2>Eliminar Proveedor</h2>
            <form action="./controller/supplierscontroller.php" method="POST">
                <input type="hidden" name="accion" value="eliminar">
                <input type="text" name="identificador" placeholder="Ingrese el ID o NIT del proveedor" required>
                <div class="form-actions">
                    <button type="submit" class="delete">Eliminar</button>
                    <button type="button" onclick="closeModal('deleteProveedorModal')">Cancelar</button>
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
        const modals = ['registerProveedorModal', 'updateProveedorModal', 'deleteProveedorModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    }

    function toggleDropdown() {
        const dropdown = document.getElementById("dropdownOpciones");
        dropdown.classList.toggle("show");
    }

    window.addEventListener("click", function (e) {
        if (!e.target.matches('.dropdown input')) {
            const open = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < open.length; i++) {
                open[i].classList.remove('show');
            }
        }
    });
</script>

</html>
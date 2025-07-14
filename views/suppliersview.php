<?php
require_once __DIR__ . "/../controller/supplierscontroller.php";
require_once __DIR__ . "/../functions/auth.php";
// session_start();

// if (!isset($_SESSION['usuario_id'])) {
//     header("Location: ./views/loginview.php");
//     exit();
// }

$response = handlesuppliers();
$disabled = getDisabledState();
$btnClass = $disabled['class'];
$btnAttr = $disabled['attr'];
?>

<link rel="stylesheet" href="./css/suppliers.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<h2>Proveedores</h2>

<div class="s_buttons">
    <input type="search" name="searchbar" placeholder="Buscar proveedor...">
    <div class="dropdown">
        <button class="dropdown-button" onclick="toggleDropdown()">
            Opciones <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="dropdown-content" id="dropdownOpciones">
            <button type="button" class="<?= $btnClass ?>" onclick="openModal('registerProveedorModal')" <?= $btnAttr ?>>Registrar proveedor</button>
            <button type="button" class="<?= $btnClass ?>" onclick="openModal('deleteProveedorModal')" <?= $btnAttr ?>>Eliminar proveedor</button>
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
                <th>Nit</th>
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
                    <td><?= $p["nit"] ?></td>
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
            <form action="controller/supplierscontroller.php" method="POST">
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

<!-- Modal: Eliminar proveedor -->
<div id="deleteProveedorModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteProveedorModal')">&times;</span>
        <div class="form-container">
            <h2>Eliminar Proveedor</h2>
            <form action="controller/supplierscontroller.php" method="POST">
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
        const btn = document.querySelector(`[onclick="openModal('${modalId}')"]`);
        if (btn && btn.classList.contains('disabled-button')) return;
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function toggleDropdown() {
        const dropdown = document.getElementById("dropdownOpciones");
        dropdown.classList.toggle("show");
    }

    window.onclick = function (e) {
        const modals = ['registerProveedorModal', 'deleteProveedorModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });

        if (!e.target.matches('.dropdown input')) {
            const open = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < open.length; i++) {
                open[i].classList.remove('show');
            }
        }
    }
</script>

<script>
    function showAlert(title, message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `custom-alert ${type}`;
        const icons = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-times-circle"></i>',
            info: '<i class="fas fa-info-circle"></i>'
        };
        alert.innerHTML = `
            <div class="alert-title">${icons[type] || icons.info} ${title}</div>
            <div class="alert-message">${message}</div>
        `;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 4500);
    }

    window.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash;
        if (hash.includes('Proveedores::')) {
            const mensajePlano = decodeURIComponent(hash.split('::')[1] || '');
            if (mensajePlano.trim()) {
                alert(mensajePlano);
            }
            // Limpiar el hash para evitar repetición
            history.replaceState(null, '', window.location.pathname);
        }
    });
</script>
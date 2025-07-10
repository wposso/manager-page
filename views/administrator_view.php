<?php
require_once __DIR__ . "/../controller/usercontroller.php";
require_once __DIR__ . "/../controller/logscontroller.php";
require_once __DIR__ . "/../controller/projectscontroller.php";
require_once __DIR__ . "/../controller/warehousescontroller.php";

$usuarios = getUsers();
$logs = getLogs();
$proyectos = getProjects();
$bodegas = getWarehouses();
?>

<link rel="stylesheet" href="./css/admin.css">

<h2 class="admin-heading">Panel de Administración General</h2>

<div class="admin-section">
    <h3>Usuarios del Sistema</h3>
    <div class="row">
        <div class="searchbar">
            <input type="search" placeholder="Buscar usuario...">
        </div>
        <div class="buttons">
            <button onclick="openModal('addUserModal')">Añadir Usuario</button>
            <button onclick="openModal('deleteUserModal')">Eliminar Usuario</button>
        </div>
    </div>
    <table class="admin-table">
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
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u["id"] ?></td>
                    <td><?= ucfirst($u["rol"]) ?></td>
                    <td><?= $u["nombre"] ?></td>
                    <td><?= $u["email"] ?></td>
                    <td><?= $u["creado_en"] ?></td>
                    <td><?= $u["actualizado_en"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="admin-section">
    <h3>Proyectos</h3>
    <div class="buttons">
        <button onclick="openModal('addProjectModal')">Añadir Proyecto</button>
    </div>
    <ul class="simple-list">
        <?php foreach ($proyectos as $p): ?>
            <li>
                <?= $p["nombre"] ?> - <?= $p["ubicacion"] ?>
                <button class="delete-btn" onclick="confirmDeleteProject(<?= $p['id'] ?>)">🗑️</button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="admin-section">
    <h3>Bodegas</h3>
    <div class="buttons">
        <button onclick="openModal('addWarehouseModal')">Añadir Bodega</button>
    </div>
    <ul class="simple-list">
        <?php foreach ($bodegas as $b): ?>
            <li>
                <?= $b["nombre"] ?> - <?= $b["ubicacion"] ?>
                <button class="delete-btn" onclick="confirmDeleteWarehouse(<?= $b['id'] ?>)">🗑️</button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="admin-section">
    <h3>Registros de Actividad (Logs)</h3>
    <table class="admin-table logs">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $l): ?>
                <tr>
                    <td><?= $l["usuario"] ?></td>
                    <td><?= $l["accion"] ?></td>
                    <td><?= $l["fecha"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Formulario invisible para eliminar -->
<form id="deleteForm" method="POST" style="display: none;"></form>

<!-- Modales -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addUserModal')">&times;</span>
        <h2>Registrar Usuario</h2>
        <form method="POST" action="./controller/usercontroller.php">
            <input type="hidden" name="action" value="add">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="" disabled selected hidden>Rol</option>
                <option value="ADMIN">Administrador</option>
                <option value="OPERADOR">Operador</option>
                <option value="SUPERVISOR">Supervisor</option>
            </select>
            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<div id="deleteUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteUserModal')">&times;</span>
        <h2>Eliminar Usuario</h2>
        <form method="POST" action="./controller/usercontroller.php">
            <input type="hidden" name="action" value="delete">
            <input type="email" name="email" placeholder="Correo del usuario" required>
            <button type="submit" class="delete">Eliminar</button>
        </form>
    </div>
</div>

<div id="addWarehouseModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addWarehouseModal')">&times;</span>
        <h2>Registrar Bodega</h2>
        <form method="POST" action="./controller/warehousescontroller.php">
            <input type="hidden" name="action" value="add">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="ubicacion" placeholder="Ubicación" required>
            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<div id="addProjectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addProjectModal')">&times;</span>
        <h2>Registrar Proyecto</h2>
        <form method="POST" action="./controller/projectscontroller.php">
            <input type="hidden" name="action" value="add">
            <input type="text" name="nombre" placeholder="Nombre del proyecto" required>
            <input type="text" name="ubicacion" placeholder="Ubicación" required>
            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function (e) {
        document.querySelectorAll(".modal").forEach(modal => {
            if (e.target === modal) modal.style.display = "none";
        });
    };

    function confirmDeleteProject(id) {
        if (confirm("¿Estás seguro de eliminar este proyecto?")) {
            const form = document.getElementById('deleteForm');
            form.action = './controller/projectscontroller.php';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="${id}">
            `;
            form.submit();
        }
    }

    function confirmDeleteWarehouse(id) {
        if (confirm("¿Estás seguro de eliminar esta bodega?")) {
            const form = document.getElementById('deleteForm');
            form.action = './controller/warehousescontroller.php';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="${id}">
            `;
            form.submit();
        }
    }
</script>
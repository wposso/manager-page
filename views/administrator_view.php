<?php
require_once __DIR__ . "/../controller/usercontroller.php";
require_once __DIR__ . "/../controller/logscontroller.php";
require_once __DIR__ . "/../controller/projectscontroller.php";
require_once __DIR__ . "/../controller/warehousescontroller.php";
require_once __DIR__ . '/../functions/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

$usuarios = getUsers();
$logs = getLogs();
$proyectos = getProjects();
$bodegas = getWarehouses();

$disabled = getDisabledState(); // ← control por rol
$btnClass = $disabled['class'];
$btnAttr = $disabled['attr'];
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
            <button onclick="openModal('addUserModal')" class="<?= $btnClass ?>" <?= $btnAttr ?>>Añadir Usuario</button>
            <button onclick="openModal('deleteUserModal')" class="<?= $btnClass ?>" <?= $btnAttr ?>>Eliminar Usuario</button>
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

<!-- Sección de Proyectos -->
<div class="admin-section">
    <div class="section-header">
        <h3>Proyectos</h3>
        <div class="project-controls">
            <button onclick="openModal('addProjectModal')" class="<?= $btnClass ?>" <?= $btnAttr ?>>Añadir Proyecto</button>
            <button onclick="openModal('deleteProjectModal')" class="<?= $btnClass ?>" <?= $btnAttr ?>>Eliminar Proyecto</button>
        </div>
    </div>

    <div class="card-grid">
        <?php foreach ($proyectos as $p): ?>
            <div class="card">
                <div class="card-header"><?= htmlspecialchars($p["nombre"]) ?></div>
                <div class="card-body">
                    <p><strong>Ubicación:</strong> <?= htmlspecialchars($p["ubicacion"]) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Sección de Bodegas -->
<div class="admin-section">
    <div class="section-header">
        <h3>Bodegas</h3>
        <div class="project-controls">
            <button onclick="openModal('addWarehouseModal')" class="<?= $btnClass ?>" <?= $btnAttr ?>>Añadir Bodega</button>
            <button onclick="openModal('deleteWarehouseModal')" class="<?= $btnClass ?>" <?= $btnAttr ?>>Eliminar Bodega</button>
        </div>
    </div>

    <div class="card-grid">
        <?php foreach ($bodegas as $b): ?>
            <div class="card">
                <div class="card-header"><?= htmlspecialchars($b["nombre"]) ?></div>
                <div class="card-body">
                    <p><strong>Ubicación:</strong> <?= htmlspecialchars($b["ubicacion"]) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
<?php if (!isOperador()): ?>
    <!-- Solo mostrar modales si tiene permisos -->
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

    <div id="deleteProjectModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteProjectModal')">&times;</span>
            <div class="form-container">
                <h2 style="text-align: left;">Eliminar Proyecto</h2>
                <form method="POST" action="./controller/projectscontroller.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="text" name="identificador" placeholder="ID o nombre del proyecto" required>
                    <div class="form-actions">
                        <button type="submit" class="delete">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteWarehouseModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteWarehouseModal')">&times;</span>
            <div class="form-container">
                <h2 style="text-align: left;">Eliminar Bodega</h2>
                <form method="POST" action="./controller/warehousescontroller.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="text" name="identificador" placeholder="ID o nombre de la bodega" required>
                    <div class="form-actions">
                        <button type="submit" class="delete">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.getElementById('layout').classList.add('blur-background');
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        const anyModalOpen = [...document.querySelectorAll(".modal")]
            .some(modal => modal.style.display === 'flex');
        if (!anyModalOpen) {
            document.getElementById('layout').classList.remove('blur-background');
        }
    }

    window.onclick = function (e) {
        document.querySelectorAll(".modal").forEach(modal => {
            if (e.target === modal) modal.style.display = "none";
        });
    };
</script>

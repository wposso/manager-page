<?php
require_once __DIR__ . "/../controller/newscontroller.php";
require_once __DIR__ . "/../controller/warehousescontroller.php";
require_once __DIR__ . '/../functions/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

$registros = handlegetnews();
$bodegas = getWarehouses();
$disabled = getDisabledState();
$btnClass = $disabled['class'];
$btnAttr = $disabled['attr'];
?>

<link rel="stylesheet" href="./css/news.css">

<h2 class="news-title">Gestión de Novedades</h2>

<div class="n_buttons">
    <input type="search" placeholder="Buscar novedad...">

    <div class="n_dropdown">
        <button class="dropdown-button <?= $btnClass ?>" <?= $btnAttr ?>>
            Opciones <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="n_dropdown_content">
            <button class="<?= $btnClass ?>" <?= $btnAttr ?> onclick="openModal('registerModal')">Registrar novedad</button>
            <button class="<?= $btnClass ?>" <?= $btnAttr ?> onclick="openModal('deleteModal')">Eliminar novedad</button>
        </div>
    </div>

    <input type="button" value="Exportar PDF" class="<?= $btnClass ?>" <?= $btnAttr ?>>
    <input type="button" value="Exportar CSV" class="<?= $btnClass ?>" <?= $btnAttr ?>>
    <input type="button" value="Imprimir" class="<?= $btnClass ?>" <?= $btnAttr ?>>
</div>

<div class="n_table_container">
    <table class="n_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Bodega</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $n): ?>
                <tr>
                    <td><?= $n["id"] ?></td>
                    <td><?= $n["titulo"] ?></td>
                    <td><?= $n["descripcion"] ?></td>
                    <td><?= $n["fecha"] ?></td>
                    <td><?= ucfirst(strtolower($n["tipo"])) ?></td>
                    <td><?= $n["bodega"] ?? 'No asignada' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (!isOperador()): ?>
<!-- Modal: Registrar -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerModal')">&times;</span>
        <div class="form-container">
            <h2>Registrar Novedad</h2>
            <form method="POST" action="./controller/newscontroller.php">
                <input type="hidden" name="action" value="add">
                <input type="text" name="titulo" placeholder="Título" required>
                <textarea name="descripcion" placeholder="Descripción" required></textarea>
                <input type="date" name="fecha" required>
                <select name="tipo" required>
                    <option value="" disabled selected hidden>Seleccione tipo</option>
                    <option value="DAÑO">Daño</option>
                    <option value="FALTA">Falta</option>
                    <option value="SOBRANTE">Sobrante</option>
                    <option value="REUBICACION">Reubicación</option>
                    <option value="OTRO">Otro</option>
                </select>
                <select name="bodega_id" required>
                    <option value="" disabled selected hidden>Seleccione bodega</option>
                    <?php foreach ($bodegas as $b): ?>
                        <option value="<?= $b["id"] ?>"><?= $b["nombre"] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-actions">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="closeModal('registerModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Eliminar -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <div class="form-container">
            <h2>Eliminar Novedad</h2>
            <form method="POST" action="./controller/newscontroller.php">
                <input type="hidden" name="action" value="delete">
                <input type="number" name="id" placeholder="ID de la novedad" required>
                <div class="form-actions">
                    <button type="submit" class="delete">Eliminar</button>
                    <button type="button" onclick="closeModal('deleteModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = "flex";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function (e) {
        document.querySelectorAll(".modal").forEach(modal => {
            if (e.target === modal) modal.style.display = "none";
        });
    };
</script>

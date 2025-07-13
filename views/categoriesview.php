<?php
require_once __DIR__ . '/../controller/categoriescontroller.php';
require_once __DIR__ . '/../functions/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

$response = handlecategories();
$disabled = getDisabledState();
$btnClass = $disabled['class'];
$btnAttr = $disabled['attr'];
?>

<link rel="stylesheet" href="./css/categories.css">

<h2>Categorías</h2>

<div class="c_buttons">
    <input type="search" name="searchbar" placeholder="Buscar categoría...">

    <div class="c_dropdown">
        <button class="dropdown-button <?= $btnClass ?>" onclick="toggleDropdown()" <?= $btnAttr ?>>
            Opciones <i class="fa-solid fa-chevron-down"></i>
        </button>

        <div class="c_dropdown_content">
            <a href="javascript:void(0);" class="<?= $btnClass ?>" <?= $btnAttr ?> onclick="openModal('addCategoryModal')">Registrar categoría</a>
            <a href="javascript:void(0);" class="<?= $btnClass ?>" <?= $btnAttr ?> onclick="openModal('deleteCategoryModal')">Eliminar categoría</a>
        </div>
    </div>

    <input type="button" value="Exportar PDF" class="<?= $btnClass ?>" <?= $btnAttr ?>>
    <input type="button" value="Exportar CSV" class="<?= $btnClass ?>" <?= $btnAttr ?>>
    <input type="button" value="Imprimir" class="<?= $btnClass ?>" <?= $btnAttr ?>>
</div>

<div class="c_table_container">
    <table class="c_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Creada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($response as $x): ?>
                <tr>
                    <td><?= $x["id"] ?></td>
                    <td><?= $x["nombre"] ?></td>
                    <td><?= $x["descripcion"] ?></td>
                    <td><?= $x["estado"] ? 'Activo' : 'Inactivo' ?></td>
                    <td><?= $x["creado_en"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (!isOperador()): ?>
<!-- Modal agregar -->
<div id="addCategoryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addCategoryModal')">&times;</span>
        <div class="form-container">
            <h2>Registrar Categoría</h2>
            <form method="POST" action="./controller/categoriescontroller.php">
                <input type="hidden" name="accion" value="agregar">
                <input type="text" name="nombre" placeholder="Nombre de la categoría" required>
                <input type="text" name="descripcion" placeholder="Descripción" required>
                <select name="estado" required>
                    <option value="" disabled selected hidden>Estado</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <div class="form-actions">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="closeModal('addCategoryModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal eliminar -->
<div id="deleteCategoryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteCategoryModal')">&times;</span>
        <div class="form-container">
            <h2>Eliminar Categoría</h2>
            <form method="POST" action="./controller/categoriescontroller.php">
                <input type="hidden" name="accion" value="eliminar">
                <input type="text" name="identificador" placeholder="ID o nombre de la categoría" required>
                <div class="form-actions">
                    <button type="submit" class="delete">Eliminar</button>
                    <button type="button" onclick="closeModal('deleteCategoryModal')">Cancelar</button>
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
        ['addCategoryModal', 'deleteCategoryModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    };
</script>

<?php
require_once __DIR__ . '/../controller/subcategorie_controller.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

$rol = $_SESSION['rol'] ?? '';
$subcategorias = handlesubcategories();
$categorias = getCategorias();
?>
<link rel="stylesheet" href="./css/subcategorie.css">

<h2>Subcategorías</h2>

<div class="s_buttons">
    <input type="search" name="search" placeholder="Buscar subcategoría...">

    <div class="s_dropdown">
        <button class="s_dropdown_button" <?= $rol === 'operador' ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : '' ?>>
            Opciones <i class="fa-solid fa-chevron-down"></i>
        </button>
        <?php if ($rol !== 'operador'): ?>
            <div class="s_dropdown_content">
                <button type="button" onclick="openModal('addModal')">Añadir registro</button>
                <button type="button" onclick="openModal('deleteModal')">Eliminar registro</button>
            </div>
        <?php endif; ?>
    </div>

    <input type="button" value="Exportar PDF" <?= $rol === 'operador' ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : '' ?>>
    <input type="button" value="Exportar CSV" <?= $rol === 'operador' ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : '' ?>>
    <input type="button" value="Imprimir" <?= $rol === 'operador' ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : '' ?>>
</div>

<div class="s_table_container">
    <table class="s_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Creada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subcategorias as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= $s['nombre'] ?></td>
                    <td><?= $s['descripcion'] ?></td>
                    <td><?= $s['categoria'] ?></td>
                    <td><?= $s['estado'] ? 'Activo' : 'Inactivo' ?></td>
                    <td><?= $s['creado_en'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($rol !== 'operador'): ?>
    <!-- Modal Añadir -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <div class="form-container">
                <h2>Añadir Subcategoría</h2>
                <form method="post" action="./controller/subcategorie_controller.php">
                    <input type="hidden" name="action" value="add">
                    <input type="text" name="nombre" placeholder="Nombre de la subcategoría" required>
                    <textarea name="descripcion" placeholder="Descripción" required></textarea>
                    <select name="categoria_id" required>
                        <option value="" disabled selected hidden>Categoría</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
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
                <h2>Eliminar Subcategoría</h2>
                <form method="post" action="./controller/subcategorie_controller.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="text" name="nombre" placeholder="ID de la subcategoría" required>
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
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function (e) {
        ['addModal', 'deleteModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    };

    window.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash;
        if (hash.includes('Subcategorias::')) {
            const parts = hash.split('::');
            if (parts.length > 1) {
                const mensaje = decodeURIComponent(parts[1]);
                alert(mensaje); // ✅ o usa showAlert() si quieres el formato personalizado
            }
            // Limpia el hash para evitar recarga con alerta repetida
            history.replaceState(null, '', window.location.pathname);
        }
    });

</script>
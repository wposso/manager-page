<?php require_once __DIR__ . "/../controller/newscontroller.php";
$x = handlegetnews(); ?>
<link rel="stylesheet" href="./css/news.css">

<h2>Novedades</h2>

<div class="n_buttons">
    <input type="search" name="search" placeholder="Buscar Registro...">

    <div class="n_dropdown">
        <input type="button" value="Opciones">
        <div class="n_dropdown_content">
            <button onclick="openModal('registerModal')">Registrar novedad</button>
            <button onclick="openModal('deleteModal')">Eliminar novedad</button>
        </div>
    </div>


    <input type="button" value="Exportar PDF">
    <input type="button" value="Exportar CSV">
    <input type="button" value="Imprimir">
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

<!-- Modal: Registrar -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerModal')">&times;</span>
        <div class="form-container">
            <h2>Registrar Novedad</h2>
            <form action="../controller/newscontroller.php" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="titulo" placeholder="Título" required>
                <input type="text" name="descripcion" placeholder="Descripción" required>
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
                    <!-- Opciones dinámicas si se desea -->
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
            <form action="../controller/newscontroller.php" method="POST">
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
<script>
    function openModal(id) {
        document.getElementById(id).style.display = "flex";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function (e) {
        ['registerModal', 'deleteModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    }
</script>
<?php
require_once __DIR__ . "/../controller/categoriescontroller.php";
$response = handlecategories();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link rel="stylesheet" href="./css/categories.css">
</head>

<body>

    <h2>Categorías</h2>

    <div class="c_buttons">
        <input type="search" name="searchbar" placeholder="Buscar categoría...">

        <div class="c_dropdown">
            <input type="button" value="Opciones">
            <div class="c_dropdown_content">
                <a href="#" onclick="openModal('addCategoryModal')">Registrar categoría</a>
                <a href="#" onclick="openModal('deleteCategoryModal')">Eliminar categoría</a>
            </div>
        </div>


        <input type="button" value="Exportar PDF">
        <input type="button" value="Exportar CSV">
        <input type="button" value="Imprimir">
    </div>

    <div class="c_table_container">
        <table class="c_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($response as $x): ?>
                    <tr>
                        <td><?= $x["id"] ?></td>
                        <td><?= $x["nombre"] ?></td>
                        <td><?= $x["descripcion"] ?></td>
                        <td><?= $x["estado"] ? 'Activo' : 'Inactivo' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

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

    <div id="deleteCategoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteCategoryModal')">&times;</span>
            <div class="form-container">
                <h2>Eliminar Categoría</h2>
                <form method="POST" action="./controller/categoriescontroller.php">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="text" name="identificador" placeholder="Ingrese el ID o nombre de la categoría"
                        required>
                    <div class="form-actions">
                        <button type="submit" class="delete">Eliminar</button>
                        <button type="button" onclick="closeModal('deleteCategoryModal')">Cancelar</button>
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
            ['addCategoryModal', 'deleteCategoryModal'].forEach(id => {
                const modal = document.getElementById(id);
                if (e.target === modal) modal.style.display = "none";
            });
        }
    </script>

</body>

</html>
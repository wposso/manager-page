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
            <a href="#" id="openModalBtn">Añadir registro</a>
            <a href="#">Editar registro</a>
            <a href="#">Eliminar registro</a>
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
                <th>id</th>
                <th>nombre</th>
                <th>categoria</th>
                <th>precio</th>
                <th>unidad</th>
                <th>stock</th>
                <th>descripcion</th>
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
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="form-scroll">
                <div class="form-container">
                    <h2>Añadir registro</h2>
                    <form id="form-inventario" action="./controller/inventorycontroller.php" class="form-content"
                        method="post">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" placeholder="Ingrese el nombre">

                        <label for="category">Categoría</label>
                        <input type="text" id="category" name="category" placeholder="Ingrese la categoría">

                        <label for="price">Precio</label>
                        <input type="text" id="price" name="price" placeholder="Ingrese el precio">

                        <label for="unit">Unidad</label>
                        <input type="text" id="unit" name="unit" placeholder="Ingrese la unidad">

                        <label for="stock">Stock</label>
                        <input type="text" id="stock" name="stock" placeholder="Ingrese el stock">

                        <label for="description">Descripción</label>
                        <input type="text" id="description" name="description" placeholder="Ingrese la descripción">

                        <input type="submit" value="Guardar Registro" class="btn-submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // ----- MODAL -----
        const modal = document.getElementById("myModal");
        const openBtn = document.getElementById("openModalBtn");
        const closeBtn = document.querySelector(".close");

        if (openBtn && closeBtn && modal) {
            openBtn.onclick = () => modal.style.display = "block";
            closeBtn.onclick = () => modal.style.display = "none";
            window.onclick = (e) => {
                if (e.target === modal) modal.style.display = "none";
            };
        }

        // ----- FORMULARIO INVENTARIO -----
        const formInventario = document.getElementById("form-inventario");
        if (formInventario) {
            formInventario.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("./controller/inventorycontroller.php", {
                    method: "POST",
                    body: formData
                })
                    .then(async res => {
                        if (!res.ok) throw new Error("Error en la respuesta del servidor");
                        const data = await res.json();
                        alert(data.message);
                        if (data.success) {
                            this.reset();
                            if (typeof cargarTablaInventario === "function") {
                                cargarTablaInventario();
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Error inesperado al enviar el formulario.");
                    });
            });
        }
    </script>
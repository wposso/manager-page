<?php
require_once __DIR__ . "/../controller/inventorycontroller.php";
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/getinventory.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

$bodega_id = $_GET['id'] ?? null;
if (!$bodega_id) {
    echo "<p>Error: Bodega no especificada.</p>";
    exit();
}

$products = handleGetInventoryByBodega($bodega_id);
$catalogos = getCatalogosForSelects();
$disabled = getDisabledState();
$btnClass = $disabled['class'];
$btnAttr = $disabled['attr'];
$bodega_nombre = getBodegaName($bodega_id);
?>

<link rel="stylesheet" href="./css/inventory.css">

<h2>Inventario en Bodega: <?= htmlspecialchars($bodega_nombre) ?></h2>

<div class="i_buttons">
    <input type="search" name="search" placeholder="Buscar producto...">

    <div class="i_dropdown">
        <button class="dropdown-button <?= $btnClass ?>" <?= $btnAttr ?>>
            Opciones <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="dropdown-content">
            <button type="button" class="<?= $btnClass ?>" <?= $btnAttr ?>
                onclick="openModal('transferModal')">Transferir inventario</button>
        </div>
    </div>


    <input type="button" value="Exportar PDF" class="<?= $btnClass ?>" <?= $btnAttr ?>>
    <input type="button" value="Exportar CSV" class="<?= $btnClass ?>" <?= $btnAttr ?>>
    <input type="button" value="Imprimir" class="<?= $btnClass ?>" <?= $btnAttr ?>>
</div>

<div class="i_table_container">
    <table class="i_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Proveedor</th>
                <th>Bodega</th>
                <th>Cantidad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['codigo'] ?></td>
                    <td><?= $p['nombre'] ?></td>
                    <td><?= $p['categoria'] ?></td>
                    <td><?= $p['subcategoria'] ?></td>
                    <td><?= $p['proveedor'] ?></td>
                    <!-- <td><?= $p['bodega'] ?></td> -->
                    <td><?= $p['ubicacion'] ?? 'Sin ubicación' ?></td>
                    <td><?= $p['cantidad'] ?></td>
                    <td><?= $p['estado'] ? 'Activo' : 'Inactivo' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (!isOperador()): ?>
    <!-- Modal Añadir -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <div class="form-container">
                <h2>Añadir Producto</h2>
                <form method="post" action="./controller/inventorycontroller.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="bodega_id" value="<?= $bodega_id ?>">

                    <input type="text" name="codigo" placeholder="Código del producto" required>
                    <input type="text" name="nombre" placeholder="Nombre del producto" required>
                    <input type="number" name="cantidad" placeholder="Cantidad" required>

                    <select name="categoria_id" required>
                        <option value="" disabled selected hidden>Categoría</option>
                        <?php foreach ($catalogos['categorias'] as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="subcategoria_id" required>
                        <option value="" disabled selected hidden>Subcategoría</option>
                        <?php foreach ($catalogos['subcategorias'] as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="proveedor_id" required>
                        <option value="" disabled selected hidden>Proveedor</option>
                        <?php foreach ($catalogos['proveedores'] as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="proyecto_id" required>
                        <option value="" disabled selected hidden>Proyecto</option>
                        <?php
                        $conn = db_connect();
                        $proyectos = $conn->query("SELECT id, nombre FROM proyecto");
                        while ($row = $proyectos->fetch_assoc()):
                            ?>
                            <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
                        <?php endwhile; ?>
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
                <h2>Eliminar Producto</h2>
                <form method="post" action="./controller/inventorycontroller.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="text" name="codigo" placeholder="Código del producto" required>
                    <div class="form-actions">
                        <button type="submit" class="delete">Eliminar</button>
                        <button type="button" onclick="closeModal('deleteModal')">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Transferencia -->
    <div id="transferModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('transferModal')">&times;</span>
            <div class="form-container">
                <h2>Transferir Inventario</h2>
                <form method="post" action="./controller/inventorycontroller.php">
                    <input type="hidden" name="action" value="transfer">
                    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">

                    <select name="producto_id" required>
                        <option value="" disabled selected hidden>Selecciona el producto</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= $p['codigo'] ?> - <?= $p['nombre'] ?> (<?= $p['cantidad'] ?> disponibles)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="origen_tipo_id" required>
                        <option value="" disabled selected hidden>Origen</option>
                        <optgroup label="Bodegas">
                            <?php foreach ($catalogos['bodegas'] as $b): ?>
                                <option value="bodega_<?= $b['id'] ?>"><?= $b['nombre'] ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="Proyectos">
                            <?php foreach ($catalogos['proyectos'] as $p): ?>
                                <option value="proyecto_<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>

                    <select name="destino_tipo_id" required>
                        <option value="" disabled selected hidden>Destino</option>
                        <optgroup label="Bodegas">
                            <?php foreach ($catalogos['bodegas'] as $b): ?>
                                <option value="bodega_<?= $b['id'] ?>"><?= $b['nombre'] ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="Proyectos">
                            <?php foreach ($catalogos['proyectos'] as $p): ?>
                                <option value="proyecto_<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>

                    <input type="number" name="cantidad" placeholder="Cantidad a transferir" required min="1">
                    <textarea name="motivo" placeholder="Motivo de la transferencia" required></textarea>

                    <div class="form-actions">
                        <button type="submit">Transferir</button>
                        <button type="button" onclick="closeModal('transferModal')">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php endif; ?>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function (e) {
        ['addModal', 'deleteModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = "none";
        });
    }
</script>
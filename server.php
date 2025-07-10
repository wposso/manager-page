<?php
require_once __DIR__ . '/database/dbconnection.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

function getProyectos()
{
    $conn = db_connect();
    $proyectos = [];
    $result = $conn->query("SELECT id, nombre FROM proyecto");

    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }
    return $proyectos;
}

function getBodegas()
{
    $conn = db_connect();
    $bodegas = [];
    $result = $conn->query("SELECT id, nombre FROM bodega");

    while ($row = $result->fetch_assoc()) {
        $bodegas[] = $row;
    }
    return $bodegas;
}

$proyectos = getProyectos();
$bodegas = getBodegas();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acema | Inventario</title>
    <link rel="stylesheet" href="./css/server.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="./assets/images/Logo-ACEMA.png" alt="Logo de ACEMA" width="160">
            <span style="font-weight: bold; color: #333; margin: left 70px;">
                Bienvenido(a), <?= htmlspecialchars($_SESSION['nombre']) ?>
            </span>
        </div>

        <nav class="options">
            <ul>
                <a href="#Notificaciones"><i class="fa-solid fa-code"></i> Soporte</a>
                <a href="#Lenguaje"><i class="fa-solid fa-earth-americas"></i> Lenguaje</a>
                <a href="#Ayuda"><i class="fa-solid fa-circle-info"></i> Ayuda</a>
                <a href="#Administrador" onclick="handleLoadView('administrator_view.php');"><i
                        class="fa-solid fa-hammer"></i> Administrador</a>
            </ul>
        </nav>
    </header>

    <nav class="drawer">
        <h3>Menú</h3>
        <a href="#Dashboard" onclick="handleLoadView('homeview.php');"><i class="fa-solid fa-gauge-high"></i>
            Dashboard</a><br>
        <a href="#Inventario" onclick="handleLoadView('inventoryview.php');"><i class="fa-solid fa-boxes-stacked"></i>
            Inventario</a><br>

        <!-- PROYECTOS DINÁMICOS -->
        <div class="menu-group">
            <a href="#Proyectos" onclick="toggleGroupSubmenu(event)" class="menu-link">
                <i class="fa-solid fa-oil-well"></i> Proyectos
                <i class="fa-solid fa-chevron-down arrow-icon"></i>
            </a>
            <div class="submenu" style="display: none;">
                <?php foreach ($proyectos as $p): ?>
                    <a href="#Proyecto<?= $p['id'] ?>" onclick="handleLoadView('projectview.php?id=<?= $p['id'] ?>');"
                        class="submenu-link">
                        <i class="fa-solid fa-toolbox"></i> <?= htmlspecialchars($p['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- BODEGAS DINÁMICAS -->
        <div class="menu-group">
            <a href="#Bodegas" onclick="toggleGroupSubmenu(event)" class="menu-link">
                <i class="fa-solid fa-warehouse"></i> Bodegas
                <i class="fa-solid fa-chevron-down arrow-icon"></i>
            </a>
            <div class="submenu" style="display: none;">
                <?php foreach ($bodegas as $b): ?>
                    <a href="#Bodega<?= $b['id'] ?>" onclick="handleLoadView('warehouseview.php?id=<?= $b['id'] ?>');"
                        class="submenu-link">
                        <i class="fa-solid fa-box"></i> <?= htmlspecialchars($b['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <a href="#Proveedores" onclick="handleLoadView('suppliersview.php');"><i class="fa-solid fa-truck"></i>
            Proveedores</a><br>

        <div class="menu-group">
            <a href="#Categorías" onclick="toggleGroupSubmenu(event)" class="menu-link">
                <i class="fa-solid fa-tags"></i> Categorías
                <i class="fa-solid fa-chevron-down arrow-icon"></i>
            </a>
            <div class="submenu" style="display: none;">
                <a href="#Categoría" onclick="handleLoadView('categoriesview.php');" class="submenu-link">
                    <i class="fa-solid fa-tag"></i> Categoría
                </a>
                <a href="#Subcategorías" onclick="handleLoadView('subcategorie_view.php');" class="submenu-link">
                    <i class="fa-solid fa-tag"></i> Subcategoría
                </a>
            </div>
        </div>

        <a href="#Movimientos" onclick="handleLoadView('movementsview.php');"><i class="fa-solid fa-right-left"></i>
            Movimientos</a><br>
        <a href="#Reportes" onclick="handleLoadView('reportsview.php');"><i class="fa-solid fa-file-pen"></i>
            Reportes</a>
        <a href="#Novedades" onclick="handleLoadView('newsview.php');"><i class="fa-solid fa-calendar-check"></i>
            Novedades</a>
        <a href="#Cerrar sesión" onclick="openLogoutModal()">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </a>

    </nav>
    <div id="loader" class="loader-circle" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div class="layout" id="layout">
        <!-- Aquí se cargan las vistas -->
    </div>

    <footer>
        <p>@ 2025 derechos reservados | by dobleu</p>
    </footer>

    <!-- Scripts -->
    <script src="./javascript/handlelayout.js"></script>
    <script src="./javascript/modal.js"></script>
    <script src="./javascript/suppliers_modal.js"></script>

    <script>
        function toggleGroupSubmenu(event) {
            event.preventDefault();
            const group = event.currentTarget.parentElement;
            const submenu = group.querySelector('.submenu');
            const arrow = group.querySelector('.arrow-icon');

            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                group.classList.add('active');
            } else {
                submenu.style.display = 'none';
                group.classList.remove('active');
            }
        }

        function renderDashboardCharts() {
            const bar = document.getElementById("barChart");
            const pie = document.getElementById("pieChart");

            if (!bar || !pie) return;

            new Chart(bar.getContext("2d"), {
                type: "bar",
                data: {
                    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio"],
                    datasets: [{
                        label: "Movimientos",
                        data: [120, 90, 150, 110, 180, 140],
                        backgroundColor: "#2d8f2d",
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true },
                        x: { grid: { display: false } }
                    }
                }
            });

            new Chart(pie.getContext("2d"), {
                type: "doughnut",
                data: {
                    labels: ["Ferretería", "Eléctricos", "Pinturas", "Herramientas"],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: ["#2d8f2d", "#55b555", "#88cc88", "#b2e0b2"],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: { color: "#444", font: { size: 12 } }
                        }
                    }
                }
            });
        }
    </script>
    <!-- Modal de Confirmación de Cierre de Sesión -->
    <div id="logoutModal" class="modal-logout">
        <div class="modal-content">
            <h3><i class="fa-solid fa-circle-question"></i> ¿Desea cerrar sesión?</h3>
            <p>Su sesión actual será finalizada.</p>
            <div class="modal-buttons">
                <button onclick="confirmLogout()" class="btn-confirm">Sí, cerrar sesión</button>
                <button onclick="closeLogoutModal()" class="btn-cancel">Cancelar</button>
            </div>
        </div>
    </div>
    <script>
        function openLogoutModal() {
            document.getElementById("logoutModal").style.display = "flex";
        }

        function closeLogoutModal() {
            document.getElementById("logoutModal").style.display = "none";
        }

        function confirmLogout() {
            window.location.href = "logout.php";
        }
    </script>

</body>

</html>
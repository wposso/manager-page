<!DOCTYPE html>
<html lang="en">

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
        <img src="./assets/images/Logo-ACEMA.png" alt="Logo de ACEMA" width="160">
        <nav class="options">
            <ul>
                <a href="#Notificaciones"><i class="fa-solid fa-code"></i> Soporte</a>
                <a href="#Lenguaje"><i class="fa-solid fa-earth-americas"></i> Lenguaje</a>
                <a href="#Ayuda"><i class="fa-solid fa-circle-info"></i> Ayuda</a>
                <a href="#Administrador" onclick="onpress();"><i class="fa-solid fa-hammer"></i>
                    Administrador</a>
            </ul>
        </nav>
    </header>
    <script>
        function onpress() {
            alert("No tienes permiso para visualizar este modulo");
        }
    </script>
    <nav class="drawer">
        <h3>Menú</h3>
        <a href="#Dashboard" onclick="handleLoadView('homeview.php');">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a><br>

        <a href="#Inventario" onclick="handleLoadView('inventoryview.php');">
            <i class="fa-solid fa-boxes-stacked"></i> Inventario
        </a><br>
        <div class="menu-group">
            <a href="#Bodegas" onclick="toggleSubmenu(event)" class="menu-link">
                <i class="fa-solid fa-warehouse"></i> Bodegas
                <i class="fa-solid fa-chevron-down arrow-icon"></i>
            </a>

            <div class="submenu" style="display: none;">
                <a href="#BodegaA" onclick="handleLoadView('bagemedellin.php');" class="submenu-link">
                    <i class="fa-solid fa-box"></i> Bodega Acema
                </a>
                <a href="#BodegaB" onclick="handleLoadView('bagepelayo.php');" class="submenu-link">
                    <i class="fa-solid fa-box"></i> San Pelayo
                </a>
            </div>
        </div>

        <a href="#Categorías" onclick="handleLoadView('categoriesview.php');">
            <i class="fa-solid fa-tags"></i> Categorías
        </a><br>

        <a href="#Proveedores" onclick="handleLoadView('suppliersview.php');">
            <i class="fa-solid fa-truck"></i> Proveedores
        </a><br>

        <a href="#Movimientos" onclick="handleLoadView('movementsview.php');">
            <i class="fa-solid fa-right-left"></i> Movimientos
        </a><br>

        <a href="#Reportes" onclick="handleLoadView('reportsview.php');">
            <i class="fa-solid fa-file-pen"></i> Reportes
        </a>

        <a href="#Novedades" onclick="handleLoadView('newsview.php');">
            <i class="fa-solid fa-calendar-check"></i> Novedades
        </a>

        <a href="#Cerrar sesión">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </a>
    </nav>


    <div class="layout" id="layout">
        <!-- Contenido de las vistas -->
    </div>

    </div>
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

    <footer>
        <p>@ 2025 derechos reservados | by dobleu</p>
    </footer>
    <script src="./javascript/handlelayout.js"></script>
    <script>

        function toggleSubmenu(event) {
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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


</body>

</html>
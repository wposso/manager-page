<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ACEMA | Portal de Gestión de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body,
        html {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            width: 55%;
            background: linear-gradient(to right, #004a7c, #006ab6);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-panel h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .left-panel p.intro {
            font-size: 17px;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .datetime {
            font-size: 14px;
            margin-bottom: 30px;
            opacity: 0.85;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            margin-bottom: 10px;
            font-size: 20px;
            border-left: 4px solid #fff;
            padding-left: 10px;
        }

        .section p,
        .section ul {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.95;
        }

        .section ul {
            list-style: disc;
            padding-left: 20px;
        }

        .right-panel {
            width: 45%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 40px 60px;
            position: relative;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.06);
        }

        header.header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        header img {
            height: 38px;
        }

        nav.header-options a {
            margin-left: 0px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            padding: 10px;
            border-radius: 10px;
        }

        nav.header-options a:hover {
            color: white;
            background-color: #0066cc;
        }

        .login-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-area h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .login-area p {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .support-box {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .support-box h4 {
            margin-bottom: 10px;
            font-size: 14px;
            color: #444;
        }

        .support-box p {
            font-size: 13px;
            color: #666;
        }

        .support-box i {
            margin-right: 6px;
            color: #007bff;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                padding: 30px;
                height: auto;
            }

            header.header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Panel izquierdo -->
        <section class="left-panel">
            <h1>Bienvenido a ACEMA</h1>
            <p class="intro">Portal de Gestión de Inventario Inteligente</p>
            <div class="datetime" id="datetime"></div>

            <div class="section">
                <h3>Misión</h3>
                <p>
                    Brindar soluciones de consultoría, especificaciones técnicas e ingeniería para proyectos de granjas
                    solares, sistemas eléctricos de baja, mediana y alta tensión, optimizando los tiempos de ejecución e
                    inversiones necesarias.
                </p>
            </div>
            <div class="section">
                <h3>Visión</h3>
                <p>
                    Consolidar y posicionar ACEMA como una empresa líder en soporte, consultoría e ingeniería en el
                    desarrollo de proyectos asociados a granjas solares y subestaciones de baja, mediana y alta tensión.
                </p>
            </div>
            <div class="section">
                <h3>Valores</h3>
                <ul>
                    <li>Compromiso</li>
                    <li>Innovación</li>
                    <li>Orientación al resultado</li>
                    <li>Calidad</li>
                    <li>Transparencia</li>
                </ul>
            </div>
        </section>

        <!-- Panel derecho (login) -->
        <section class="right-panel">
            <header class="header">
                <img src="../assets/images/Logo-ACEMA.png" alt="Logo ACEMA" style="height: 50px;" />
                <nav class="header-options">
                    <a href="#"><i class="fa-solid fa-handshake"></i> Servicios</a>
                    <a href="#"><i class="fa-solid fa-users"></i> Nosotros</a>
                    <a href="#"><i class="fa-solid fa-envelope"></i> Contacto</a>
                </nav>
            </header>

            <div class="login-area">
                <h2>Inicio de sesión</h2>
                <p>Ingrese sus credenciales para acceder al sistema.</p>
                <form class="login-form" action="login.php" method="POST">
                    <input type="text" name="usuario" placeholder="Usuario" required />
                    <input type="password" name="clave" placeholder="Contraseña" required />
                    <button type="submit" onclick="window.location.href='../server.php'">Iniciar sesión</button>
                </form>

                <div class="support-box">
                    <h4><i class="fa-solid fa-headset"></i> Soporte Técnico</h4>
                    <p>¿Problemas para ingresar? Escríbenos a <b>soporte@acema.com</b> o llama al (601) 123 4567.</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('es-ES', options);
            const time = now.toLocaleTimeString('es-ES');
            document.getElementById('datetime').textContent = `${date} - ${time}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>

</html>
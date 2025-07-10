<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ACEMA | Plataforma de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        :root {
            --primary: #0066cc;
            --gray: #6c757d;
            --bg: #f4f6f8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            width: 55%;
            background: linear-gradient(to right, #004a7c, var(--primary));
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .left-panel i.big-icon {
            font-size: 72px;
            opacity: 0.2;
            position: absolute;
            top: 40px;
            right: 40px;
        }

        .left-panel h1 {
            font-size: 36px;
            margin-bottom: 15px;
        }

        .left-panel p.intro {
            font-size: 17px;
            margin-bottom: 25px;
            opacity: 0.95;
            max-width: 550px;
        }

        .left-panel .support-box {
            margin-top: 30px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }

        .left-panel .support-box h4 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        .left-panel .support-box p {
            font-size: 13px;
            line-height: 1.5;
            opacity: 0.95;
        }

        .left-panel .support-box i {
            color: white;
            margin-right: 6px;
        }

        .datetime {
            font-size: 14px;
            margin-top: auto;
            opacity: 0.8;
        }

        .right-panel {
            width: 45%;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 40px 60px;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.06);
        }

        header.header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        header img {
            height: 42px;
        }

        nav.header-options a {
            /* margin-left: 16px; */
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
        }

        nav.header-options a:hover {
            color: white;
            background-color: var(--primary);
        }

        .login-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-area h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #222;
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
            font-size: 14px;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #004d99;
        }

        .access-note {
            font-size: 12px;
            color: #888;
            margin-top: 15px;
            text-align: center;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                height: auto;
                padding: 30px;
            }

            header.header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .left-panel i.big-icon {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Panel izquierdo -->
        <section class="left-panel">
            <i class="fa-solid fa-box-open big-icon"></i>
            <h1>Bienvenido a ACEMA</h1>
            <p class="intro">
                <strong>ACEMA SGI</strong> es una plataforma integral diseñada para centralizar la gestión de
                inventario, controlar movimientos logísticos, registrar novedades y optimizar la trazabilidad de
                recursos en tiempo real.
            </p>

            <div class="support-box">
                <h4><i class="fa-solid fa-headset"></i> Soporte técnico</h4>
                <p>
                    ¿Tienes inconvenientes de acceso? Nuestro equipo está disponible para ayudarte a través de
                    <strong>soporte@acema.com</strong> o llamando al <strong>(601) 123 4567</strong>.
                </p>
            </div>

            <div class="datetime" id="datetime"></div>
        </section>

        <!-- Panel derecho -->
        <section class="right-panel">
            <header class="header">
                <img src="../assets/images/Logo-ACEMA.png" alt="Logo ACEMA" style="height: 50px" />
                <nav class="header-options">
                    <a href="#"><i class="fa-solid fa-handshake"></i> Servicios</a>
                    <a href="#"><i class="fa-solid fa-users"></i> Nosotros</a>
                    <a href="#"><i class="fa-solid fa-envelope"></i> Contacto</a>
                </nav>
            </header>

            <div class="login-area">
                <h2>Inicio de sesión</h2>
                <p>Ingrese sus credenciales corporativas para acceder al sistema.</p>
                <form class="login-form" action="../controller/logincontroller.php" method="POST">
                    <input type="text" name="usuario" placeholder="Usuario" required />
                    <input type="password" name="clave" placeholder="Contraseña" required />
                    <button type="submit" onclick="window.location = '../server.php'">Iniciar sesión</button>
                </form>

                <div class="access-note">
                    Acceso exclusivo para personal autorizado. Toda actividad es registrada y monitoreada.
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
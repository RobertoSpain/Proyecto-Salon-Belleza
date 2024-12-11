<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salón de Belleza</title>
    <style>
        /* Estilo básico para la página */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Estilo para el header */
        header {
            background-color: #007BFF;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        nav {
            margin-top: 10px;
        }

        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Estilo para el footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Header con enlaces -->
    <header>
        <h1>Salón de Belleza</h1>
        <nav>
            <a href="?path=home">Inicio</a>
            <a href="?path=servicios/listar">Servicios</a>
            <a href="?path=citas/misCitas">Mis Citas</a>
            <a href="?path=citas/agregar">Reservar Cita</a>
            <a href="?path=sesion/logout">Cerrar Sesión</a>
        </nav>
    </header>

    <!-- Contenido principal -->
    <main style="padding: 20px; text-align: center;">
        <h2>Bienvenido a nuestro salón</h2>
        <p>Elija una de las opciones del menú para continuar.</p>
    </main>

    <!-- Footer -->
    <footer>
        &copy; <?= date("Y"); ?> Salón de Belleza - Todos los derechos reservados.
    </footer>
</body>
</html>

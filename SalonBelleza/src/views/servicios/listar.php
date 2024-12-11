<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    /* Contenedor principal centrado */
    .contenedor-flex {
        display: flex;
        justify-content: center; /* Centra los contenidos en el medio horizontalmente */
        gap: 50px; /* Espacio entre la tabla y el formulario */
        align-items: flex-start;
        margin: 0 auto;
        max-width: 90%;
        padding: 20px 0;
    }

    /* Estilo de la tabla */
    table {
        width: 60%;
        border-collapse: collapse;
        margin: 0;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    /* Estilo del formulario */
    .formulario-asignar {
        width: 30%;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        width: 100%;
        text-align: center;
    }

    .btn:hover {
        background-color: #218838;
    }

    .back-link {
        margin-top: 15px;
        text-align: center;
    }

    .back-link a {
        color: #007BFF;
        text-decoration: none;
    }

    .back-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="contenedor-flex">
    <!-- Lista de Servicios -->
    <div>
        <h1>Lista de Servicios</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Duración (min)</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicios as $servicio): ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['id_servicio']) ?></td>
                        <td><?= htmlspecialchars($servicio['nombre']) ?></td>
                        <td><?= htmlspecialchars($servicio['precio']) ?>€</td>
                        <td><?= htmlspecialchars($servicio['duracion']) ?></td>
                        <td><?= htmlspecialchars($servicio['descripcion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'encargado'): ?>
        <!-- Formulario para Asignar Servicio -->
        <div class="formulario-asignar">
            <h2>Asignar Servicio a Empleado</h2>
            <form method="POST" action="?path=servicios/asignar">
                <div class="form-group">
                    <label for="id_empleado">ID Empleado:</label>
                    <input type="number" name="id_empleado" id="id_empleado" required>
                </div>

                <div class="form-group">
                    <label for="id_servicio">ID Servicio:</label>
                    <input type="number" name="id_servicio" id="id_servicio" required>
                </div>

                <button type="submit" class="btn">Asignar</button>
            </form>

            <div class="back-link">
                <a href="?path=encargados/dashboard">Volver al Panel del Encargado</a>
            </div>
        </div>
    <?php endif; ?>
</div>

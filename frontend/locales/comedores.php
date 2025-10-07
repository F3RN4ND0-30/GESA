<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GESA - LOCALES</title>
    <link rel="stylesheet" href="../../backend/css/lugares/direcciones.css" />
    <!-- DataTables CSS v1.13.6 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>

    <?php include __DIR__ . '/../navbar/navbar.php'; ?>

    <div class="container">
        <div class="table-card">
            <div class="header-flex">
                <h2>GESA - COMEDORES</h2>
                <button id="openModalBtn">+ Agregar Lugar</button>
            </div>

            <table id="placesTable" class="display">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Beneficiarios</th>
                        <th>Encargado</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="placesTableBody">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar lugar -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span id="closeModalBtn" class="close">&times;</span>
            <h3>Agregar Nuevo Lugar</h3>
            <form id="placeForm">
                <label> Nombre del lugar: </label>
                <input type="text" id="nombreLugar" placeholder="Nombre del lugar" required />
                <label> Número de beneficiarios: </label>
                <input type="number" id="beneficiarios" placeholder="Total de beneficiarios" required />
                <label> Nombre del encargado: </label>
                <input type="text" id="encargado" placeholder="Encargado" required />
                <label> Dirección: </label>
                <input type="text" id="direccion" placeholder="Dirección" required />
                <button type="submit">Guardar Lugar</button>
            </form>
        </div>
    </div>

    <!-- jQuery necesario para DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS v1.13.6 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="../../backend/js/lugares/direcciones.js"></script>
</body>

</html>
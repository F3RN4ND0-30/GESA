<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>GESA - ALMACEN</title>
    <link rel="stylesheet" href="../../backend/css/almacen/almacen.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

</head>

<body>

    <?php include __DIR__ . '/../navbar/navbar.php'; ?>

    <div class="container">
        <div class="table-card">
            <h2>Inventario / Almacén</h2>

            <table id="tablaAlmacen" class="display">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Marca</th>
                        <th>Unidad</th>
                        <th>Stock Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal de detalles -->
    <div id="modalDetalles" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalDetalles">&times;</span>
            <h3 id="tituloModal">Historial de movimientos</h3>
            <table class="history-table" id="tablaDetalleMovimientos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Movimiento</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="history-total" id="historyTotal" style="margin-left: 10cm;">Total: 0</div>
        </div>
    </div>

    <!-- JS dependencias -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="../../backend/js/almacen/almacen.js"></script>
</body>

</html>
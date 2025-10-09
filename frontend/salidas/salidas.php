<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>GESA - SALIDA (PECOSA)</title>
    <link rel="stylesheet" href="../../backend/css/salidas/salidas.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
</head>

<body>

    <?php include __DIR__ . '/../navbar/navbar.php'; ?>

    <div class="container">
        <h2 style="text-align: center; margin-top: 20px; color: white;">Registrar PECOSA - Salida de Productos</h2>

        <div class="main-content">
            <!-- 🟩 PANEL IZQUIERDO: Selección -->
            <div class="panel-left">
                <h3>Seleccionar Comedor y Productos</h3>

                <div class="form-group">
                    <label for="comedorSelect">Seleccione el Comedor</label>
                    <select id="comedorSelect" placeholder="Buscar comedor..."></select>
                </div>

                <div class="form-group">
                    <label for="productoSelect">Seleccione el Producto</label>
                    <select id="productoSelect" placeholder="Buscar producto..." disabled></select>
                </div>

                <button id="addProductBtn" disabled>Añadir Producto</button>
            </div>

            <!-- 🟦 PANEL DERECHO: Lista de productos seleccionados -->
            <div class="panel-right">
                <h3>Productos a Distribuir</h3>

                <div class="table-container">
                    <table id="listaProductos" class="display">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Calculada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="bottom-actions">
                    <div class="form-group">
                        <label for="observaciones">Observaciones:</label>
                        <input type="text" id="observaciones" placeholder="Opcional" />
                    </div>

                    <button id="confirmarSalidaBtn" class="btn-confirmar">Confirmar PECOSA</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Selectize -->
    <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

    <script src="../../backend/js/salidas/salidas.js"></script>
</body>

</html>
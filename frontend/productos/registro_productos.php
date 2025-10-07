<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GESA - ENTRADA</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="../../backend/css/registro/registro_producto.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- DataTables JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Selectize CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize/dist/css/selectize.default.css">

</head>

<body>

    <?php include __DIR__ . '/../navbar/navbar.php'; ?>

    <div class="container">
        <div class="form-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h2 style="margin: 0;">Registrar Producto</h2>
                <div style="margin-left: auto;">
                    <button
                        onclick="openModal()"
                        style="
                            padding: 10px 14px;
                            background-color: #007bff;
                            color: white;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 14px;
                            white-space: nowrap;
                        ">
                        + Unid. Medida
                    </button>
                </div>
            </div>

            <form id="productForm">
                <div class="form-grid">
                    <div style="margin-right: 15px;">
                        <label for="articulo">Artículo</label>
                        <input type="text" id="articulo" required placeholder="Ej. Monitor LED" />
                    </div>
                    <div style="margin-right: 10px;">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" required placeholder="Ej. LG" />
                    </div>
                    <div style="margin-right: 15px;">
                        <label for="unidad">Unidad de Medida</label>
                        <select id="unidad" required>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <button type="submit">Guardar Producto</button>
            </form>
        </div>

        <div class="table-card">
            <h3>Productos Registrados</h3>
            <table id="productosTable">
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th>Marca</th>
                        <th>Unidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->
    <div id="unidadModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Nueva Unidad de Medida</h3>
            <form id="unidadForm">
                <input type="text" id="descripcion" placeholder="Descripción" required />
                <button type="submit">Guardar Unidad</button>
            </form>
        </div>
    </div>

    <!-- jQuery necesario para DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS v1.13.6 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Selectize JS -->
    <script src="https://cdn.jsdelivr.net/npm/selectize/dist/js/standalone/selectize.min.js"></script>


    <script src="../../backend/js/productos/productos.js"></script>
</body>

</html>
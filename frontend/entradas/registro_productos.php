<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GESA - ENTRADA</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="../../backend/css/navbar/navbar.css" />
    <link rel="stylesheet" href="../../backend/css/registro/registro_producto.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>

    <?php include __DIR__ . '/../navbar/navbar.php'; ?>

    <div class="container">
        <div class="form-card">
            <h2>Registrar Producto</h2>
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
                        <input type="text" id="unidad" required placeholder="Ej. Unidad, Caja" />
                    </div>
                    <div style="margin-right: 10px;">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" required placeholder="Ej. 15" />
                    </div>
                </div>
                <button type="submit">Guardar Producto</button>
            </form>
        </div>

        <div class="table-card">
            <h3>Productos Registrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th>Marca</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr>
                        <td>Monitor LED</td>
                        <td>LG</td>
                        <td>Unidad</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Teclado Mecánico</td>
                        <td>Redragon</td>
                        <td>Unidad</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>Mouse Inalámbrico</td>
                        <td>Logitech</td>
                        <td>Caja</td>
                        <td>12</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const form = document.getElementById("productForm");
        const tableBody = document.getElementById("productTableBody");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const articulo = document.getElementById("articulo").value;
            const marca = document.getElementById("marca").value;
            const unidad = document.getElementById("unidad").value;
            const cantidad = document.getElementById("cantidad").value;

            const row = document.createElement("tr");
            row.innerHTML = `
        <td>${articulo}</td>
        <td>${marca}</td>
        <td>${unidad}</td>
        <td>${cantidad}</td>
      `;

            tableBody.appendChild(row);
            form.reset();
        });
    </script>
</body>

</html>
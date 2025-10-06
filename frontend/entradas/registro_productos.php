<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de Productos</title>
    <link rel="stylesheet" href="../../backend/css/registro/registro_producto.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="nav-left">
            <div class="logo">
                <i class="fas fa-cube"></i>
                <div class="logo-text">
                    <span class="logo-title">GESA</span>
                    <!-- Subtítulo removido para que escale a otras áreas -->
                    <!-- <span class="logo-subtitle">Vaso de Leche - PECOSA</span> -->
                </div>
            </div>

            <div class="nav-menu">
                <a href="../sisvis/escritorio.php" class="nav-link active">Dashboard</a>
                <a href="#" class="nav-link">Entradas</a>
                <a href="#" class="nav-link">PECOSA</a>
                <a href="#" class="nav-link">Almacén</a>
                <a href="#" class="nav-link">Reportes</a>
            </div>
        </div>

        <div class="nav-right">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar PECOSA, insumo o movimiento...">
            </div>

            <!-- Botón oculto en el dashboard: se mostrará en el módulo PECOSA -->
            <!-- <button class="btn-new-pecosa"><i class="fas fa-plus"></i> Nueva PECOSA</button> -->

            <div class="user-profile">
                <div class="user-avatar"><i class="fas fa-user"></i></div>
                <span>Johan</span>
            </div>
        </div>
    </nav>

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
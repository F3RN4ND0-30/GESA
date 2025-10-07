document.addEventListener("DOMContentLoaded", function () {
    let productosData = [];
    let carrito = [];

    $(document).ready(function () {
        const table = $('#listaProductos').DataTable();

        // 1. Cargar productos desde PHP
        fetch('../../backend/php/productos/listar_productos.php')
            .then(res => res.json())
            .then(data => {
                // Opcional: filtrar solo productos activos (Estado: 1)
                productosData = data.filter(p => p.Estado == 1);

                $('#productoSelect').selectize({
                    options: productosData.map(p => ({
                        value: p.IdProducto,
                        text: `${p.Descripcion} - ${p.Marca} (${p.Unidad})`
                    })),
                    create: false,
                    placeholder: 'Buscar producto...'
                });
            })
            .catch(err => {
                console.error('Error al cargar productos:', err);
                alert('No se pudieron cargar los productos');
            });

        // 2. Añadir producto al carrito
        $('#addProductBtn').click(function () {
            const selectize = $('#productoSelect')[0].selectize;
            const idProducto = selectize.getValue();
            const cantidad = parseInt($('#cantidadInput').val());

            if (!idProducto || !cantidad || cantidad <= 0) {
                alert('Selecciona un producto y una cantidad válida');
                return;
            }

            // Obtener el producto desde la lista
            const productoSeleccionado = productosData.find(p => p.IdProducto == idProducto);
            if (!productoSeleccionado) {
                alert('Producto no encontrado');
                return;
            }

            const nombreProducto = `${productoSeleccionado.Descripcion} - ${productoSeleccionado.Marca} (${productoSeleccionado.Unidad})`;

            // Verificar si ya está en el carrito
            const existente = carrito.find(p => p.id === idProducto);
            if (existente) {
                existente.cantidad += cantidad;
            } else {
                carrito.push({
                    id: idProducto,
                    nombre: nombreProducto,
                    cantidad
                });
            }

            // Limpiar campos
            selectize.clear();
            $('#cantidadInput').val('');
            actualizarTabla();
        });

        // 3. Eliminar producto del carrito
        $('#listaProductos tbody').on('click', '.btn-eliminar', function () {
            const index = $(this).data('index');
            carrito.splice(index, 1);
            actualizarTabla();
        });

        // 4. Actualizar tabla de carrito
        function actualizarTabla() {
            table.clear().draw();
            carrito.forEach((item, index) => {
                table.row.add([
                    item.nombre,
                    item.cantidad,
                    `<button class="btn-eliminar" data-index="${index}">Eliminar</button>`
                ]).draw(false);
            });
        }

        // 5. Confirmar entrada
        $('#confirmarEntradaBtn').click(function () {
            if (carrito.length === 0) {
                alert('Agrega al menos un producto');
                return;
            }

            const observaciones = $('#observaciones').val();

            $.ajax({
                url: '../../backend/php/entradas/guardar_entrada.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    observaciones,
                    productos: carrito
                }),
                success: function (res) {
                    if (res.success) {
                        alert('Entrada registrada exitosamente');
                        carrito = [];
                        actualizarTabla();
                        $('#observaciones').val('');
                    } else {
                        alert('Error: ' + res.error);
                    }
                },
                error: function (err) {
                    console.error('Error en la petición:', err);
                    alert('Error al guardar la entrada');
                }
            });
        });
    });
});

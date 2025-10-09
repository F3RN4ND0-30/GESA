document.addEventListener("DOMContentLoaded", function () {
    let productosData = [];
    let comedoresData = [];
    let carrito = [];
    let beneficiariosActuales = 0;

    $(document).ready(function () {
        const table = $('#listaProductos').DataTable();

        // 1. Cargar comedores
        fetch('../../backend/php/salidas/listar_comedores.php')
            .then(res => res.json())
            .then(data => {
                comedoresData = data;

                $('#comedorSelect').selectize({
                    options: comedoresData.map(c => ({
                        value: c.IdComedor,
                        text: `${c.Nombre} (${c.Beneficiarios} beneficiarios)`
                    })),
                    create: false,
                    onChange: function (value) {
                        const comedor = comedoresData.find(c => c.IdComedor == value);
                        if (comedor) {
                            beneficiariosActuales = parseInt(comedor.Beneficiarios);
                            $('#productoSelect')[0].selectize.enable();
                            $('#addProductBtn').prop('disabled', false);
                        } else {
                            beneficiariosActuales = 0;
                            $('#productoSelect')[0].selectize.disable();
                            $('#addProductBtn').prop('disabled', true);
                        }
                    }
                });
            })
            .catch(err => {
                console.error('Error al cargar comedores:', err);
                alert('No se pudieron cargar los comedores');
            });

        // 2. Cargar productos
        fetch('../../backend/php/productos/listar_productos.php')
            .then(res => res.json())
            .then(data => {
                productosData = data.filter(p => p.Estado == 1);

                $('#productoSelect').selectize({
                    options: productosData.map(p => ({
                        value: p.IdProducto,
                        text: `${p.Descripcion} - ${p.Marca} (${p.Unidad})`
                    })),
                    create: false,
                    placeholder: 'Buscar producto...',
                    disabled: true
                });
            })
            .catch(err => {
                console.error('Error al cargar productos:', err);
                alert('No se pudieron cargar los productos');
            });

        // 3. Añadir producto (con cantidad calculada)
        $('#addProductBtn').click(async function () {
            const selectize = $('#productoSelect')[0].selectize;
            const idProducto = selectize.getValue();

            if (!idProducto || beneficiariosActuales <= 0) {
                alert('Selecciona un comedor y un producto válido');
                return;
            }

            // Verificar si ya está en el carrito
            const existente = carrito.find(p => p.id === idProducto);
            if (existente) {
                alert('Este producto ya fue añadido');
                return;
            }

            // Obtener producto
            const producto = productosData.find(p => p.IdProducto == idProducto);
            if (!producto) {
                alert('Producto no encontrado');
                return;
            }

            // Obtener cantidadPorPersona desde el backend
            const cantidadPorPersona = await obtenerCantidadPorPersona(idProducto);
            if (cantidadPorPersona === null) {
                alert('No hay una cantidad por persona configurada para este producto');
                return;
            }

            const cantidadTotal = parseFloat((cantidadPorPersona * beneficiariosActuales).toFixed(2));

            const nombreProducto = `${producto.Descripcion} - ${producto.Marca} (${producto.Unidad})`;

            carrito.push({
                id: idProducto,
                nombre: nombreProducto,
                cantidad: cantidadTotal
            });

            selectize.clear();
            actualizarTabla();
        });

        // 4. Eliminar producto
        $('#listaProductos tbody').on('click', '.btn-eliminar', function () {
            const index = $(this).data('index');
            carrito.splice(index, 1);
            actualizarTabla();
        });

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

        // 5. Confirmar salida
        $('#confirmarSalidaBtn').click(function () {
            const comedorSelectize = $('#comedorSelect')[0].selectize;
            const idComedor = comedorSelectize.getValue();

            if (!idComedor) {
                alert('Debe seleccionar un comedor');
                return;
            }

            if (carrito.length === 0) {
                alert('Agrega al menos un producto');
                return;
            }

            const observaciones = $('#observaciones').val();

            $.ajax({
                url: '../../backend/php/salidas/guardar_salida.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    idComedor,
                    observaciones,
                    productos: carrito
                }),
                success: function (res) {
                    if (res.success) {
                        alert('PECOSA registrada exitosamente');
                        carrito = [];
                        actualizarTabla();
                        $('#observaciones').val('');
                        $('#comedorSelect')[0].selectize.clear();
                        $('#productoSelect')[0].selectize.disable();
                        $('#addProductBtn').prop('disabled', true);
                    } else {
                        alert('Error: ' + res.error);
                    }
                },
                error: function (err) {
                    console.error('Error en la petición:', err);
                    alert('Error al guardar la salida');
                }
            });
        });

        // Función auxiliar para obtener la cantidad por persona
        async function obtenerCantidadPorPersona(idProducto) {
            try {
                const res = await fetch(`../../backend/php/salidas/obtener_cantidad_por_persona.php?idProducto=${idProducto}`);
                const data = await res.json();
                if (data.success && data.cantidadPorPersona) {
                    return parseFloat(data.cantidadPorPersona);
                } else {
                    return null;
                }
            } catch (err) {
                console.error('Error al obtener cantidad por persona:', err);
                return null;
            }
        }
    });
});

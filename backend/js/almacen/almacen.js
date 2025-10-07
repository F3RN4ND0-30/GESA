document.addEventListener("DOMContentLoaded", function () {
    $(document).ready(function () {
        // Inicializar DataTable del inventario
        const tableInventario = $('#tablaAlmacen').DataTable({
            ajax: {
                url: '../../backend/php/almacen/listar_almacen.php',
                dataSrc: ''
            },
            columns: [{
                data: 'Descripcion'
            },
            {
                data: 'Marca'
            },
            {
                data: 'Unidad'
            },
            {
                data: 'StockTotal'
            },
            {
                data: null,
                defaultContent: '<button class="btn-detalles">Detalles</button>',
                orderable: false
            }
            ]
        });

        // Manejar click en botón Detalles
        $('#tablaAlmacen tbody').on('click', '.btn-detalles', function () {
            const data = tableInventario.row($(this).closest('tr')).data();
            const idProducto = data.IdProducto;
            const nombre = data.Descripcion;
            const marca = data.Marca;
            const unidad = data.Unidad;

            // Cambiar título del modal
            $('#tituloModal').text(`${nombre} | ${marca} | ${unidad}`);

            // Cargar movimientos desde backend
            fetch(`../../backend/php/almacen/listar_movimientos_producto.php?id=${idProducto}`)
                .then(res => res.json())
                .then(movs => {
                    const tbody = $('#tablaDetalleMovimientos tbody');
                    tbody.empty();
                    let total = 0;

                    movs.forEach(m => {
                        const tipo = m.tipo;
                        const cantidadNum = parseFloat(m.cantidad); // ✅ CONVERSIÓN AQUÍ
                        const signo = (tipo === 'entrada') ? '+' : '-';
                        const monto = signo + cantidadNum.toFixed(2);

                        if (tipo === 'entrada') {
                            total += cantidadNum;
                        } else {
                            total -= cantidadNum;
                        }

                        const rowHtml = `
                                <tr>
                                    <td>${m.fecha}</td>
                                    <td>${tipo === 'entrada' ? 'Entrada' : 'Salida'}</td>
                                    <td>${monto}</td>
                                </tr>
                            `;
                        tbody.append(rowHtml);
                    });

                    $('#historyTotal').text(`Total: ${total.toFixed(2)}`);
                    $('#modalDetalles').show();
                })
                .catch(err => {
                    console.error('Error al cargar movimientos:', err);
                    alert('No se pudieron cargar los movimientos');
                });
        });

        // Cerrar modal
        $('#closeModalDetalles').click(function () {
            $('#modalDetalles').hide();
        });

        window.onclick = function (event) {
            if (event.target == document.getElementById('modalDetalles')) {
                $('#modalDetalles').hide();
            }
        };
    });
})
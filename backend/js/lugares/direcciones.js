$(document).ready(function () {
    const modal = $('#modal');
    const openModalBtn = $('#openModalBtn');
    const closeModalBtn = $('#closeModalBtn');
    const form = $('#placeForm');

    // Inicializar DataTable con datos desde el servidor
    const table = $('#placesTable').DataTable({
        ajax: {
            url: '../../backend/php/locales/listar_comedores.php',
            dataSrc: ''
        },
        columns: [
            { data: 'Nombre' },
            { data: 'Beneficiarios' },
            { data: 'Encargado' },
            { data: 'Direccion' },
            {
                data: null,
                defaultContent: '<button class="edit-btn">Editar</button>',
                orderable: false
            }
        ],
        createdRow: function (row, data) {
            // ✅ Asignar el ID del comedor como atributo data-id en la fila
            $(row).attr('data-id', data.IdComedor);
        },
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: false,
        pageLength: 5,
        language: {
            search: "Buscar:",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            infoEmpty: "Mostrando 0 a 0 de 0 entradas",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });

    // Abrir modal
    openModalBtn.click(() => modal.show());

    // Cerrar modal y resetear formulario
    closeModalBtn.click(() => {
        modal.hide();
        form[0].reset();
    });

    // Cerrar modal al hacer clic fuera del contenido
    $(window).click(function (event) {
        if ($(event.target).is(modal)) {
            modal.hide();
            form[0].reset();
        }
    });

    // Enviar nuevo comedor (alta)
    form.submit(function (e) {
        e.preventDefault();

        const data = {
            nombre: $('#nombreLugar').val(),
            beneficiarios: $('#beneficiarios').val(),
            encargado: $('#encargado').val(),
            direccion: $('#direccion').val()
        };

        $.post('../../backend/php/locales/guardar_comedor.php', data, function (response) {
            if (response.success) {
                modal.hide();
                form[0].reset();
                table.ajax.reload(null, false); // recargar tabla sin reiniciar paginación
            } else {
                alert('Error al guardar: ' + (response.error || 'Error desconocido'));
            }
        }, 'json');
    });

    // Modo edición en la tabla
    $('#placesTable tbody').on('click', '.edit-btn', function () {
        const row = $(this).closest('tr');
        const button = $(this);

        if (!row.hasClass('editing')) {
            row.addClass('editing');
            row.find('td').each(function (i) {
                if (i < 4) { // Las 4 columnas editables
                    const valor = $(this).text();
                    $(this).html(`<input type="text" value="${valor}" class="edit-input">`);
                }
            });
            button.text('Guardar');
        } else {
            const id = row.attr('data-id');
            const inputs = row.find('input');
            const nombre = inputs.eq(0).val();
            const beneficiarios = inputs.eq(1).val();
            const encargado = inputs.eq(2).val();
            const direccion = inputs.eq(3).val();

            $.post('../../backend/php/locales/actualizar_comedor.php', {
                id,
                nombre,
                beneficiarios,
                encargado,
                direccion
            }, function (res) {
                if (res.success) {
                    row.removeClass('editing');
                    inputs.eq(0).parent().text(nombre);
                    inputs.eq(1).parent().text(beneficiarios);
                    inputs.eq(2).parent().text(encargado);
                    inputs.eq(3).parent().text(direccion);
                    button.text('Editar');
                } else {
                    alert('Error al actualizar: ' + (res.error || 'Desconocido'));
                }
            }, 'json');
        }
    });
});

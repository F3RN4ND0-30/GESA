$(document).ready(function () {
    // Inicializar DataTables sobre la tabla
    const table = $('#placesTable').DataTable({
        // Puedes ajustar opciones si quieres
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: false,
        pageLength: 5,
        // idioma en español (opcional)
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

    // abrir/cerrar modal, agregar lugar, editar etc como antes

    const modal = document.getElementById("modal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const form = document.getElementById("placeForm");
    const tableBody = document.getElementById("placesTableBody");

    openModalBtn.onclick = () => modal.style.display = "block";
    closeModalBtn.onclick = () => modal.style.display = "none";
    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = "none";
    };

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const nombre = document.getElementById("nombreLugar").value;
        const beneficiarios = document.getElementById("beneficiarios").value;
        const encargado = document.getElementById("encargado").value;
        const direccion = document.getElementById("direccion").value;

        // Añadir nueva fila al DataTable
        table.row.add([
            nombre,
            beneficiarios,
            encargado,
            direccion,
            '<button class="edit-btn">Editar</button>'
        ]).draw(false);

        modal.style.display = "none";
        form.reset();
    });

    // Manejar el editar
    $('#placesTable tbody').on('click', '.edit-btn', function () {
        const tr = $(this).closest('tr');
        const rowData = table.row(tr);

        if (!tr.hasClass('editing')) {
            // poner en modo edición
            tr.addClass('editing');
            // hacer las celdas editables visualmente
            tr.find('td').each(function (index) {
                if (index < 4) {
                    const currentText = $(this).text();
                    $(this).html('<input type="text" value="' + currentText + '">');
                }
            });
            $(this).text('Guardar');
        } else {
            // guardar cambios
            tr.removeClass('editing');
            tr.find('td').each(function (index) {
                if (index < 4) {
                    const input = $(this).find('input');
                    if (input.length) {
                        $(this).text(input.val());
                    }
                }
            });
            $(this).text('Editar');
            // actualizar la fila en DataTable
            rowData.invalidate();
        }
    });
});

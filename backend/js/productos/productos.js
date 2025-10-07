document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {
        $('#productosTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        cargarProductos();
        cargarUnidades();
    });

    const form = document.getElementById("productForm");
    const unidadForm = document.getElementById("unidadForm");

    // Guardar producto en la BD
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const articulo = document.getElementById("articulo").value;
        const marca = document.getElementById("marca").value;
        const unidad = document.getElementById("unidad").value;

        fetch("../../backend/php/productos/guardar_producto.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `articulo=${encodeURIComponent(articulo)}&marca=${encodeURIComponent(marca)}&unidad=${unidad}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    cargarProductos();
                } else {
                    alert("Error al guardar producto: " + data.error);
                }
            });
    });

    // Guardar nueva unidad de medida
    unidadForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const descripcion = document.getElementById("descripcion").value;

        fetch("../../backend/php/productos/guardar_unidad.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `descripcion=${encodeURIComponent(descripcion)}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Unidad registrada con éxito");
                    unidadForm.reset();
                    closeModal();
                    cargarUnidades();
                } else {
                    alert("Error al guardar unidad: " + data.error);
                }
            });
    });

    // Modal
    window.openModal = function () {
        document.getElementById("unidadModal").style.display = "block";
    }

    window.closeModal = function () {
        document.getElementById("unidadModal").style.display = "none";
    }

    window.onclick = function (event) {
        const modal = document.getElementById("unidadModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Función para cargar productos desde la BD y mostrar con checkbox Estado
    function cargarProductos() {
        fetch("../../backend/php/productos/listar_productos.php")
            .then(res => res.json())
            .then(data => {
                const table = $('#productosTable').DataTable();
                table.clear(); // Borra los datos actuales

                data.forEach(p => {
                    const estadoActivo = parseInt(p.Estado) === 1 ? 'checked' : '';
                    const switchHTML = `
                    <label class="switch">
                        <input type="checkbox" ${estadoActivo} data-id="${p.IdProducto}" class="estado-toggle">
                        <span class="slider"></span>
                    </label>
                `;

                    table.row.add([
                        p.Descripcion,
                        p.Marca,
                        p.Unidad,
                        switchHTML
                    ]);
                });

                table.draw();

                // Reasociar eventos después de redibujar
                document.querySelectorAll('.estado-toggle').forEach(toggle => {
                    toggle.addEventListener('change', function () {
                        const idProducto = this.getAttribute('data-id');
                        const nuevoEstado = this.checked ? 1 : 0;

                        fetch("../../backend/php/productos/actualizar_estado.php", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id=${idProducto}&estado=${nuevoEstado}`
                        })
                            .then(res => res.json())
                            .then(response => {
                                if (!response.success) {
                                    alert("Error al actualizar el estado.");
                                }
                            });
                    });
                });
            });
    }


    // Función para cargar unidades al select
    function cargarUnidades() {
        fetch('../../backend/php/productos/listar_unidades.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById("unidad");

                // Agrega la opción por defecto como guía
                select.innerHTML = '<option value="">-- Seleccionar --</option>';

                data.forEach(unidad => {
                    const option = document.createElement("option");
                    option.value = unidad.IdUnidadMedida;
                    option.textContent = unidad.Descripcion;
                    select.appendChild(option);
                });

                // Destruye selectize si ya está activo
                if ($(select)[0].selectize) {
                    $(select)[0].selectize.destroy();
                }

                // Inicializa Selectize
                const selectizeInstance = $(select).selectize({
                    placeholder: 'Selecciona una unidad...',
                    allowEmptyOption: true,
                    selectOnTab: true,
                    onFocus: function () {
                        const control = this;
                        // Si la opción seleccionada es la por defecto, bórrala al enfocar
                        if (control.getValue() === "") {
                            control.clear(true);  // borra el valor
                        }
                    }
                });
            });
    }


});

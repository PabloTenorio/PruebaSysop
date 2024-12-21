// Declarar una variable global para la DataTable
let table;

let toolbar = document.createElement('div');
toolbar.innerHTML = '<a href="/home"><button class="NewUser">Volver al inicio</button></a>';
 
$(document).ready(function () {
    table = new DataTable('#example', {
        layout: {
            topStart: toolbar // Respetar el toolbar existente
        },
        responsive: true, // Habilita el diseño responsivo
        columnDefs: [
            {
                targets: 0, // Oculta la primera columna (Número de Empleado)
                visible: false,
                className: "d-md-table-cell"
            }
        ]
    });
        // Detectar cambios de tamaño de pantalla y ajustar la visibilidad
        $(window).on('resize', function () {
            const width = $(window).width();
    
            if (width < 767) {
                table.column(0).visible(false);
            } else {
                table.column(0).visible(true);
            }
        }).trigger('resize');
});

$(document).on('change', '.reactivate-switch', function () {
    let switchButton = $(this);
    let actionUrl = switchButton.data('url');
    let isChecked = switchButton.prop('checked'); // Verificar si el switch está activado

    // Confirmar la acción con SweetAlert
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esto reactivará al usuario y lo moverá a la tabla de empleados activos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        title: '¡Reactivado!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });

                    // Remover la fila de la tabla de empleados inactivos
                    let row = switchButton.closest('tr');
                    table.row(row).remove().draw(false); // Eliminar y redibujar sin reiniciar la paginación
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo reactivar al usuario.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });

                    // Revertir el estado del switch si hay un error
                    switchButton.prop('checked', false);
                }
            });
        } else {
            // Revertir el estado del switch si se cancela la acción
            switchButton.prop('checked', false);
        }
    });
});

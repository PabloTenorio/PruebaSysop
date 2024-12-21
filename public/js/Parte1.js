// Declarar una variable global para la DataTable
let table;

let toolbar = document.createElement('div');
toolbar.innerHTML = '<a href="/NewUser"><button class="NewUser">Crear Usuario <i class="fi fi-rr-add"></i></button></a>' + '<a href="/users/inactive"><button class="InactiveUser">Empleados Inactivos</button></a>';
 
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

/*Solicitud PUT mediante Ajax*/
$(document).on('submit', '.update-user-form', function (e) {
    e.preventDefault(); // Evitar la recarga de la página

    let form = $(this)[0]; // Obtener el formulario como objeto DOM
    let formData = new FormData(form); // Crear un objeto FormData
    let actionUrl = $(this).attr('action'); // Obtener la URL de la acción

    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        processData: false, // Evitar que jQuery procese los datos
        contentType: false, // Evitar que jQuery establezca el tipo de contenido
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            Swal.fire({
                title: '¡Éxito!',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });

            // Actualizar la fila en la DataTable
            let user = response.user;
            let row = table.row(function (idx, data, node) {
                return data[0] == user.id; // Suponiendo que la primera columna es el ID
            });

            row.data([
                user.id,
                user.nombre,
                `${user.tipo_usuario.charAt(0).toUpperCase() + user.tipo_usuario.slice(1)} ${user.activo ? 'Sí' : 'No'}`,
                `<button class="btn btn-info btn-sm">Ver más</button>
                 <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal${user.id}">Editar</button>
                 <button class="btn btn-danger btn-sm delete-user" data-url="/users/${user.id}">Eliminar</button>`
            ]).draw(false);

            // Cerrar el modal
            $(`#editUserModal${user.id}`).modal('hide');
        },
        error: function (xhr, status, error) {
            console.error('Error en la actualización:', xhr.responseText);
        }
    });
});


//Ajax para manejar la eliminación o desactivación del empleado

$(document).on('click', '.delete-user', function (e) {
    e.preventDefault(); // Prevenir la acción por defecto
    const actionUrl = $(this).data('url'); // Asegúrate de tener un atributo data-url con la URL de eliminación
    const userId = $(this).data('id'); // Asegúrate de tener un atributo data-id con el ID del usuario

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción marcará al usuario como inactivo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: actionUrl,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // Token CSRF
                },
                success: function (response) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    });

                    // Eliminar dinámicamente la fila de la DataTable
                    const row = $(`#user-row-${userId}`);
                    table.row(row).remove().draw(false); // Remover y redibujar
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al eliminar el usuario.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    });
                },
            });
        }
    });
});



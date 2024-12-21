//Manejo de sweet alert para el switch de validar usuario
document.getElementById('activoSwitch').addEventListener('change', function (e) {
    if (this.checked) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Estás activando a este usuario. ¿Deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) {
                this.checked = false; // Desmarcar si el usuario cancela.
            }
        });
    } else {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Estás desactivando a este usuario. ¿Deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) {
                this.checked = true; // Marcar nuevamente si el usuario cancela.
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Verificar si hay un valor previo en el campo de correo
    const correoInput = document.getElementById('correo');
    const nombreInput = document.getElementById('nombre');
    const fechaInput = document.getElementById('fecha_nacimiento');
    const tipoUsuarioInput = document.getElementById('tipo_usuario');

    if (!correoInput.value) {
        generarCorreo(); // Generar el correo si está vacío
    }

    // Generar correo al cambiar los valores
    nombreInput.addEventListener('input', generarCorreo);
    fechaInput.addEventListener('input', generarCorreo);
    tipoUsuarioInput.addEventListener('change', generarCorreo);
});

function generarCorreo() {
    const nombreInput = document.getElementById('nombre').value.trim().toLowerCase();
    const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    const tipoUsuario = document.getElementById('tipo_usuario').value;

    let nombres = nombreInput.split(' ');
    let primerNombre = nombres[0] || '';
    let primerApellido = nombres[2] || '';
    let anioNacimiento = fechaNacimiento ? fechaNacimiento.split('-')[0].slice(-2) : '';
    let sufijo = tipoUsuario === 'admin' ? 'adm' : tipoUsuario === 'empleado' ? 'emp' : 'exe';

    // Generar el correo
    const correo = `${primerNombre}.${primerApellido}${anioNacimiento}.${sufijo}@sysop.com`;
    document.getElementById('correo').value = correo;
}

//Logica para validar que el correo y/o el rfc no existan
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("create-user-form");
    const correoInput = document.getElementById("correo");
    const rfcInput = document.getElementById("rfc");
    const tipoUsuarioInput = document.getElementById("tipo_usuario");
    const validateUrl = form.getAttribute("data-validate-url");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const correo = correoInput.value.trim();
        const rfc = rfcInput.value.trim();
        const tipo_usuario = tipoUsuarioInput.value;

        // Realizar validación vía AJAX
        fetch(validateUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ correo, rfc, tipo_usuario }),
        })
            .then((response) => response.json())
            .then((data) => {
                // Limpiar errores previos
                document.getElementById("correo-error").textContent = "";
                document.getElementById("rfc-error").textContent = "";

                if (data.success) {
                    form.submit(); // Si no hay errores, enviar el formulario
                } else {
                    if (data.errors.correo) {
                        const correoErrorElement = document.getElementById("correo-error");
                        if (correoErrorElement) {
                            correoErrorElement.textContent = data.errors.correo;
                        } else {
                            console.error("El elemento correo-error no existe en el DOM.");
                        }
                    }
                    
                    if (data.errors.rfc) {
                        const rfcErrorElement = document.getElementById("rfc-error");
                        if (rfcErrorElement) {
                            rfcErrorElement.textContent = data.errors.rfc;
                        } else {
                            console.error("El elemento rfc-error no existe en el DOM.");
                        }
                    }
                    
                }
            })
            .catch((error) => console.error("Error en la validación:", error));
    });
});





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="/css/Parte1.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Usuario</title>
</head>
<body>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card p-4 shadow-lg custom-card-bg mb-3">
            <div class="row">
                <div class="col-lg-4 col-12 volver">
                    <a href="/home">
                        <button type="submit" class="btn btn-danger">Volver al Inicio</button>
                    </a>
                </div>
                <div class="col-lg-4 col-12 titulo">
                    <h2 class="mb-4 text-center">Panel de Empleados</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('NewUser') }}" enctype="multipart/form-data" data-validate-url="{{ route('validateUser') }}" id="create-user-form">
                @csrf
                <div class="row">
                    <div class="mb-3 col-lg-5 col-12">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required minlength="2" maxlength="100">
                        <div class="form-text">Ingresa el nombre completo del empleado.</div>
                        @error('nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-1"></div>
                    
                    <div class="mb-3 col-lg-5 col-12">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="correo" 
                            name="correo" 
                            value="{{ old('correo') }}" 
                            readonly
                            placeholder="Se generará automáticamente"
                        >
                        <div class="form-text">El correo será generado automáticamente basado en el nombre ingresado.</div>
                        @error('correo')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div id="correo-error" class="error-message"></div>
                    </div>

                    <div class="mb-3 col-lg-5 col-12">
                        <label for="password" class="form-label">Contraseña</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="password" 
                            readonly 
                            placeholder="Se generará automáticamente por seguridad"
                        >
                        <div class="form-text">La contraseña será generada automáticamente por seguridad.</div>
                    </div>

                    <div class="col-1"></div>

                    <div class="mb-3 col-lg-5 col-12">
                        <label for="foto" class="form-label">Foto de perfil (Opcional)</label>
                        <input type="file" 
                            class="form-control" 
                            id="foto" 
                            name="foto" 
                            accept="image/*">
                        <div class="form-text">
                            Subir una imagen en formato JPG, PNG o WEBP. Tamaño máximo recomendado: 2 MB.
                        </div>
                        @error('foto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-lg-5 col-12">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input 
                            type="tel" 
                            class="form-control" 
                            id="telefono" 
                            name="telefono" 
                            value="{{ old('telefono') }}" 
                            required 
                            pattern="[0-9]{10,20}">
                        <div class="form-text">Ingresa el teléfono, solo dígitos (10 a 20 caracteres).</div>
                        @error('telefono')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-1"></div>
                    
                    <div class="mb-3 col-lg-5 col-12">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="fecha_nacimiento" 
                            name="fecha_nacimiento" 
                            value="{{ old('fecha_nacimiento') }}" 
                            required>
                        <div class="form-text">Selecciona una fecha de nacimiento válida.</div>
                        @error('fecha_nacimiento')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 col-lg-5 col-12">
                        <label for="rfc" class="form-label">RFC</label>
                        <input 
                            style="text-transform: uppercase" 
                            type="text" 
                            class="form-control" 
                            id="rfc" 
                            name="rfc" 
                            value="{{ old('rfc') }}" 
                            required>
                        <div class="form-text">El RFC debe contener 4 letras iniciales, 6 números de fecha de nacimiento y 3 caracteres opcionales.</div>
                        @error('rfc')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div id="rfc-error" class="error-message"></div>
                    </div>
                    <div class="col-1"></div>

                    <div class="mb-3 col-lg-5 col-12">
                        <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                        <select 
                            class="form-select" 
                            id="tipo_usuario" 
                            name="tipo_usuario" 
                            required>
                            <option value="" disabled {{ old('tipo_usuario') ? '' : 'selected' }}>Selecciona un tipo de usuario</option>
                            <option value="admin" {{ old('tipo_usuario') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="empleado" {{ old('tipo_usuario') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                            <option value="ejecutivo" {{ old('tipo_usuario') == 'ejecutivo' ? 'selected' : '' }}>Ejecutivo de Ventas</option>
                        </select>
                        <div class="form-text">Elige el rol que se asignará al usuario.</div>
                        @error('tipo_usuario')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Switch para definir si el usuario está activo -->
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label me-3" for="activoSwitch">Usuario Activo</label>
                        <div class="form-check form-switch">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="activoSwitch" 
                                name="activo" 
                                {{ old('activo') ? 'checked' : '' }}>
                        </div>
                        @error('activo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-80">Registrar</button>

                    </div>
                </div>
            </form>

        </div>
    </div>
    

    @if(session('success'))
    <script>
        Swal.fire({
            title: '¡Usuario Creado con Éxito!',
            html: `
                <p>El usuario ha sido registrado correctamente.</p>
                <p><strong>Correo:</strong> {{ session('correo') }}</p>
                <p><strong>Contraseña:</strong> {{ session('password') }}</p>
                <p>Por favor, comparta esta información con el empleado.</p>
            `,
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

    <script src="/js/Parte1Create.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
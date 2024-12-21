<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados Inactivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/Parte1.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <div class="container-fluid mt-5 d-flex justify-content-center">
        <div class="card p-4 shadow-lg custom-card-bg">
            <h1>Empleados Inactivos</h1>
            <table id="example" class="display cell-border">
                <thead>
                    <tr>
                        <th class="d-none d-md-table-cell">Número de Empleado</th>
                        <th>Nombre</th>
                        <th>Tipo de Usuario / Activo</th>
                        <th>Más Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->nombre }}</td>
                        <td>{{ ucfirst($user->tipo_usuario) }}  {{ $user->activo ? 'Sí' : 'No' }}</td>
                        <td>
                            <!-- Botón para ver detalles -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}">Ver más</button>
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input reactivate-switch" 
                                    type="checkbox" 
                                    id="reactivateSwitch{{ $user->id }}" 
                                    data-url="{{ route('reactivateUser', $user->id) }}">
                                <label class="form-check-label" for="reactivateSwitch{{ $user->id }}">Reactivar</label>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal para ver más detalles -->
                    <div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="viewUserModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewUserModalLabel{{ $user->id }}">Detalles de {{ $user->nombre }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nombre:</strong> {{ $user->nombre }}</p>
                                    <p><strong>Teléfono:</strong> {{ $user->telefono }}</p>
                                    <p><strong>Correo:</strong> {{ $user->correo }}</p>
                                    <p><strong>Fecha de Nacimiento:</strong> {{ $user->fecha_nacimiento }}</p>
                                    <p><strong>RFC:</strong> {{ $user->rfc }}</p>
                                    <p><strong>Tipo de Usuario:</strong> {{ ucfirst($user->tipo_usuario) }}</p>
                                    <p><strong>Activo:</strong> {{ $user->activo ? 'Sí' : 'No' }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/js/Parte1EmpleadosInactivos.js"></script>
</body>
</html>
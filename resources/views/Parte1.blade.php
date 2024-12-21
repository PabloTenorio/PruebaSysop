@extends('layouts.app')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión y Admin de Usuarios</title>
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
            <div class="row">
                <div class="col-lg-4 col-12"></div>
                <div class="col-lg-4 col-12 titulo">
                    <h2 class="mb-4 text-center">Panel de Empleados</h2>
                </div>
                <div class="col-lg-4 col-12 logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
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
                    <tr id="user-row-{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->nombre }}</td>
                        <td>{{ ucfirst($user->tipo_usuario) }}  {{ $user->activo ? 'Sí' : 'No' }}</td>
                        <td>
                            <!-- Botón para ver detalles -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}">Ver más</button>
                            <!-- Botón para editar -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">Editar</button>
                            <!-- Botón para eliminar -->
                            <button class="btn btn-danger btn-sm delete-user" data-url="{{ route('deleteUser', $user->id) }}" data-id="{{ $user->id }}">Eliminar</button>
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

                    <!-- Modal para editar información -->
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Editar {{ $user->nombre }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('updateUser', $user->id) }}" method="PUT"  enctype="multipart/form-data" class="update-user-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nombre{{ $user->id }}" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre{{ $user->id }}" name="nombre" value="{{ $user->nombre }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="telefono{{ $user->id }}" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono{{ $user->id }}" name="telefono" value="{{ $user->telefono }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="correo{{ $user->id }}" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo{{ $user->id }}" name="correo" value="{{ $user->correo }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password{{ $user->id }}" class="form-label">Nueva Contraseña (Opcional)</label>
                                            <input type="password" class="form-control" id="password{{ $user->id }}" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_confirmation{{ $user->id }}" class="form-label">Confirmar Contraseña</label>
                                            <input type="password" class="form-control" id="password_confirmation{{ $user->id }}" name="password_confirmation">
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Foto de perfil (Opcional)</label>
                                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_nacimiento{{ $user->id }}" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento{{ $user->id }}" name="fecha_nacimiento" value="{{ $user->fecha_nacimiento }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="rfc{{ $user->id }}" class="form-label">RFC</label>
                                            <input type="text" class="form-control" id="rfc{{ $user->id }}" name="rfc" value="{{ $user->rfc }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tipo_usuario{{ $user->id }}" class="form-label">Tipo de Usuario</label>
                                            <select class="form-select" id="tipo_usuario{{ $user->id }}" name="tipo_usuario" required>
                                                <option value="admin" {{ $user->tipo_usuario == 'admin' ? 'selected' : '' }}>Administrador</option>
                                                <option value="empleado" {{ $user->tipo_usuario == 'empleado' ? 'selected' : '' }}>Empleado</option>
                                                <option value="ejecutivo" {{ $user->tipo_usuario == 'ejecutivo' ? 'selected' : '' }}>Ejecutivo</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="activo{{ $user->id }}" name="activo" {{ $user->activo ? 'checked' : '' }}>
                                            <label class="form-check-label" for="activo{{ $user->id }}">Usuario Activo</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
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
<script src="/js/Parte1.js"></script>
</body>
</html>
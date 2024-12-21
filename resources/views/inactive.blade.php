@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/Parte1.css">
</head>
<body>

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body text-center">
            <h1 class="mb-4">¡Bienvenido, {{ $user->nombre }}!</h1>
            <h2>Actualmente tu cuenta está suspendida o Inactiva por lo que no puedes acceder a ningun apartado como Empleado comunicate con los responsables de tu área en caso de alguna duda o aclaración</h2>
            <h4>Que tengas un excelente día! :D</h4>
        </div>
        <div class="col-12 d-flex justify-content-center mb-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection

</body>
</html>
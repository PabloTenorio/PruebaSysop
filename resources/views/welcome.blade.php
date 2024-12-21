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
            <h2 class="mb-4">¡Bienvenido, {{ $user->nombre }}!</h2>
            <div class="profile-picture mb-3">
            @if ($user->foto)
                <img src="{{ asset($user->foto) }}" alt="Foto de perfil" class="img-fluid rounded-circle" width="200">
            @else
                <img src="{{ asset('images/defaultimage.webp') }}" alt="Foto por defecto" class="img-fluid rounded-circle" width="200">
            @endif
            </div>
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
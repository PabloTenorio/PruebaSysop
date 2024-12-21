<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Parte1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->where('activo', '=', 1);
        return view('Parte1', compact('users'));
    }

    public function inactive()
    {
        $users = User::all()->where('activo', '=', 0);
        return view('Parte1/EmpleadosInactivos', compact('users'));
    }

    public function reactivate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['activo' => 1]); // Cambiar el estado a activo

        return response()->json([
            'success' => true,
            'message' => 'Usuario reactivado correctamente.',
            'id' => $id
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Parte1/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $validated = $request->validate([
            'nombre' => 'required|min:2|max:100',
            'correo' => 'required|email|max:100',
            'telefono' => 'required|regex:/^\d{10,20}$/',
            'fecha_nacimiento' => 'required|date',
            'rfc' => 'required|regex:/^[A-ZÑ]{4}\d{6}[A-Z\d]{3}?$/i',
            'tipo_usuario' => 'required|in:admin,empleado,ejecutivo',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        $validated['activo'] = $request->has('activo') ? 1 : 0;
 
        // Generar correo y contraseña
        $email = $validated['correo'];
        $password = Str::random(12);

        // Subir la imagen si existe, de lo contrario usar una por defecto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $rutaFoto = 'images/' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('images'), $rutaFoto);
            $validated['foto'] = $rutaFoto; // Guarda la ruta relativa en la base de datos
        } else {
            $validated['foto'] = 'images/defaultimage.png'; // Ruta por defecto si no se sube imagen
        }
    
        User::create([
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'correo' => $validated['correo'],
            'password' => Hash::make($password),
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'rfc' => strtoupper($validated['rfc']),
            'tipo_usuario' => $validated['tipo_usuario'],
            'activo' => $request->has('activo') ? 1 : 0,
            'foto' => $validated['foto']
        ]);
    
        return redirect()->route('CreateUser')->with([
            'success' => 'Usuario creado correctamente.',
            'correo' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $user = User::findOrFail($id);
    
        // Validar todos los campos
        $validated = $request->validate([
            'nombre' => 'required|min:2|max:100',
            'telefono' => 'required|regex:/^\d{10,20}$/',
            'correo' => 'required|email|max:100|unique:users,correo,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'fecha_nacimiento' => 'nullable|date',
            'rfc' => 'nullable|regex:/^[A-ZÑ]{4}\d{6}[A-Z\d]{3}?$/i',
            'tipo_usuario' => 'required|in:admin,empleado,ejecutivo',
        ]);
    
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); // No incluye la contraseña en la actualización
        }

        // Convertir "activo" a 1 o 0 (si está presente)
        $validated['activo'] = $request->has('activo') ? 1 : 0;
    
        // Si se proporciona una contraseña, hashearla
        if ($request->hasFile('foto')) {
            // Eliminar la imagen anterior si existe
            if ($user->foto && $user->foto !== 'images/defaultimage.png') {
                $rutaCompleta = public_path($user->foto);
                if (file_exists($rutaCompleta)) {
                    unlink($rutaCompleta);
                }
            }
            // Subir la nueva imagen
            $foto = $request->file('foto');
            $rutaFoto = 'images/' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('images'), $rutaFoto);
            $validated['foto'] = $rutaFoto;
        }
    
        $user->update($validated);
    
        // Retornar la respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
            'user' => $user,
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);
    
        // Cambiar el estado de activo a 0
        $user->update(['activo' => 0]);
    
        // Retornar una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.',
            'id' => $id, // Retornar el ID del usuario eliminado
        ]);
    }

    public function validateUser(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'rfc' => 'required|regex:/^[A-ZÑ]{4}\d{6}[A-Z\d]{3}?$/i',
        ]);
    
        $errors = [];
    
        // Validar correo con condiciones específicas (mismo nombre, puesto y año)
        $correoDuplicado = User::where('correo', $request->correo)
            ->where('tipo_usuario', $request->tipo_usuario)
            ->whereYear('created_at', now()->year)
            ->exists();
    
        if ($correoDuplicado) {
            $errors['correo'] = 'Este correo ya está registrado con el mismo puesto y año.';
        }
    
        // Validar RFC como único
        if (User::where('rfc', $request->rfc)->exists()) {
            $errors['rfc'] = 'Este RFC ya está registrado.';
        }
    
        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors]);
        }
    
        return response()->json(['success' => true]);
    }
    
    
}

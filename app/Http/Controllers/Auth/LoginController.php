<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email', // Cambiar de 'email' a 'correo'
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        
            // Redirigir según el rol
            switch (Auth::user()->tipo_usuario) {
                case 'admin':
                    return redirect()->route('home');
                case 'empleado':
                    return redirect()->route('welcome');
                case 'ejecutivo':
                    return redirect()->route('welcome');
                default:
                    return redirect()->route('login')->withErrors([
                        'correo' => 'Rol de usuario no válido.',
                    ]);
            }
        }
        
        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden.',
        ]);
    }
    

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    public function showLoginForm()
    {
        return view('Parte2.Login'); // Asegúrate de que exista la vista 'auth/login.blade.php'
    }

}

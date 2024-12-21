<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Parte3Controller extends Controller
{
    public function index()
    {
        
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $user = Auth::user();
        //dd($user);
        return view('welcome', compact('user')); // Envía solo el usuario autenticado a la vista
    }

    public function inactive(){
        $user = Auth::user();
        //dd($user);
        return view('inactive', compact('user'));
    }
}

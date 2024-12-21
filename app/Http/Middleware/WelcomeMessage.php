<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WelcomeMessage
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('welcome_message_shown') && Auth::check()) {
            Session::flash('success', '¡Bienvenido, ' . Auth::user()->nombre . ' y que tengas un excelente día:D!');
            Session::put('welcome_message_shown', true);
        }

        return $next($request);
    }
}


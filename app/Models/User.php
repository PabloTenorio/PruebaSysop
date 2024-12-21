<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'password',
        'fecha_nacimiento',
        'rfc',
        'tipo_usuario',
        'foto',
        'activo',
    ];

    protected $username = 'correo';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->tipo_usuario === 'admin';
    }

    public function isEmpleado()
    {
        return $this->tipo_usuario === 'empleado';
    }

    public function isEjecutivo()
    {
        return $this->tipo_usuario === 'ejecutivo';
    }

}

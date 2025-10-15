<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'id_persona',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function scopePorRol($query, string $rol)
    {
        return $query->where('role', $rol);
    }

    public function scopePorUsername($query, string $username)
    {
        return $query->where('name', $username);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->persona ? $this->persona->nombre_completo : $this->name;
    }

    public function esAdmin()
    {
        return $this->role === 'admin';
    }

    public function esEmpleado()
    {
        return $this->role === 'empleado';
    }
}

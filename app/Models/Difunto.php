<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difunto extends Model
{
    use HasFactory;

    protected $table = 'difunto';
    protected $primaryKey = 'id_difunto';

    protected $fillable = [
        'id_persona',
        'id_doliente',
        'id_nicho',
        'fecha_fallecimiento',
        'fecha_entierro',
        'estado',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function doliente()
    {
        return $this->belongsTo(Persona::class, 'id_doliente', 'id_persona');
    }

    public function nicho()
    {
        return $this->belongsTo(Nicho::class, 'id_nicho', 'id_nicho');
    }

    public function osario()
    {
        return $this->hasOne(Osario::class, 'id_difunto', 'id_difunto');
    }

    public function contratos()
    {
        return $this->hasMany(ContratoAlquiler::class, 'id_difunto', 'id_difunto');
    }

    public function programacionesEntierro()
    {
        return $this->hasMany(ProgramacionEntierro::class, 'id_difunto', 'id_difunto');
    }

    public function scopeSinNicho($query)
    {
        return $query->whereNull('id_nicho')->where('estado', 'registrado');
    }

    public function scopeEnOsario($query)
    {
        return $query->where('estado', 'osario');
    }
}

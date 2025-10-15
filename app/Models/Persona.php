<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'persona';
    protected $primaryKey = 'id_persona';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'direccion',
        'email',
        'id_tipo_persona',
    ];


    public function tipoPersona()
    {
        return $this->belongsTo(TipoPersona::class, 'id_tipo_persona', 'id_tipo_persona');
    }
    public function incineraciones()
    {
        return $this->hasMany(Incineracion::class, 'id_responsable', 'id_persona');
    }
    public function programacionesEntierro()
    {
        return $this->hasMany(ProgramacionEntierro::class, 'id_trabajador', 'id_persona');
    }

    public function serviciosExtras()
    {
        return $this->hasMany(ServicioExtra::class, 'id_trabajador', 'id_persona');
    }
    public function scopePorCI($query, string $ci)
    {
        return $query->where('ci', $ci);
    }
    public function scopePorTipo($query, int $idTipoPersona)
    {
        return $query->where('id_tipo_persona', $idTipoPersona);
    }
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}

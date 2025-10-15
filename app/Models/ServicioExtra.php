<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioExtra extends Model
{
    use HasFactory;
    protected $table = 'servicio_extra';
    protected $primaryKey = 'id_servicio';
    protected $fillable = [
        'id_difunto',
        'id_trabajador',
        'tipo_servicio',
        'fecha_servicio',
        'costo',
        'observaciones',
    ];
    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
    }
    public function trabajador()
    {
        return $this->belongsTo(Persona::class, 'id_trabajador', 'id_persona');
    }
    public function scopeTipo($query, string $tipo)
    {
        return $query->where('tipo_servicio', $tipo);
    }
    public function scopePorFecha($query, string $fecha)
    {
        return $query->whereDate('fecha_servicio', $fecha);
    }
    public function scopePorTrabajador($query, int $idTrabajador)
    {
        return $query->where('id_trabajador', $idTrabajador);
    }
}

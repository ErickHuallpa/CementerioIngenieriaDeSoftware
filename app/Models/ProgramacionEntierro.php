<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramacionEntierro extends Model
{
    use HasFactory;
    protected $table = 'programacion_entierro';
    protected $primaryKey = 'id_programacion';
    protected $fillable = [
        'id_difunto',
        'id_trabajador',
        'fecha_programada',
        'hora_programada',
        'estado',
    ];
    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
    }
    public function trabajador()
    {
        return $this->belongsTo(Persona::class, 'id_trabajador', 'id_persona');
    }
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }
    public function scopePorFecha($query, string $fecha)
    {
        return $query->whereDate('fecha_programada', $fecha);
    }
    public function scopePorTrabajador($query, int $idTrabajador)
    {
        return $query->where('id_trabajador', $idTrabajador);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incineracion extends Model
{
    use HasFactory;
    protected $table = 'incineracion';
    protected $primaryKey = 'id_incineracion';
    protected $fillable = [
        'id_difunto',
        'id_responsable',
        'fecha_incineracion',
        'tipo',
        'costo',
    ];
    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
    }
    public function responsable()
    {
        return $this->belongsTo(Persona::class, 'id_responsable', 'id_persona');
    }
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }
    public function scopeEntreFechas($query, string $inicio, string $fin)
    {
        return $query->whereBetween('fecha_incineracion', [$inicio, $fin]);
    }
}

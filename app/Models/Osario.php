<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Osario extends Model
{
    use HasFactory;

    protected $table = 'osario';
    protected $primaryKey = 'id_osario';

    protected $fillable = [
        'id_difunto',
        'id_pabellon',
        'fila',
        'columna',
        'estado',
        'fecha_ingreso',
        'fecha_salida',
        'costo',
    ];

    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
    }

    public function pabellon()
    {
        return $this->belongsTo(Pabellon::class, 'id_pabellon', 'id_pabellon');
    }

    public function contrato()
    {
        return $this->hasOne(ContratoAlquiler::class, 'id_osario', 'id_osario');
    }

    public function scopeActivos($query)
    {
        return $query->whereNull('fecha_salida');
    }

    public function scopeEntreFechas($query, string $inicio, string $fin)
    {
        return $query->whereBetween('fecha_ingreso', [$inicio, $fin]);
    }

    public function scopeUbicacion($query, string $ubicacion)
    {
        return $query->where('ubicacion', 'ILIKE', "%{$ubicacion}%");
    }
}

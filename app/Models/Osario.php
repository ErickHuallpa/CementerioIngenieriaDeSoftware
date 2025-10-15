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
        'fecha_ingreso',
        'fecha_salida',
        'ubicacion',
        'costo',
    ];
    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
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

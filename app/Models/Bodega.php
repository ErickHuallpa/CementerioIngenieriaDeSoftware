<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;
    protected $table = 'bodega';
    protected $primaryKey = 'id_bodega';
    protected $fillable = [
        'id_difunto',
        'fecha_ingreso',
        'fecha_salida',
        'destino',
    ];
    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
    }
    public function getEnBodegaAttribute(): bool
    {
        return is_null($this->fecha_salida);
    }
    public function scopeActuales($query)
    {
        return $query->whereNull('fecha_salida');
    }
    public function scopePorDestino($query, string $destino)
    {
        return $query->where('destino', $destino);
    }
}

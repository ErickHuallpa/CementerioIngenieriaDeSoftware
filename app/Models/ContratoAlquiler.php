<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoAlquiler extends Model
{
    use HasFactory;
    protected $table = 'contrato_alquiler';
    protected $primaryKey = 'id_contrato';
    protected $fillable = [
        'id_difunto',
        'id_nicho',
        'fecha_inicio',
        'fecha_fin',
        'renovaciones',
        'monto',
        'estado',
        'boleta_numero',
    ];
    public function difunto()
    {
        return $this->belongsTo(Difunto::class, 'id_difunto', 'id_difunto');
    }
    public function nicho()
    {
        return $this->belongsTo(Nicho::class, 'id_nicho', 'id_nicho');
    }
    public function getEstaVencidoAttribute(): bool
    {
        return $this->fecha_fin && now()->greaterThan($this->fecha_fin);
    }
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
    public function scopeVencidos($query)
    {
        return $query->where('estado', 'vencido');
    }
}

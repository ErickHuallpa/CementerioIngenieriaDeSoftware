<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nicho extends Model
{
    use HasFactory;
    protected $table = 'nicho';
    protected $primaryKey = 'id_nicho';
    protected $fillable = [
        'id_pabellon',
        'fila',
        'columna',
        'posicion',
        'costo_alquiler',
        'estado',
        'fecha_ocupacion',
        'fecha_vencimiento',
    ];
    public function pabellon()
    {
        return $this->belongsTo(Pabellon::class, 'id_pabellon', 'id_pabellon');
    }
    public function difuntos()
    {
        return $this->hasMany(Difunto::class, 'id_nicho', 'id_nicho');
    }
    public function contratos()
    {
        return $this->hasMany(ContratoAlquiler::class, 'id_nicho', 'id_nicho');
    }
}

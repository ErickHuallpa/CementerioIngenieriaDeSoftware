<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pabellon extends Model
{
    use HasFactory;

    protected $table = 'pabellon';
    protected $primaryKey = 'id_pabellon';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'institucion',
    ];
    public function nichos()
    {
        return $this->hasMany(Nicho::class, 'id_pabellon', 'id_pabellon');
    }
}

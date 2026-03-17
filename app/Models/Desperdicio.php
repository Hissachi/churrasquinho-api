<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desperdicio extends Model
{
    protected $fillable = [
        'tipo_residuo',
        'peso',
        'origem',
        'observacao',
        'data',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public static function tiposResiduo()
    {
        return [
            'comida_pronta' => 'Comida pronta',
            'insumo_cru' => 'Insumo cru',
            'embalagem' => 'Embalagem',
        ];
    }
}

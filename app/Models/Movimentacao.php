<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';

    protected $fillable = [
        'produto_id',
        'tipo',
        'quantidade',
        'custo_unitario',
        'observacao',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
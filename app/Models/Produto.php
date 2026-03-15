<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'categoria',
        'quantidade',
        'preco',
        'status',
        'ativo'
    ];

    protected $apprends = ['status'];

    public function getStatusAttribute()
    {
        if (!$this->ativo) {
            return 'inativo';
        }

        if ($this->quantidade === 0) {
            return 'esgotado';
        }

        if ($this->quantidade <= 5) {
            return 'baixo';
        }

        return 'disponivel';
    }
}
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
        'ativo'
    ];

    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        return match (true) {
            !$this->ativo => 'inativo',
            $this->quantidade === 0 => 'esgotado',
            $this->quantidade <= 5 => 'baixo',
            default => 'disponivel'
        };
    }

    // public function getStatusAttribute()
    // {
    //     if (!$this->ativo) {
    //         return 'inativo';
    //     }

    //     if ($this->quantidade === 0) {
    //         return 'esgotado';
    //     }

    //     if ($this->quantidade <= 5) {
    //         return 'baixo';
    //     }

    //     return 'disponivel';
    // }
}
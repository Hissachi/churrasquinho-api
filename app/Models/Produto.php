<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'categoria_id',
        'quantidade',
        'preco',
        'ativo'
    ];

    protected $appends = ['status'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function getStatusAttribute()
    {
        return match (true) {
            !$this->ativo => 'inativo',
            $this->quantidade === 0 => 'esgotado',
            $this->quantidade <= 5 => 'baixo',
            default => 'disponivel'
        };
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}

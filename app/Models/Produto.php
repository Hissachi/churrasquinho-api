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
        'custo',
        'ativo',
    ];

    protected $appends = ['status', 'margem', 'lucro_total'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function getStatusAttribute()
    {
        return match (true) {
            ! $this->ativo => 'inativo',
            $this->quantidade === 0 => 'esgotado',
            $this->quantidade <= 5 => 'baixo',
            default => 'disponivel'
        };
    }

    public function getMargemAttribute()
    {
        if (! $this->custo || ! $this->preco) {
            return null;
        }

        return round((($this->preco - $this->custo) / $this->custo) * 100, 2);
    }

    public function getLucroTotalAttribute()
    {
        if (! $this->custo || ! $this->preco) {
            return null;
        }

        return ($this->preco - $this->custo) * $this->quantidade;
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}

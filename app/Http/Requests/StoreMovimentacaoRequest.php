<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovimentacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'produto_id' => 'required|exists:produtos,id',
            'tipo' => 'required|in:entrada,saida,perda',
            'quantidade' => 'required|integer|min:1',
            'custo_unitario' => 'nullable|numeric',
            'observacao' => 'nullable|string',
        ];
    }
}
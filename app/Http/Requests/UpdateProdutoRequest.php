<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'sometimes|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'quantidade' => 'sometimes|integer',
            'preco' => 'sometimes|numeric',
            'ativo' => 'sometimes|boolean',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'quantidade' => 'required|integer',
            'preco' => 'required|numeric',
            'ativo' => 'boolean',
        ];
    }
}
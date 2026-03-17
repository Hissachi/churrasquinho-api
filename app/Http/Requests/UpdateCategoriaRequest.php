<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|integer|exists:categorias,id',
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'Categoria pai inválida'
        ];
    }
}
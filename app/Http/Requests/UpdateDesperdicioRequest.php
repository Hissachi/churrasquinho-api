<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDesperdicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_residuo' => 'sometimes|in:comida_pronta,insumo_cru,embalagem',
            'peso' => 'sometimes|numeric|min:0.01',
            'origem' => 'sometimes|in:interno,cliente',
            'observacao' => 'nullable|string',
            'data' => 'nullable|date',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDesperdicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (!$this->data) {
            $this->merge([
                'data' => now()->toDateString()
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'tipo_residuo' => 'required|in:comida_pronta,insumo_cru,embalagem',
            'peso' => 'required|numeric|min:0.01',
            'origem' => 'required|in:interno,cliente',
            'observacao' => 'nullable|string',
            'data' => 'nullable|date',
        ];
    }
}

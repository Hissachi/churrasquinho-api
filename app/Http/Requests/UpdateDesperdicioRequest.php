<?php

namespace App\Http\Requests;

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
            'tipo_residuo' => 'nullable|in:comida_pronta,insumo_cru,embalagem',
            'peso' => 'sometimes|numeric|min:0.01',
            'origem' => 'sometimes|in:interno,cliente',
            'observacao' => 'nullable|string',
            'data' => 'nullable|date',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $origem = $this->input('origem');
            $tipo = $this->input('tipo_residuo');

            if ($origem === 'interno' && ! $tipo) {
                $validator->errors()->add(
                    'tipo_residuo',
                    'tipo_residuo é obrigatório quando origem é interno'
                );
            }
        });
    }
}
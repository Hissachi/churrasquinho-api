<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movimentacao;
use App\Models\Produto;
use App\Http\Requests\StoreMovimentacaoRequest;
use Illuminate\Http\Request;

class MovimentacaoController extends Controller
{
    public function store(StoreMovimentacaoRequest $request)
    {
        $data = $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'tipo' => 'required|in:entrada,saida,perda',
            'quantidade' => 'required|integer|min:1',
            'custo_unitario' => 'nullable|numeric',
            'observacao' => 'nullable|string',
        ]);

        $produto = Produto::findOrFail($data['produto_id']);
        if (in_array($data['tipo'], ['saida', 'perda'])) {
            if ($produto->quantidade < $data['quantidade']) {
                return response()->json([
                    'message' => 'Estoque insuficiente'
                ], 400);
            }
        }

        // cria movimentação
        $movimentacao = Movimentacao::create($data);

        match ($data['tipo']) {
            'entrada' => $produto->increment('quantidade', $data['quantidade']),
            'saida', 'perda' => $produto->decrement('quantidade', $data['quantidade']),
        };

        return response()->json([
            'message' => 'Movimentação registrada',
            'movimentacao' => $movimentacao,
            'produto' => $produto->fresh()
        ], 201);
    }
}
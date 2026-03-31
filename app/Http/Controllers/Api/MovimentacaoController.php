<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimentacaoRequest;
use App\Models\Movimentacao;
use App\Models\Produto;
use Illuminate\Http\Request;

class MovimentacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimentacao::with('produto')->latest();

        if ($request->data_inicio && $request->data_fim) {
            $query->whereBetween('created_at', [
                $request->data_inicio,
                $request->data_fim,
            ]);
        }

        if ($request->produto_id) {
            $query->where('produto_id', $request->produto_id);
        }

        if ($request->has('tipo')) {
            $tipos = explode(',', $request->tipo);
            $query->whereIn('tipo', $tipos);
        }

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('observacao', 'like', "%{$search}%")
                    ->orWhereHas('produto', function ($p) use ($search) {
                        $p->where('nome', 'like', "%{$search}%");
                    });
            });
        }

        return response()->json(
            $query->paginate(15)
        );
    }

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
                    'message' => 'Estoque insuficiente',
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
            'produto' => $produto->fresh(),
        ], 201);
    }
}

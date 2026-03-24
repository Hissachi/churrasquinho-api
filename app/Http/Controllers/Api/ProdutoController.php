<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('categoria');

        if ($request->search) {
            $query->where('nome', 'like', "%{$request->search}%");
        }

        if ($request->categoria_id) {
            $query->whereIn('categoria_id', function ($q) use ($request) {
                $q->select('id')
                    ->from('categorias')
                    ->where('id', $request->categoria_id)
                    ->orWhere('parent_id', $request->categoria_id);
            });
        }

        return $query->paginate($request->per_page ?? 10);
    }

    public function store(StoreProdutoRequest $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
            'quantidade' => 'required|integer',
            'preco' => 'required|numeric',
            'ativo' => 'boolean',
        ]);

        $produto = Produto::create($data);

        return response()->json($produto->load('categoria'), 201);
    }

    public function show($id)
    {
        return Produto::with(['categoria', 'movimentacoes' => function ($q) {
            $q->latest()->limit(20);
        }])->findOrFail($id);
    }

    public function update(UpdateProdutoRequest $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $data = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
            'quantidade' => 'sometimes|integer',
            'preco' => 'sometimes|numeric',
            'ativo' => 'boolean',
        ]);

        $produto->update($data);

        return response()->json($produto->load('categoria'));
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        $produto->delete();

        return response()->json([
            'message' => 'Produto deletado com sucesso',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use Illuminate\Http\Requests;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->search) {
            $query->where('nome', 'like', "%{$request->search}%");
        }

        if ($request->categoria) {
            $query->where('categoria', $request->categoria);
        }

        return $query->paginate($request->per_page ?? 10);
    }

    public function store(StoreProdutoRequest $request)
    {
        $produto = Produto::create($request->validated());

        return response()->json($produto, 201);
    }

    public function show($id)
    {
        return Produto::findOrFail($id);
    }

    public function update(UpdateProdutoRequest $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $produto->update($request->validated());

        return response()->json($produto, 201);
    }


    public function destroy($id)
    {
        Produto::destroy($id);

        return response()->json([
            "message" => "Produto deletado"
        ]);
    }
}
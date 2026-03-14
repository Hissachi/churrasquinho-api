<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $produto = Produto::create($request->all());

        return response()->json($produto, 201);
    }

    public function show($id)
    {
        return Produto::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $produto->update($request->all());

        return response()->json($produto);
    }

    public function destroy($id)
    {
        Produto::destroy($id);

        return response()->json([
            "message" => "Produto deletado"
        ]);
    }
}
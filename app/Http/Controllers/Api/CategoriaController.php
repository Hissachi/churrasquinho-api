<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return Categoria::whereNull('parent_id')
            ->with('children.children')
            ->get();
    }

    public function store(StoreCategoriaRequest $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categorias,id'
        ]);

        $categoria = Categoria::create($data);

        return response()->json($categoria, 201);
    }

    public function show($id)
    {
        return Categoria::with('children')->findOrFail($id);
    }

    public function update(UpdateCategoriaRequest $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $data = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:categorias,id'
        ]);

        $categoria->update($data);

        return response()->json($categoria);
    }

    public function destroy($id)
    {
        $categoria = Categoria::with(['children', 'produtos'])->findOrFail($id);

        if ($categoria->children->count() > 0) {
            return response()->json([
                'message' => 'Categoria possui subcategorias'
            ], 400);
        }

        if ($categoria->produtos->count() > 0) {
            return response()->json([
                'message' => 'Categoria possui produtos vinculados'
            ], 400);
        }

        $categoria->delete();

        return response()->json([
            'message' => 'Categoria deletada com sucesso'
        ]);
    }
}
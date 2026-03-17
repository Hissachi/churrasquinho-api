<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesperdicioRequest;
use App\Http\Requests\UpdateDesperdicioRequest;
use App\Models\Desperdicio;
use Illuminate\Http\Request;

class DesperdicioController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $query = Desperdicio::query();

        if ($request->data) {
            $query->whereDate('data', $request->data);
        }

        if ($request->origem) {
            $query->where('origem', $request->origem);
        }

        if ($request->tipo_residuo) {
            $query->where('tipo_residuo', $request->tipo_residuo);
        }

        return $query->latest()->paginate(10);
    }

    // Criar
    public function store(StoreDesperdicioRequest $request)
    {
        $desperdicio = Desperdicio::create($request->validated());

        return response()->json($desperdicio, 201);
    }

    // Mostrar
    public function show($id)
    {
        return Desperdicio::findOrFail($id);
    }

    // Atualizar
    public function update(UpdateDesperdicioRequest $request, $id)
    {
        $desperdicio = Desperdicio::findOrFail($id);

        $desperdicio->update($request->validated());

        return response()->json($desperdicio);
    }

    // Deletar
    public function destroy($id)
    {
        Desperdicio::destroy($id);

        return response()->json([
            'message' => 'Desperdício removido',
        ]);
    }
}

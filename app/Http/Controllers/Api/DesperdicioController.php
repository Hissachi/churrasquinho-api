<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesperdicioRequest;
use App\Http\Requests\UpdateDesperdicioRequest;
use App\Models\Desperdicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function resumo(Request $request)
    {
        $query = DB::table('desperdicios');

        if ($request->data) {
            $query->whereDate('data', $request->data);
        }

        // total geral
        $total = (clone $query)->sum('peso');

        // total por origem
        $porOrigem = (clone $query)
            ->select('origem', DB::raw('SUM(peso) as total'))
            ->groupBy('origem')
            ->pluck('total', 'origem');

        // total por tipo de resíduo
        $porTipo = (clone $query)
            ->select(
                DB::raw('COALESCE(tipo_residuo, "misturado") as tipo_residuo'),
                DB::raw('SUM(peso) as total')
            )
            ->groupBy('tipo_residuo')
            ->pluck('total', 'tipo_residuo');

        return response()->json([
            'total_kg' => (float) $total,

            'por_origem' => [
                'interno' => (float) ($porOrigem['interno'] ?? 0),
                'cliente' => (float) ($porOrigem['cliente'] ?? 0),
            ],

            'por_tipo' => [
                'comida_pronta' => (float) ($porTipo['comida_pronta'] ?? 0),
                'insumo_cru' => (float) ($porTipo['insumo_cru'] ?? 0),
                'embalagem' => (float) ($porTipo['embalagem'] ?? 0),
            ],
        ]);
    }
}

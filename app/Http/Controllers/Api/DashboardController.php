<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'resumo' => $this->resumo(),
            'movimentacoes_hoje' => $this->movimentacoesHoje(),
            'produtos_criticos' => $this->produtosCriticos(),
            'top_produtos' => $this->topProdutos(),
            'impacto_financeiro' => $this->impactoFinanceiro(),
            'kpis' => [
                'produtos_prejuizo' => $this->produtosComPrejuizo(),
                'margem_media' => $this->margemMedia(),
            ],
            'desperdicio' => $this->desperdicioResumo(),
        ]);
    }

    // ==============================
    // RESUMO
    // ==============================
    private function resumo()
    {
        return DB::table('produtos')
            ->selectRaw('
                COUNT(*) as total_produtos,
                SUM(quantidade) as estoque_total,
                SUM(quantidade * preco) as valor_estoque
            ')
            ->first();
    }

    // ==============================
    // MOVIMENTAÇÕES HOJE
    // ==============================
    private function movimentacoesHoje()
    {
        return DB::table('movimentacoes')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->selectRaw('
                tipo,
                SUM(movimentacoes.quantidade) as total
            ')
            ->groupBy('tipo')
            ->get();
    }

    // ==============================
    // PRODUTOS CRÍTICOS
    // ==============================
    private function produtosCriticos()
    {
        return DB::table('produtos')
            ->where('quantidade', '<=', 5)
            ->orderBy('quantidade')
            ->limit(10)
            ->get();
    }

    // ==============================
    // TOP PRODUTOS (SAÍDA)
    // ==============================
    private function topProdutos()
    {
        return DB::table('movimentacoes')
            ->join('produtos', 'produtos.id', '=', 'movimentacoes.produto_id')
            ->where('movimentacoes.tipo', 'saida')
            ->select(
                'movimentacoes.produto_id',
                'produtos.nome',
                DB::raw('SUM(movimentacoes.quantidade) as total')
            )
            ->groupBy('movimentacoes.produto_id', 'produtos.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    // ==============================
    // IMPACTO FINANCEIRO
    // ==============================
    private function impactoFinanceiro()
    {
        return DB::table('movimentacoes')
            ->join('produtos', 'produtos.id', '=', 'movimentacoes.produto_id')
            ->select(
                'produtos.nome',
                DB::raw('
                    SUM(
                        CASE 
                            WHEN movimentacoes.tipo = "saida" 
                                THEN movimentacoes.quantidade * produtos.preco
                            WHEN movimentacoes.tipo = "entrada" 
                                THEN movimentacoes.quantidade * produtos.custo
                            ELSE 0
                        END
                    ) as valor
                ')
            )
            ->groupBy('produtos.nome')
            ->orderByDesc('valor')
            ->limit(5)
            ->get();
    }

    // ==============================
    // KPI - PRODUTOS EM PREJUÍZO
    // ==============================
    private function produtosComPrejuizo()
    {
        return DB::table('produtos')
            ->whereNotNull('custo')
            ->whereNotNull('preco')
            ->whereRaw('(preco - custo) < 0')
            ->count();
    }

    // ==============================
    // KPI - MARGEM MÉDIA
    // ==============================
    private function margemMedia()
    {
        $result = DB::table('produtos')
            ->whereNotNull('custo')
            ->where('custo', '>', 0)
            ->whereNotNull('preco')
            ->selectRaw('AVG((preco - custo) / custo * 100) as margem')
            ->first();

        return round($result->margem ?? 0, 2);
    }

    // ==============================
    // DESPERDÍCIO
    // ==============================

    private function desperdicioResumo()
    {
        $query = DB::table('desperdicios');

        $total = (clone $query)->sum('peso');

        $porOrigem = (clone $query)
            ->select('origem', DB::raw('SUM(peso) as total'))
            ->groupBy('origem')
            ->pluck('total', 'origem');

        $porTipo = (clone $query)
            ->select('tipo_residuo', DB::raw('SUM(peso) as total'))
            ->groupBy('tipo_residuo')
            ->pluck('total', 'tipo_residuo');

        return [
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
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movimentacao;
use App\Models\Produto;
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
        ]);
    }

    private function resumo()
    {
        return [
            'total_produtos' => Produto::count(),
            'estoque_total' => Produto::sum('quantidade'),
            'valor_estoque' => Produto::all()->sum(fn ($p) => $p->quantidade * $p->preco),
        ];
    }

    private function movimentacoesHoje()
    {
        return Movimentacao::whereDate('created_at', now())
            ->selectRaw('
                tipo,
                SUM(quantidade) as total
            ')
            ->groupBy('tipo')
            ->get();
    }

    private function produtosCriticos()
    {
        return Produto::where('quantidade', '<=', 5)
            ->orderBy('quantidade')
            ->limit(10)
            ->get();
    }

    private function topProdutos()
    {
        return Movimentacao::where('tipo', 'saida')
            ->with('produto')
            ->select('produto_id', DB::raw('SUM(quantidade) as total'))
            ->groupBy('produto_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }
}

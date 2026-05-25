<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pedido;
use App\Models\Produto;
use App\Enums\PedidoStatus;
use Carbon\Carbon;

class DashboardTv extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-tv';
    protected static \UnitEnum|string|null $navigationGroup = 'Operação Logística';
    protected static ?string $title = 'Painel TV (Full Screen)';
    protected static ?string $slug = 'dashboard-tv';

    protected string $view = 'filament.pages.dashboard-tv';

    // Remove sidebar and topbar
    protected static string $layout = 'filament-panels::components.layout.base';

    public $pedidosExpedidosHoje = 0;
    public $pedidosPendentes = 0;
    public $produtosBaixoEstoque = 0;
    public $ultimosPedidos = [];

    public function mount()
    {
        $this->carregarDados();
    }

    public function carregarDados()
    {
        $this->pedidosExpedidosHoje = Pedido::where('status', PedidoStatus::EXPEDIDO)
            ->whereDate('data_envio', Carbon::today())
            ->count();

        $this->pedidosPendentes = Pedido::whereIn('status', [
            PedidoStatus::PENDENTE,
            PedidoStatus::EM_SEPARACAO,
            PedidoStatus::CONFERIDO,
            PedidoStatus::AGUARDANDO_EXPEDICAO
        ])->count();

        $this->produtosBaixoEstoque = Produto::where('quantidade_estoque', '<', 10)->count();

        $this->ultimosPedidos = Pedido::with('cliente')
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();
    }
}

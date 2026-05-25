<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Produto;
use App\Enums\PedidoStatus;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class PublicTv extends Component
{
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

    public function render()
    {
        return view('livewire.public-tv');
    }
}

<?php

namespace App\Livewire\Mobile;

use App\Enums\PedidoStatus;
use App\Models\Lote;
use App\Models\Pedido;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Separação'])]
class Separacao extends Component
{
    public string $modo = 'selecao'; // selecao | separando | concluido

    public ?int $pedidoId = null;
    public ?Pedido $pedido = null;
    public array $itensSeparados = [];
    public $pedidos = [];
    public array $rotaColeta = [];

    public function mount(): void
    {
        $this->carregarPedidos();
    }

    public function carregarPedidos(): void
    {
        $this->pedidos = Pedido::with(['produto', 'cliente'])
            ->whereIn('status', [PedidoStatus::PENDENTE, PedidoStatus::EM_SEPARACAO])
            ->orderBy('data_pedido', 'asc')
            ->get();
    }

    public function selecionarPedido(int $id): void
    {
        $this->pedido = Pedido::with(['produto.lotes' => function ($query) {
            $query->where('quantidade_atual', '>', 0)
                ->with('endereco')
                ->orderBy('data_validade', 'asc'); // FEFO
        }, 'cliente'])->findOrFail($id);

        $this->pedidoId = $id;
        $this->itensSeparados = [];

        // Build picking route using FEFO
        $this->rotaColeta = [];
        $quantidadeRestante = $this->pedido->quantidade;

        if ($this->pedido->produto && $this->pedido->produto->lotes) {
            foreach ($this->pedido->produto->lotes as $lote) {
                if ($quantidadeRestante <= 0) break;
                if (!$lote->endereco) continue;

                $quantidadeColeta = min($quantidadeRestante, $lote->quantidade_atual);

                $this->rotaColeta[] = [
                    'lote_id' => $lote->id,
                    'codigo_lote' => $lote->codigo_lote,
                    'produto_nome' => $this->pedido->produto->nome,
                    'quantidade' => $quantidadeColeta,
                    'data_validade' => $lote->data_validade?->format('d/m/Y'),
                    'endereco_id' => $lote->endereco->id,
                    'endereco_codigo' => $lote->endereco->corredor . '-' . $lote->endereco->estante . '-' . $lote->endereco->nivel,
                    'corredor' => $lote->endereco->corredor,
                    'estante' => $lote->endereco->estante,
                    'nivel' => $lote->endereco->nivel,
                    'coletado' => false,
                ];

                $quantidadeRestante -= $quantidadeColeta;
            }
        }

        // Update pedido status
        if ($this->pedido->status === PedidoStatus::PENDENTE) {
            $this->pedido->update(['status' => PedidoStatus::EM_SEPARACAO]);
        }

        $this->modo = 'separando';
    }

    public function confirmarColeta(int $index): void
    {
        if (isset($this->rotaColeta[$index]) && !$this->rotaColeta[$index]['coletado']) {
            $this->rotaColeta[$index]['coletado'] = true;
            $this->itensSeparados[] = $index;
        }
    }

    #[On('barcode-scanned')]
    public function scanColeta(string $barcode): void
    {
        if ($this->modo !== 'separando') {
            return;
        }

        foreach ($this->rotaColeta as $index => $item) {
            if ($item['codigo_lote'] === $barcode && !$item['coletado']) {
                $this->confirmarColeta($index);
                $this->dispatch('notify', type: 'success', message: 'Item coletado: ' . $item['produto_nome']);
                return;
            }
        }

        $this->dispatch('notify', type: 'error', message: 'Código não encontrado na rota de coleta.');
    }

    public function finalizarSeparacao(): void
    {
        if ($this->pedido && count($this->itensSeparados) === count($this->rotaColeta)) {
            // Deduct quantities from lots
            foreach ($this->rotaColeta as $item) {
                $lote = Lote::find($item['lote_id']);
                if ($lote) {
                    $lote->decrement('quantidade_atual', $item['quantidade']);
                }
            }

            $this->pedido->update(['status' => PedidoStatus::CONFERIDO]);
            $this->modo = 'concluido';
        }
    }

    public function voltar(): void
    {
        $this->reset(['pedidoId', 'pedido', 'itensSeparados', 'rotaColeta']);
        $this->modo = 'selecao';
        $this->carregarPedidos();
    }

    public function render()
    {
        return view('mobile.separacao');
    }
}

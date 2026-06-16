<?php

namespace App\Livewire\Mobile;

use App\Models\Lote;
use App\Models\Recebimento;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Conferência'])]
class Conferencia extends Component
{
    public string $modo = 'selecao'; // selecao | conferindo | concluido
    public string $search = '';

    public ?int $recebimentoId = null;
    public ?Recebimento $recebimento = null;
    public array $itensConferidos = [];
    public int $totalItens = 0;
    public $recebimentos = [];

    public function mount(): void
    {
        $this->carregarRecebimentos();
    }

    public function carregarRecebimentos(): void
    {
        $this->recebimentos = Recebimento::whereIn('status', ['rascunho', 'em_conferencia'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('fornecedor', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo_nfe', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('lotes')
            ->orderBy('data_recebimento', 'desc')
            ->get();
    }

    public function selecionarRecebimento(int $id): void
    {
        $this->recebimento = Recebimento::with('lotes.produto')->findOrFail($id);
        $this->recebimentoId = $id;
        $this->totalItens = $this->recebimento->lotes->count();
        $this->itensConferidos = [];
        $this->modo = 'conferindo';

        if ($this->recebimento->status === 'rascunho') {
            $this->recebimento->update(['status' => 'em_conferencia']);
        }
    }

    public function confirmarItem(int $loteId): void
    {
        if (!in_array($loteId, $this->itensConferidos)) {
            $this->itensConferidos[] = $loteId;
        }
    }

    #[On('barcode-scanned')]
    public function scanRecebido(string $barcode): void
    {
        if ($this->modo !== 'conferindo' || !$this->recebimento) {
            return;
        }

        $lote = $this->recebimento->lotes->firstWhere('codigo_lote', $barcode);

        if ($lote) {
            $this->confirmarItem($lote->id);
            $this->dispatch('notify', type: 'success', message: 'Item conferido: ' . $lote->produto->nome);
        } else {
            $this->dispatch('notify', type: 'error', message: 'Código não encontrado neste recebimento.');
        }
    }

    public function finalizarConferencia(): void
    {
        if ($this->recebimento && count($this->itensConferidos) === $this->totalItens) {
            $this->recebimento->update(['status' => 'conferido']);
            $this->modo = 'concluido';
        }
    }

    public function voltar(): void
    {
        $this->reset(['recebimentoId', 'recebimento', 'itensConferidos', 'totalItens', 'search']);
        $this->modo = 'selecao';
        $this->carregarRecebimentos();
    }

    public function render()
    {
        $this->carregarRecebimentos();
        return view('mobile.conferencia');
    }
}

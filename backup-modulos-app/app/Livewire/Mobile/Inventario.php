<?php

namespace App\Livewire\Mobile;

use App\Models\Endereco;
use App\Models\Lote;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Inventário'])]
class Inventario extends Component
{
    public string $modo = 'selecao'; // selecao | contando

    public ?int $enderecoSelecionado = null;
    public ?Endereco $endereco = null;
    public array $itensNoEndereco = [];
    public array $contagens = [];
    public string $search = '';
    public $enderecosRecentes = [];
    public $resultadosBusca = [];

    public function mount(): void
    {
        $this->carregarRecentes();

        $enderecoId = request()->query('enderecoId');
        if ($enderecoId) {
            try {
                $this->selecionarEndereco((int)$enderecoId);
            } catch (\Exception $e) {
                // Ignore invalid query parameter
            }
        }
    }

    public function carregarRecentes(): void
    {
        $this->enderecosRecentes = Endereco::withCount('lotes')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            $this->buscarEndereco();
        } else {
            $this->resultadosBusca = [];
        }
    }

    public function buscarEndereco(): void
    {
        $search = $this->search;

        $this->resultadosBusca = Endereco::withCount('lotes')
            ->where(function ($query) use ($search) {
                $query->where('codigo_barras', 'like', "%{$search}%")
                    ->orWhere('corredor', 'like', "%{$search}%")
                    ->orWhere('estante', 'like', "%{$search}%")
                    ->orWhere('nivel', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();
    }

    public function selecionarEndereco(int $id): void
    {
        $this->endereco = Endereco::with('lotes.produto')->findOrFail($id);
        $this->enderecoSelecionado = $id;
        $this->itensNoEndereco = $this->endereco->lotes->toArray();

        // Initialize contagens with current quantities
        $this->contagens = [];
        foreach ($this->endereco->lotes as $lote) {
            $this->contagens[$lote->id] = $lote->quantidade_atual;
        }

        $this->modo = 'contando';
    }

    public function registrarContagem(int $loteId, $quantidade): void
    {
        $this->contagens[$loteId] = (int) $quantidade;
    }

    public function finalizarContagem(): void
    {
        foreach ($this->contagens as $loteId => $quantidade) {
            $lote = Lote::find($loteId);
            if ($lote) {
                $quantidadeAnterior = $lote->quantidade_atual;
                $lote->update(['quantidade_atual' => (int) $quantidade]);

                activity('inventario')
                    ->performedOn($lote)
                    ->withProperties([
                        'quantidade_anterior' => $quantidadeAnterior,
                        'quantidade_contada' => (int) $quantidade,
                        'diferenca' => (int) $quantidade - $quantidadeAnterior,
                        'endereco_id' => $this->enderecoSelecionado,
                    ])
                    ->log('Contagem de inventário realizada');
            }
        }

        $this->dispatch('notify', type: 'success', message: 'Contagem salva com sucesso!');
        $this->voltar();
    }

    #[On('barcode-scanned')]
    public function scanEndereco(string $barcode): void
    {
        $endereco = Endereco::where('codigo_barras', $barcode)->first();

        if ($endereco) {
            $this->selecionarEndereco($endereco->id);
        } else {
            $this->dispatch('notify', type: 'error', message: 'Endereço não encontrado.');
        }
    }

    public function voltar(): void
    {
        $this->reset(['enderecoSelecionado', 'endereco', 'itensNoEndereco', 'contagens', 'search', 'resultadosBusca']);
        $this->modo = 'selecao';
        $this->carregarRecentes();
    }

    public function render()
    {
        return view('mobile.inventario');
    }
}

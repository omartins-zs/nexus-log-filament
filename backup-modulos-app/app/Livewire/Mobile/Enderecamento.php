<?php

namespace App\Livewire\Mobile;

use App\Models\Endereco;
use App\Models\Lote;
use App\Models\Produto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Endereçamento — Nexus WMS'])]
class Enderecamento extends Component
{
    public string $modo = 'pesquisa'; // pesquisa | endereco_detalhes | produto_detalhes
    public string $subModo = 'endereco'; // endereco | produto
    public string $search = '';
    
    public $resultados = [];
    public $recentes = [];
    
    // Details models
    public ?Endereco $selectedEndereco = null;
    public ?Produto $selectedProduto = null;
    
    // Loaded lists
    public $lotes = [];

    public function mount(): void
    {
        $this->carregarRecentes();
    }

    public function carregarRecentes(): void
    {
        if ($this->subModo === 'endereco') {
            $this->recentes = Endereco::withCount('lotes')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $this->recentes = Produto::withCount('lotes')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    public function updatedSubModo(): void
    {
        $this->search = '';
        $this->resultados = [];
        $this->carregarRecentes();
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            $this->buscar();
        } else {
            $this->resultados = [];
        }
    }

    public function buscar(): void
    {
        $q = $this->search;
        if ($this->subModo === 'endereco') {
            $this->resultados = Endereco::where('codigo_barras', 'like', "%{$q}%")
                ->orWhere('corredor', 'like', "%{$q}%")
                ->orWhere('estante', 'like', "%{$q}%")
                ->orWhere('nivel', 'like', "%{$q}%")
                ->limit(15)
                ->get();
        } else {
            $this->resultados = Produto::where('nome', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%")
                ->orWhere('codigo_barras', 'like', "%{$q}%")
                ->limit(15)
                ->get();
        }
    }

    public function selecionarEndereco(int $id): void
    {
        $this->selectedEndereco = Endereco::with(['lotes.produto'])->findOrFail($id);
        $this->lotes = $this->selectedEndereco->lotes->where('quantidade_atual', '>', 0);
        $this->modo = 'endereco_detalhes';
    }

    public function selecionarProduto(int $id): void
    {
        $this->selectedProduto = Produto::findOrFail($id);
        $this->lotes = Lote::with('endereco')
            ->where('produto_id', $id)
            ->where('quantidade_atual', '>', 0)
            ->orderBy('data_validade', 'asc') // FEFO
            ->get();
        $this->modo = 'produto_detalhes';
    }

    #[On('barcode-scanned')]
    public function scanCodigo(string $barcode): void
    {
        // Check address first
        $endereco = Endereco::where('codigo_barras', $barcode)->first();
        if ($endereco) {
            $this->subModo = 'endereco';
            $this->selecionarEndereco($endereco->id);
            $this->dispatch('notify', type: 'success', message: 'Endereço localizado: ' . $endereco->corredor . '-' . $endereco->estante . '-' . $endereco->nivel);
            return;
        }

        // Check product barcode / SKU
        $produto = Produto::where('codigo_barras', $barcode)->orWhere('sku', $barcode)->first();
        if ($produto) {
            $this->subModo = 'produto';
            $this->selecionarProduto($produto->id);
            $this->dispatch('notify', type: 'success', message: 'Produto localizado: ' . $produto->nome);
            return;
        }

        // Check lot code
        $lote = Lote::where('codigo_lote', $barcode)->where('quantidade_atual', '>', 0)->first();
        if ($lote) {
            $this->subModo = 'produto';
            $this->selecionarProduto($lote->produto_id);
            $this->dispatch('notify', type: 'success', message: 'Lote localizado: ' . $lote->codigo_lote . ' (' . $lote->produto->nome . ')');
            return;
        }

        $this->dispatch('notify', type: 'error', message: 'Código de barras não encontrado no armazém.');
    }

    public function voltar(): void
    {
        $this->reset(['selectedEndereco', 'selectedProduto', 'lotes', 'search', 'resultados']);
        $this->modo = 'pesquisa';
        $this->carregarRecentes();
    }

    public function render()
    {
        return view('mobile.enderecamento');
    }
}

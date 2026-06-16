<?php

namespace App\Livewire\Mobile;

use App\Models\Endereco;
use App\Models\Lote;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Transferência — Nexus WMS'])]
class Transferencia extends Component
{
    public string $modo = 'origem'; // origem | lote_selecao | quantidade | destino | confirmar | concluido

    public string $searchOrigem = '';
    public string $searchDestino = '';
    
    public ?int $origemEnderecoId = null;
    public ?Endereco $origemEndereco = null;
    
    public ?int $origemLoteId = null;
    public ?Lote $origemLote = null;
    
    public ?int $destinoEnderecoId = null;
    public ?Endereco $destinoEndereco = null;
    
    public int $quantidade = 0;
    public $lotes = [];
    public $resultadosOrigem = [];
    public $resultadosDestino = [];

    public function mount(): void
    {
        $enderecoId = request()->query('enderecoId');
        $loteId = request()->query('loteId');
        if ($enderecoId) {
            try {
                $this->selecionarOrigemEndereco((int)$enderecoId);
                if ($loteId) {
                    $this->selecionarLote((int)$loteId);
                }
            } catch (\Exception $e) {
                // Ignore invalid query params and stay on origin select mode
            }
        }
    }

    public function updatedSearchOrigem(): void
    {
        if (strlen($this->searchOrigem) >= 2) {
            $this->resultadosOrigem = Endereco::where('codigo_barras', 'like', "%{$this->searchOrigem}%")
                ->orWhere('corredor', 'like', "%{$this->searchOrigem}%")
                ->orWhere('estante', 'like', "%{$this->searchOrigem}%")
                ->orWhere('nivel', 'like', "%{$this->searchOrigem}%")
                ->limit(10)
                ->get();
        } else {
            $this->resultadosOrigem = [];
        }
    }

    public function updatedSearchDestino(): void
    {
        if (strlen($this->searchDestino) >= 2) {
            $this->resultadosDestino = Endereco::where('codigo_barras', 'like', "%{$this->searchDestino}%")
                ->orWhere('corredor', 'like', "%{$this->searchDestino}%")
                ->orWhere('estante', 'like', "%{$this->searchDestino}%")
                ->orWhere('nivel', 'like', "%{$this->searchDestino}%")
                ->limit(10)
                ->get();
        } else {
            $this->resultadosDestino = [];
        }
    }

    public function selecionarOrigemEndereco(int $id): void
    {
        $this->origemEndereco = Endereco::with(['lotes' => function ($query) {
            $query->where('quantidade_atual', '>', 0)->with('produto');
        }])->findOrFail($id);

        $this->origemEnderecoId = $id;
        $this->lotes = $this->origemEndereco->lotes;

        if ($this->lotes->isEmpty()) {
            $this->dispatch('notify', type: 'error', message: 'Este endereço não contém lotes disponíveis.');
            $this->reset(['origemEnderecoId', 'origemEndereco', 'lotes']);
            return;
        }

        if ($this->lotes->count() === 1) {
            $this->selecionarLote($this->lotes->first()->id);
        } else {
            $this->modo = 'lote_selecao';
        }
    }

    public function selecionarLote(int $id): void
    {
        $this->origemLote = Lote::with('produto')->findOrFail($id);
        $this->origemLoteId = $id;
        $this->quantidade = $this->origemLote->quantidade_atual;
        
        // If lot was selected without origin location (direct lot scan)
        if (!$this->origemEndereco && $this->origemLote->endereco_id) {
            $this->origemEnderecoId = $this->origemLote->endereco_id;
            $this->origemEndereco = Endereco::find($this->origemLote->endereco_id);
        }

        $this->modo = 'quantidade';
    }

    public function confirmarQuantidade(int $qtd): void
    {
        if ($qtd <= 0) {
            $this->dispatch('notify', type: 'error', message: 'A quantidade deve ser maior que zero.');
            return;
        }

        if ($qtd > $this->origemLote->quantidade_atual) {
            $this->dispatch('notify', type: 'error', message: 'Quantidade superior ao saldo do lote (' . $this->origemLote->quantidade_atual . ').');
            return;
        }

        $this->quantidade = $qtd;
        $this->modo = 'destino';
    }

    public function selecionarDestinoEndereco(int $id): void
    {
        if ($id === $this->origemEnderecoId) {
            $this->dispatch('notify', type: 'error', message: 'O endereço de destino não pode ser igual ao de origem.');
            return;
        }

        $this->destinoEndereco = Endereco::findOrFail($id);
        $this->destinoEnderecoId = $id;
        $this->modo = 'confirmar';
    }

    public function executarTransferencia(): void
    {
        if (!$this->origemLote || !$this->destinoEndereco) {
            return;
        }

        DB::transaction(function () {
            // 1. Decrement quantity from origin lot
            $quantidadeAnteriorOrigem = $this->origemLote->quantidade_atual;
            $this->origemLote->decrement('quantidade_atual', $this->quantidade);

            // 2. Check if identical lot code already exists in target address
            $loteDestino = Lote::where('endereco_id', $this->destinoEnderecoId)
                ->where('codigo_lote', $this->origemLote->codigo_lote)
                ->first();

            if ($loteDestino) {
                $loteDestino->increment('quantidade_atual', $this->quantidade);
            } else {
                Lote::create([
                    'produto_id' => $this->origemLote->produto_id,
                    'recebimento_id' => $this->origemLote->recebimento_id,
                    'endereco_id' => $this->destinoEnderecoId,
                    'codigo_lote' => $this->origemLote->codigo_lote,
                    'data_fabricacao' => $this->origemLote->data_fabricacao,
                    'data_validade' => $this->origemLote->data_validade,
                    'quantidade_inicial' => $this->quantidade,
                    'quantidade_atual' => $this->quantidade,
                ]);
            }

            // 3. Register activity log
            activity('transferencia')
                ->performedOn($this->origemLote)
                ->withProperties([
                    'produto_sku' => $this->origemLote->produto->sku,
                    'lote' => $this->origemLote->codigo_lote,
                    'quantidade_transferida' => $this->quantidade,
                    'origem_endereco' => $this->origemEndereco ? $this->origemEndereco->corredor . '-' . $this->origemEndereco->estante . '-' . $this->origemEndereco->nivel : 'Estoque Externo',
                    'destino_endereco' => $this->destinoEndereco->corredor . '-' . $this->destinoEndereco->estante . '-' . $this->destinoEndereco->nivel,
                ])
                ->log('Transferência de estoque realizada com sucesso');
        });

        $this->modo = 'concluido';
    }

    #[On('barcode-scanned')]
    public function scanRecebido(string $barcode): void
    {
        if ($this->modo === 'origem') {
            // Check if address scanned
            $endereco = Endereco::where('codigo_barras', $barcode)->first();
            if ($endereco) {
                $this->selecionarOrigemEndereco($endereco->id);
                return;
            }

            // Check if lot scanned
            $lote = Lote::where('codigo_lote', $barcode)->where('quantidade_atual', '>', 0)->first();
            if ($lote) {
                $this->selecionarLote($lote->id);
                return;
            }

            $this->dispatch('notify', type: 'error', message: 'Código de barras de origem inválido ou não encontrado.');
        } elseif ($this->modo === 'destino') {
            // Check if address scanned
            $endereco = Endereco::where('codigo_barras', $barcode)->first();
            if ($endereco) {
                $this->selecionarDestinoEndereco($endereco->id);
                return;
            }

            $this->dispatch('notify', type: 'error', message: 'Código de barras do endereço de destino não encontrado.');
        }
    }

    public function voltar(): void
    {
        if ($this->modo === 'lote_selecao') {
            $this->reset(['origemEnderecoId', 'origemEndereco', 'lotes']);
            $this->modo = 'origem';
        } elseif ($this->modo === 'quantidade') {
            if ($this->lotes && count($this->lotes) > 1) {
                $this->reset(['origemLoteId', 'origemLote', 'quantidade']);
                $this->modo = 'lote_selecao';
            } else {
                $this->reset(['origemEnderecoId', 'origemEndereco', 'origemLoteId', 'origemLote', 'quantidade', 'lotes']);
                $this->modo = 'origem';
            }
        } elseif ($this->modo === 'destino') {
            $this->modo = 'quantidade';
        } elseif ($this->modo === 'confirmar') {
            $this->reset(['destinoEnderecoId', 'destinoEndereco']);
            $this->modo = 'destino';
        } elseif ($this->modo === 'concluido') {
            $this->reset([
                'origemEnderecoId', 'origemEndereco', 'origemLoteId', 'origemLote',
                'destinoEnderecoId', 'destinoEndereco', 'quantidade', 'lotes',
                'searchOrigem', 'searchDestino', 'resultadosOrigem', 'resultadosDestino'
            ]);
            $this->modo = 'origem';
        }
    }

    public function render()
    {
        return view('mobile.transferencia');
    }
}

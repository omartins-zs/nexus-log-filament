<?php

namespace App\Livewire\Mobile;

use App\Models\Endereco;
use App\Models\Lote;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Endereçamento — Nexus WMS'])]
class Enderecamento extends Component
{
    public string $modo = 'scan_lote'; // scan_lote | scan_destino | concluido
    
    public ?int $loteId = null;
    public ?Lote $loteSelecionado = null;
    
    public ?int $enderecoDestinoId = null;
    public ?Endereco $enderecoDestino = null;

    #[On('barcode-scanned')]
    public function scanBarcode(string $barcode): void
    {
        if ($this->modo === 'scan_lote') {
            $lote = Lote::with(['produto'])->where('codigo_lote', $barcode)->first();
            
            if ($lote) {
                if ($lote->endereco_id) {
                    $this->dispatch('notify', type: 'error', message: 'Este lote já possui um endereço. Use a tela de Transferência.');
                    return;
                }
                
                $this->loteSelecionado = $lote;
                $this->loteId = $lote->id;
                $this->modo = 'scan_destino';
                $this->dispatch('notify', type: 'success', message: 'Lote selecionado: ' . $lote->produto->nome);
            } else {
                $this->dispatch('notify', type: 'error', message: 'Lote não encontrado.');
            }
        } elseif ($this->modo === 'scan_destino') {
            $endereco = Endereco::where('codigo_barras', $barcode)->first();
            
            if ($endereco) {
                $this->enderecoDestino = $endereco;
                $this->enderecoDestinoId = $endereco->id;
                $this->dispatch('notify', type: 'success', message: 'Endereço destino selecionado.');
            } else {
                $this->dispatch('notify', type: 'error', message: 'Endereço não encontrado.');
            }
        }
    }

    public function confirmarEnderecamento(): void
    {
        if ($this->loteSelecionado && $this->enderecoDestino) {
            $this->loteSelecionado->update([
                'endereco_id' => $this->enderecoDestino->id
            ]);

            activity('enderecamento')
                ->performedOn($this->loteSelecionado)
                ->withProperties([
                    'novo_endereco_id' => $this->enderecoDestino->id,
                ])
                ->log('Lote endereçado pela primeira vez');

            $this->modo = 'concluido';
        }
    }

    public function voltar(): void
    {
        $this->reset(['modo', 'loteId', 'loteSelecionado', 'enderecoDestinoId', 'enderecoDestino']);
    }

    public function render()
    {
        return view('mobile.enderecamento');
    }
}

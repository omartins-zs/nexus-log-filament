<?php

namespace App\Livewire\Mobile;

use App\Models\Endereco;
use App\Models\Lote;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('mobile.layouts.app', ['title' => 'Transferência — Nexus WMS'])]
class Transferencia extends Component
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
            $lote = Lote::with(['produto', 'endereco'])->where('codigo_lote', $barcode)->first();
            
            if ($lote) {
                if (!$lote->endereco) {
                    $this->dispatch('notify', type: 'error', message: 'Este lote não possui um endereço de origem. Use a tela de Endereçamento.');
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
                if ($this->loteSelecionado->endereco_id === $endereco->id) {
                    $this->dispatch('notify', type: 'error', message: 'O lote já está neste endereço.');
                    return;
                }
                
                $this->enderecoDestino = $endereco;
                $this->enderecoDestinoId = $endereco->id;
                $this->dispatch('notify', type: 'success', message: 'Endereço destino selecionado.');
            } else {
                $this->dispatch('notify', type: 'error', message: 'Endereço de destino não encontrado.');
            }
        }
    }

    public function confirmarTransferencia(): void
    {
        if ($this->loteSelecionado && $this->enderecoDestino) {
            $enderecoOrigemId = $this->loteSelecionado->endereco_id;
            
            $this->loteSelecionado->update([
                'endereco_id' => $this->enderecoDestino->id
            ]);

            activity('transferencia')
                ->performedOn($this->loteSelecionado)
                ->withProperties([
                    'de_endereco_id' => $enderecoOrigemId,
                    'para_endereco_id' => $this->enderecoDestino->id,
                ])
                ->log('Lote transferido de endereço');

            $this->modo = 'concluido';
        }
    }

    public function voltar(): void
    {
        $this->reset(['modo', 'loteId', 'loteSelecionado', 'enderecoDestinoId', 'enderecoDestino']);
    }

    public function render()
    {
        return view('mobile.transferencia');
    }
}

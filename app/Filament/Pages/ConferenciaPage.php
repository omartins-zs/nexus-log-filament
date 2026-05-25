<?php

namespace App\Filament\Pages;

use App\Models\Pedido;
use App\Enums\PedidoStatus;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use BackedEnum;
use UnitEnum;

class ConferenciaPage extends Page
{
    protected string $view = 'filament.pages.conferencia-page';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $title = 'Conferência de Pedidos (Bipagem)';

    protected static ?string $navigationLabel = 'Conferência (Bipagem)';

    protected static string|UnitEnum|null $navigationGroup = 'Expedição Logística';

    public ?string $barcode = '';
    
    public ?array $ultimoPedido = null;

    public array $historico = [];

    public function mount()
    {
        $this->barcode = '';
        $this->ultimoPedido = null;
        $this->historico = [];
    }

    public function conferir()
    {
        $barcodeValue = trim($this->barcode);
        $this->barcode = ''; // limpa imediatamente para o próximo bipo

        if (empty($barcodeValue)) {
            return;
        }

        // Tentar extrair o ID numérico do código de barras
        preg_match('/\d+/', $barcodeValue, $matches);
        $pedidoId = $matches[0] ?? null;

        if (!$pedidoId) {
            $this->dispatch('play-sound', type: 'error');
            Notification::make()
                ->title('Código Inválido')
                ->body("Não foi possível ler um ID de pedido válido no código: '{$barcodeValue}'")
                ->danger()
                ->send();
            return;
        }

        $pedido = Pedido::with(['cliente', 'produto', 'centroDistribuicao'])->find($pedidoId);

        if (!$pedido) {
            $this->dispatch('play-sound', type: 'error');
            Notification::make()
                ->title('Pedido Não Encontrado')
                ->body("Nenhum pedido com o ID #{$pedidoId} foi localizado no banco de dados.")
                ->danger()
                ->send();
            return;
        }

        // Verifica status atual
        if ($pedido->status === PedidoStatus::CONFERIDO) {
            $this->dispatch('play-sound', type: 'warning');
            Notification::make()
                ->title('Pedido Já Conferido')
                ->body("O pedido #{$pedido->id} já está com o status de CONFERIDO.")
                ->warning()
                ->send();
            
            $this->definirUltimoPedido($pedido);
            return;
        }

        // Atualiza status
        $pedido->update([
            'status' => PedidoStatus::CONFERIDO
        ]);

        $this->dispatch('play-sound', type: 'success');

        Notification::make()
            ->title('Pedido Conferido!')
            ->body("Pedido #{$pedido->id} de {$pedido->cliente->nome} avançou para status CONFERIDO.")
            ->success()
            ->send();

        $this->definirUltimoPedido($pedido);
        $this->adicionarAoHistorico($pedido);
    }

    protected function definirUltimoPedido(Pedido $pedido)
    {
        $this->ultimoPedido = [
            'id' => $pedido->id,
            'cliente' => $pedido->cliente->nome,
            'produto' => $pedido->produto->nome,
            'sku' => $pedido->produto->sku,
            'quantidade' => $pedido->quantidade,
            'cd' => $pedido->centroDistribuicao->nome,
            'status_label' => $pedido->status->getLabel(),
            'status_color' => $pedido->status->getColor(),
            'data' => now()->format('H:i:s')
        ];
    }

    protected function adicionarAoHistorico(Pedido $pedido)
    {
        array_unshift($this->historico, [
            'id' => $pedido->id,
            'cliente' => $pedido->cliente->nome,
            'produto' => $pedido->produto->nome,
            'quantidade' => $pedido->quantidade,
            'horario' => now()->format('H:i:s')
        ]);

        // Manter apenas os últimos 5 no log visual rápido
        if (count($this->historico) > 5) {
            array_pop($this->historico);
        }
    }
}

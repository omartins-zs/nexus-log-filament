<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pedido;
use App\Enums\PedidoStatus;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class BipagemLogistica extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-qr-code';
    protected static \UnitEnum|string|null $navigationGroup = 'Operação Logística';
    protected static ?string $title = 'Bipagem / Armazém';
    protected static ?string $slug = 'bipagem-logistica';

    protected string $view = 'filament.pages.bipagem-logistica';

    public string $modo = 'conferencia'; // 'conferencia' ou 'expedicao'
    public string $codigo = '';
    public array $ultimasBipagens = [];

    public function alterarModo($novoModo)
    {
        $this->modo = $novoModo;
        $this->codigo = '';
    }

    public function processarBipagem()
    {
        $codigoBipado = trim($this->codigo);
        $this->codigo = ''; // Limpa rápido pro próximo bipe

        if (empty($codigoBipado)) {
            return;
        }

        // Buscar Pedido
        $pedido = null;
        
        // Regra 1: QR Code do Documento (Padrão: NEXUS-PED-123)
        if (Str::startsWith(strtoupper($codigoBipado), 'NEXUS-PED-')) {
            $id = str_replace('NEXUS-PED-', '', strtoupper($codigoBipado));
            $pedido = Pedido::find($id);
        } else {
            // Regra 2: Busca por código de rastreio ou ID direto
            $pedido = Pedido::where('codigo_rastreio', $codigoBipado)
                ->orWhere('id', $codigoBipado)
                ->first();
        }

        if (!$pedido) {
            $this->registrarBipagem(null, $codigoBipado, false, 'Pedido não encontrado.');
            $this->notificarErro('Pedido não localizado pelo código: ' . $codigoBipado);
            return;
        }

        // Regras de Transição de Status por Modo
        if ($this->modo === 'conferencia') {
            $this->processarConferencia($pedido, $codigoBipado);
        } elseif ($this->modo === 'expedicao') {
            $this->processarExpedicao($pedido, $codigoBipado);
        }
    }

    private function processarConferencia(Pedido $pedido, $codigoLido)
    {
        if (in_array($pedido->status, [PedidoStatus::PENDENTE, PedidoStatus::EM_SEPARACAO])) {
            $pedido->update(['status' => PedidoStatus::CONFERIDO]);
            $this->registrarBipagem($pedido, $codigoLido, true, 'Conferido com sucesso.');
            $this->notificarSucesso("Pedido #{$pedido->id} CONFERIDO.");
        } else {
            $msg = "Status incompatível: {$pedido->status->getLabel()}. Requer Pendente/Em Separação.";
            $this->registrarBipagem($pedido, $codigoLido, false, $msg);
            $this->notificarErro("Falha no Pedido #{$pedido->id}: $msg");
        }
    }

    private function processarExpedicao(Pedido $pedido, $codigoLido)
    {
        if ($pedido->status === PedidoStatus::AGUARDANDO_EXPEDICAO) {
            $pedido->update([
                'status' => PedidoStatus::EXPEDIDO,
                'data_envio' => now()
            ]);
            $this->registrarBipagem($pedido, $codigoLido, true, 'Expedido com sucesso.');
            $this->notificarSucesso("Pedido #{$pedido->id} EXPEDIDO na Doca.");
        } else {
            $msg = "Status incompatível: {$pedido->status->getLabel()}. Requer Aguardando Expedição.";
            $this->registrarBipagem($pedido, $codigoLido, false, $msg);
            $this->notificarErro("Falha no Pedido #{$pedido->id}: $msg");
        }
    }

    private function registrarBipagem($pedido, $codigoLido, $sucesso, $mensagem)
    {
        array_unshift($this->ultimasBipagens, [
            'id_unico' => uniqid(),
            'pedido_id' => $pedido ? $pedido->id : null,
            'codigo_lido' => $codigoLido,
            'sucesso' => $sucesso,
            'mensagem' => $mensagem,
            'hora' => now()->format('H:i:s'),
            'cliente' => $pedido ? ($pedido->cliente->nome ?? 'Desconhecido') : '-',
        ]);

        // Manter apenas as últimas 10 bipagens na tela para não pesar o DOM
        if (count($this->ultimasBipagens) > 10) {
            array_pop($this->ultimasBipagens);
        }
    }

    private function notificarSucesso($mensagem)
    {
        Notification::make()
            ->title('Bipagem Registrada')
            ->body($mensagem)
            ->success()
            ->send();
    }

    private function notificarErro($mensagem)
    {
        Notification::make()
            ->title('Atenção na Bipagem')
            ->body($mensagem)
            ->danger()
            ->send();
    }
}

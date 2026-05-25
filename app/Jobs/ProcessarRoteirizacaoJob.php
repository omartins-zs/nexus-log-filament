<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Enums\PedidoStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessarRoteirizacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pedido;

    /**
     * Create a new job instance.
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simula uma chamada demorada de API para a Transportadora para roteirização
        sleep(2);

        // Atualiza o status do pedido
        if ($this->pedido->status === PedidoStatus::PENDENTE || $this->pedido->status === PedidoStatus::CONFERIDO) {
            $this->pedido->update([
                'status' => PedidoStatus::AGUARDANDO_EXPEDICAO
            ]);
        }
    }
}

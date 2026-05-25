<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\CentroDistribuicao;
use App\Models\Produto;
use App\Models\Transportadora;
use App\Models\Pedido;
use App\Enums\PedidoStatus;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Recuperar Clientes
        $clienteTech = Cliente::where('cnpj', '12.345.678/0001-90')->first();
        $clienteModa = Cliente::where('cnpj', '98.765.432/0001-10')->first();
        $clienteHome = Cliente::where('cnpj', '45.678.123/0001-44')->first();

        // 2. Recuperar CDs
        $cdGuarulhos = CentroDistribuicao::where('codigo_interno', 'CD-GRU-01')->first();
        $cdJoinville = CentroDistribuicao::where('codigo_interno', 'CD-JOI-02')->first();

        // 3. Recuperar Produtos
        $prodFone = Produto::where('sku', 'TECH-EAR-ANC01')->first();
        $prodMouse = Produto::where('sku', 'TECH-MOU-ERGO')->first();
        $prodTeclado = Produto::where('sku', 'TECH-KEY-RGB09')->first();
        $prodCamiseta = Produto::where('sku', 'MODA-TSH-PIMAP-G')->first();
        $prodCalca = Produto::where('sku', 'MODA-JNS-SLIM-42')->first();
        $prodCafeteira = Produto::where('sku', 'HOME-CAF-ITA06')->first();

        // 4. Recuperar Transportadoras
        $transTotal = Transportadora::where('cnpj', '00.111.222/0001-33')->first();
        $transSedex = Transportadora::where('cnpj', '34.028.316/0001-03')->first();

        // Verificar que os dados básicos existem antes de cadastrar os pedidos
        if (!$clienteTech || !$cdGuarulhos || !$prodFone) {
            return;
        }

        // Limpar registros antigos para evitar duplicações
        Pedido::truncate();

        // Pedido 1: Pendente
        Pedido::create([
            'cliente_id' => $clienteTech->id,
            'centro_distribuicao_id' => $cdGuarulhos->id,
            'produto_id' => $prodFone->id,
            'quantidade' => 2,
            'valor_total' => 398.00,
            'status' => PedidoStatus::PENDENTE,
            'data_pedido' => now()->subHours(6),
        ]);

        // Pedido 2: Em Separação
        if ($prodTeclado) {
            Pedido::create([
                'cliente_id' => $clienteTech->id,
                'centro_distribuicao_id' => $cdGuarulhos->id,
                'produto_id' => $prodTeclado->id,
                'quantidade' => 1,
                'valor_total' => 450.00,
                'status' => PedidoStatus::EM_SEPARACAO,
                'data_pedido' => now()->subHours(12),
            ]);
        }

        // Pedido 3: Conferido
        if ($clienteModa && $cdJoinville && $prodCamiseta) {
            Pedido::create([
                'cliente_id' => $clienteModa->id,
                'centro_distribuicao_id' => $cdJoinville->id,
                'produto_id' => $prodCamiseta->id,
                'quantidade' => 5,
                'valor_total' => 495.00,
                'status' => PedidoStatus::CONFERIDO,
                'data_pedido' => now()->subHours(8),
            ]);
        }

        // Pedido 4: Aguardando Expedição (Joinville)
        if ($clienteHome && $cdGuarulhos && $prodCafeteira) {
            Pedido::create([
                'cliente_id' => $clienteHome->id,
                'centro_distribuicao_id' => $cdGuarulhos->id,
                'produto_id' => $prodCafeteira->id,
                'quantidade' => 1,
                'valor_total' => 189.90,
                'status' => PedidoStatus::AGUARDANDO_EXPEDICAO,
                'data_pedido' => now()->subDays(1),
            ]);
        }

        // Pedido 5: Aguardando Expedição (Moda)
        if ($clienteModa && $cdJoinville && $prodCalca) {
            Pedido::create([
                'cliente_id' => $clienteModa->id,
                'centro_distribuicao_id' => $cdJoinville->id,
                'produto_id' => $prodCalca->id,
                'quantidade' => 2,
                'valor_total' => 319.80,
                'status' => PedidoStatus::AGUARDANDO_EXPEDICAO,
                'data_pedido' => now()->subDays(1)->subHours(3),
            ]);
        }

        // Pedido 6: Expedido
        if ($prodMouse && $transTotal) {
            Pedido::create([
                'cliente_id' => $clienteTech->id,
                'centro_distribuicao_id' => $cdGuarulhos->id,
                'produto_id' => $prodMouse->id,
                'transportadora_id' => $transTotal->id,
                'quantidade' => 1,
                'valor_total' => 129.90,
                'status' => PedidoStatus::EXPEDIDO,
                'codigo_rastreio' => 'TEXP-889912345-BR',
                'data_pedido' => now()->subDays(2),
                'data_envio' => now()->subDays(1)->subHours(5),
            ]);
        }

        // Pedido 7: Entregue
        if ($clienteModa && $cdJoinville && $prodCamiseta && $transSedex) {
            Pedido::create([
                'cliente_id' => $clienteModa->id,
                'centro_distribuicao_id' => $cdJoinville->id,
                'produto_id' => $prodCamiseta->id,
                'transportadora_id' => $transSedex->id,
                'quantidade' => 3,
                'valor_total' => 297.00,
                'status' => PedidoStatus::ENTREGUE,
                'codigo_rastreio' => 'SX987654321BR',
                'data_pedido' => now()->subDays(5),
                'data_envio' => now()->subDays(4),
            ]);
        }
    }
}

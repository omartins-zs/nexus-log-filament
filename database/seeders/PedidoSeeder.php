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

        // Criar endereços específicos para garatir consistência de testes
        $endA0101 = \App\Models\Endereco::where('codigo_barras', 'A-01-01')->first();
        $endA0102 = \App\Models\Endereco::where('codigo_barras', 'A-01-02')->first();
        $endB0201 = \App\Models\Endereco::where('codigo_barras', 'B-02-01')->first();
        $endC0301 = \App\Models\Endereco::where('codigo_barras', 'C-03-01')->first();
        $endD0401 = \App\Models\Endereco::where('codigo_barras', 'D-04-01')->first();
        $endA0201 = \App\Models\Endereco::where('codigo_barras', 'A-02-01')->first();

        // Seed de lotes ativos previsíveis para simulação manual
        if ($endA0101 && $prodFone) {
            \App\Models\Lote::updateOrCreate(
                ['codigo_lote' => 'LOTE-FONE-01'],
                [
                    'produto_id' => $prodFone->id,
                    'endereco_id' => $endA0101->id,
                    'data_fabricacao' => now()->subDays(60),
                    'data_validade' => now()->addDays(300),
                    'quantidade_inicial' => 50,
                    'quantidade_atual' => 50,
                ]
            );
        }

        if ($endA0102 && $prodTeclado) {
            \App\Models\Lote::updateOrCreate(
                ['codigo_lote' => 'LOTE-TECLADO-01'],
                [
                    'produto_id' => $prodTeclado->id,
                    'endereco_id' => $endA0102->id,
                    'data_fabricacao' => now()->subDays(40),
                    'data_validade' => now()->addDays(200),
                    'quantidade_inicial' => 30,
                    'quantidade_atual' => 30,
                ]
            );
        }

        if ($endB0201 && $prodCamiseta) {
            \App\Models\Lote::updateOrCreate(
                ['codigo_lote' => 'LOTE-CAMISETA-01'],
                [
                    'produto_id' => $prodCamiseta->id,
                    'endereco_id' => $endB0201->id,
                    'data_fabricacao' => now()->subDays(30),
                    'data_validade' => now()->addDays(400),
                    'quantidade_inicial' => 80,
                    'quantidade_atual' => 80,
                ]
            );
        }

        if ($endC0301 && $prodCafeteira) {
            \App\Models\Lote::updateOrCreate(
                ['codigo_lote' => 'LOTE-CAFETEIRA-01'],
                [
                    'produto_id' => $prodCafeteira->id,
                    'endereco_id' => $endC0301->id,
                    'data_fabricacao' => now()->subDays(90),
                    'data_validade' => now()->addDays(180),
                    'quantidade_inicial' => 25,
                    'quantidade_atual' => 25,
                ]
            );
        }

        if ($endD0401 && $prodMouse) {
            \App\Models\Lote::updateOrCreate(
                ['codigo_lote' => 'LOTE-MOUSE-01'],
                [
                    'produto_id' => $prodMouse->id,
                    'endereco_id' => $endD0401->id,
                    'data_fabricacao' => now()->subDays(20),
                    'data_validade' => now()->addDays(150),
                    'quantidade_inicial' => 45,
                    'quantidade_atual' => 45,
                ]
            );
        }

        if ($endA0201 && $prodCalca) {
            \App\Models\Lote::updateOrCreate(
                ['codigo_lote' => 'LOTE-CALCA-01'],
                [
                    'produto_id' => $prodCalca->id,
                    'endereco_id' => $endA0201->id,
                    'data_fabricacao' => now()->subDays(15),
                    'data_validade' => now()->addDays(365),
                    'quantidade_inicial' => 15,
                    'quantidade_atual' => 15,
                ]
            );
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

        // Pedido 8: Pendente (Mouse)
        if ($prodMouse) {
            Pedido::create([
                'cliente_id' => $clienteTech->id,
                'centro_distribuicao_id' => $cdGuarulhos->id,
                'produto_id' => $prodMouse->id,
                'quantidade' => 2,
                'valor_total' => 259.80,
                'status' => PedidoStatus::PENDENTE,
                'data_pedido' => now()->subHours(2),
            ]);
        }

        // Pedido 9: Pendente (Camiseta Preta)
        if ($clienteModa && $cdJoinville && $prodCamiseta) {
            Pedido::create([
                'cliente_id' => $clienteModa->id,
                'centro_distribuicao_id' => $cdJoinville->id,
                'produto_id' => $prodCamiseta->id,
                'quantidade' => 4,
                'valor_total' => 396.00,
                'status' => PedidoStatus::PENDENTE,
                'data_pedido' => now()->subHours(4),
            ]);
        }

        // Pedido 10: Em Separação (Cafeteira Italiana)
        if ($clienteHome && $cdGuarulhos && $prodCafeteira) {
            Pedido::create([
                'cliente_id' => $clienteHome->id,
                'centro_distribuicao_id' => $cdGuarulhos->id,
                'produto_id' => $prodCafeteira->id,
                'quantidade' => 2,
                'valor_total' => 379.80,
                'status' => PedidoStatus::EM_SEPARACAO,
                'data_pedido' => now()->subHours(9),
            ]);
        }

        // Pedido 11: Cancelado (Calça Jeans)
        if ($clienteModa && $cdJoinville && $prodCalca) {
            Pedido::create([
                'cliente_id' => $clienteModa->id,
                'centro_distribuicao_id' => $cdJoinville->id,
                'produto_id' => $prodCalca->id,
                'quantidade' => 1,
                'valor_total' => 159.90,
                'status' => PedidoStatus::CANCELADO,
                'data_pedido' => now()->subDays(3),
            ]);
        }
    }
}

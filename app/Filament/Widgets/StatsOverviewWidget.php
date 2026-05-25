<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use App\Models\Produto;
use App\Enums\PedidoStatus;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProdutos = Produto::count();
        $totalPedidos = Pedido::count();
        $baixoEstoqueCount = Produto::where('quantidade_estoque', '<', 10)->count();
        
        // Entregas Pendentes (Tudo que ainda não foi expedido/entregue)
        $entregasPendentes = Pedido::whereIn('status', [
            PedidoStatus::PENDENTE,
            PedidoStatus::EM_SEPARACAO,
            PedidoStatus::CONFERIDO,
            PedidoStatus::AGUARDANDO_EXPEDICAO
        ])->count();

        // Pedidos Expedidos com Sucesso (apenas para o gráfico)
        $pedidosExpedidos = Pedido::where('status', PedidoStatus::EXPEDIDO)->count();

        return [
            Stat::make('Total de Produtos', $totalProdutos)
                ->description('Itens ativos no catálogo')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 10, 13, 15, 20, 25, $totalProdutos]),
                
            Stat::make('Entregas Pendentes', $entregasPendentes)
                ->description('Aguardando processamento logístico')
                ->descriptionIcon('heroicon-m-clock')
                ->color($entregasPendentes > 0 ? 'warning' : 'success')
                ->chart([$entregasPendentes, $entregasPendentes+2, $entregasPendentes+5, $entregasPendentes-1, $entregasPendentes]),
                
            Stat::make('Produtos em Baixo Estoque', $baixoEstoqueCount)
                ->description('Alerta de itens com saldo crítico (< 10 un)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($baixoEstoqueCount > 0 ? 'danger' : 'success'),
                
            Stat::make('Pedidos Expedidos', $pedidosExpedidos)
                ->description('Total de envios realizados')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success')
                ->chart([2, 5, 8, 12, 18, 22, $pedidosExpedidos]),
        ];
    }
}

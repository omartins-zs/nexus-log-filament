<script src="https://cdn.tailwindcss.com"></script>
<div 
    class="min-h-screen bg-gray-900 text-white font-sans p-8"
    wire:poll.10s="carregarDados"
>
    <!-- Header -->
    <div class="flex justify-between items-center mb-12">
        <div class="flex items-center gap-4">
            <x-heroicon-o-tv class="w-12 h-12 text-primary-500" />
            <h1 class="text-4xl font-bold tracking-tight">Nexus WMS <span class="text-gray-400 font-light">| Visão Operacional</span></h1>
        </div>
        <div class="text-right">
            <div class="text-2xl font-medium">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            <div class="text-gray-400 text-xl">{{ \Carbon\Carbon::now()->format('H:i') }} (Auto-refresh: 10s)</div>
        </div>
    </div>

    <!-- Top KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- KPI 1 -->
        <div class="bg-gray-800 rounded-2xl p-8 border-l-8 border-green-500 shadow-2xl relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10">
                <x-heroicon-o-check-circle class="w-48 h-48 -mr-8 -mt-8" />
            </div>
            <h2 class="text-gray-400 text-2xl font-medium uppercase tracking-wider mb-2">Expedidos Hoje</h2>
            <div class="text-7xl font-black text-green-400">{{ $pedidosExpedidosHoje }}</div>
        </div>

        <!-- KPI 2 -->
        <div class="bg-gray-800 rounded-2xl p-8 border-l-8 border-blue-500 shadow-2xl relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10">
                <x-heroicon-o-clock class="w-48 h-48 -mr-8 -mt-8" />
            </div>
            <h2 class="text-gray-400 text-2xl font-medium uppercase tracking-wider mb-2">Pedidos Pendentes (Fila)</h2>
            <div class="text-7xl font-black text-blue-400">{{ $pedidosPendentes }}</div>
        </div>

        <!-- KPI 3 -->
        <div class="bg-gray-800 rounded-2xl p-8 border-l-8 border-red-500 shadow-2xl relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10">
                <x-heroicon-o-exclamation-triangle class="w-48 h-48 -mr-8 -mt-8" />
            </div>
            <h2 class="text-gray-400 text-2xl font-medium uppercase tracking-wider mb-2">Alerta de Estoque Baixo</h2>
            <div class="text-7xl font-black text-red-400">{{ $produtosBaixoEstoque }}</div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div>
        <h2 class="text-3xl font-bold mb-6 flex items-center gap-3">
            <x-heroicon-o-bolt class="w-8 h-8 text-yellow-400" />
            Últimas Movimentações
        </h2>
        
        <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900/50 text-gray-400 text-xl uppercase tracking-wider">
                        <th class="p-6 font-medium">Pedido</th>
                        <th class="p-6 font-medium">Cliente</th>
                        <th class="p-6 font-medium">Status Atual</th>
                        <th class="p-6 font-medium text-right">Última Atualização</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($ultimosPedidos as $pedido)
                        <tr class="hover:bg-gray-700/50 transition-colors text-2xl">
                            <td class="p-6 font-bold">#{{ $pedido->id }}</td>
                            <td class="p-6">{{ $pedido->cliente->nome ?? 'Cliente Avulso' }}</td>
                            <td class="p-6">
                                @php
                                    $color = match($pedido->status->value) {
                                        'conferido' => 'text-green-400 bg-green-400/10 border-green-400/20',
                                        'expedido' => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
                                        'pendente' => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
                                        'em_separacao' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
                                        default => 'text-gray-400 bg-gray-400/10 border-gray-400/20',
                                    };
                                @endphp
                                <span class="px-4 py-2 rounded-full border text-lg font-bold uppercase tracking-wider {{ $color }}">
                                    {{ $pedido->status->getLabel() }}
                                </span>
                            </td>
                            <td class="p-6 text-right text-gray-400">{{ $pedido->updated_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-gray-500 text-2xl">
                                Nenhum pedido movimentado recentemente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

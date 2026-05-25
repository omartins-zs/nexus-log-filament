<div 
    class="min-h-screen p-8 flex flex-col bg-gray-950 text-white font-sans overflow-hidden"
    wire:poll.10s="carregarDados"
>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Header -->
    <div class="flex justify-between items-center mb-10 pb-6 border-b border-gray-800">
        <div class="flex items-center gap-4">
            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <h1 class="text-5xl font-black tracking-tight text-white">NEXUS WMS <span class="text-gray-500 font-light ml-2">TV</span></h1>
        </div>
        <div class="text-right">
            <div class="text-4xl font-bold text-gray-200">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            <div class="text-gray-500 text-2xl mt-1">{{ \Carbon\Carbon::now()->format('H:i') }} <span class="text-sm font-normal bg-gray-800 px-2 py-1 rounded ml-2">AUTO-REFRESH: 10s</span></div>
        </div>
    </div>

    <!-- Top KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <!-- KPI 1 -->
        <div class="bg-gradient-to-br from-emerald-900/40 to-gray-900 rounded-3xl p-8 border-l-8 border-emerald-500 shadow-2xl relative overflow-hidden">
            <h2 class="text-gray-400 text-2xl font-bold uppercase tracking-widest mb-4">Meta do Dia: Expedidos</h2>
            <div class="text-8xl font-black text-emerald-400">{{ $pedidosExpedidosHoje }}</div>
        </div>

        <!-- KPI 2 -->
        <div class="bg-gradient-to-br from-blue-900/40 to-gray-900 rounded-3xl p-8 border-l-8 border-blue-500 shadow-2xl relative overflow-hidden">
            <h2 class="text-gray-400 text-2xl font-bold uppercase tracking-widest mb-4">Aguardando Separação</h2>
            <div class="text-8xl font-black text-blue-400">{{ $pedidosPendentes }}</div>
        </div>

        <!-- KPI 3 -->
        <div class="bg-gradient-to-br from-red-900/40 to-gray-900 rounded-3xl p-8 border-l-8 border-red-500 shadow-2xl relative overflow-hidden">
            <h2 class="text-gray-400 text-2xl font-bold uppercase tracking-widest mb-4">Risco: Estoque Baixo</h2>
            <div class="text-8xl font-black text-red-500">{{ $produtosBaixoEstoque }} <span class="text-2xl font-normal text-gray-500">SKUs</span></div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="flex-1 bg-gray-900/50 rounded-3xl overflow-hidden border border-gray-800 flex flex-col">
        <div class="bg-gray-800/80 px-8 py-5 border-b border-gray-700 flex items-center justify-between">
            <h2 class="text-3xl font-bold flex items-center gap-3 text-gray-200">
                <span class="w-4 h-4 rounded-full bg-emerald-500 animate-pulse"></span>
                Últimas Movimentações (Real-Time)
            </h2>
        </div>
        
        <div class="flex-1 p-8">
            <div class="grid grid-cols-2 gap-6">
                @forelse($ultimosPedidos as $pedido)
                    @php
                        $color = match($pedido->status->value) {
                            'conferido' => 'text-emerald-400 border-emerald-400/30 bg-emerald-400/5',
                            'expedido' => 'text-purple-400 border-purple-400/30 bg-purple-400/5',
                            'pendente' => 'text-yellow-400 border-yellow-400/30 bg-yellow-400/5',
                            'em_separacao' => 'text-blue-400 border-blue-400/30 bg-blue-400/5',
                            default => 'text-gray-400 border-gray-400/30 bg-gray-400/5',
                        };
                    @endphp
                    <div class="flex items-center justify-between p-6 rounded-2xl border {{ $color }}">
                        <div class="flex items-center gap-6">
                            <div class="text-5xl font-black opacity-80">#{{ $pedido->id }}</div>
                            <div>
                                <div class="text-2xl font-bold text-white">{{ $pedido->cliente->nome ?? 'Cliente Avulso' }}</div>
                                <div class="text-lg opacity-70 mt-1 uppercase tracking-wider font-bold">{{ $pedido->status->getLabel() }}</div>
                            </div>
                        </div>
                        <div class="text-xl font-mono opacity-50">{{ $pedido->updated_at->format('H:i:s') }}</div>
                    </div>
                @empty
                    <div class="col-span-2 text-center text-gray-500 text-3xl py-12">
                        Aguardando movimentações...
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

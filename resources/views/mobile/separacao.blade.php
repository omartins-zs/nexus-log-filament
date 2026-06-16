<div>
    <div class="bg-gray-900 border-b border-gray-800 safe-top p-4 sticky top-0 z-10 flex items-center gap-3">
        @if($modo !== 'selecao')
            <button wire:click="voltar" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
        @else
            <a href="{{ route('mobile.hub') }}" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
        @endif
        <h1 class="text-xl font-bold text-white">Separação (Picking)</h1>
    </div>

    <div class="p-4 page-content">
        @if($modo === 'selecao')
            <h2 class="text-gray-400 text-sm font-semibold mb-4 uppercase tracking-wider">Pedidos para Separar</h2>
            <div class="space-y-4">
                @forelse($pedidos as $ped)
                    <div wire:click="selecionarPedido({{ $ped->id }})" class="bg-gray-800 rounded-xl p-4 border border-gray-700 active:bg-gray-700 transition">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-bold text-white">Pedido #{{ $ped->id }}</span>
                            <span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded font-bold uppercase">{{ str_replace('_', ' ', $ped->status) }}</span>
                        </div>
                        <div class="text-gray-300 text-sm mb-1">{{ $ped->cliente->nome ?? 'Cliente Avulso' }}</div>
                        <div class="text-gray-400 text-xs">Produto: {{ $ped->produto->nome ?? 'N/A' }} | Qtd total: <span class="text-white font-bold">{{ $ped->quantidade }}</span></div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">Nenhum pedido aguardando separação.</div>
                @endforelse
            </div>
        @elseif($modo === 'separando')
            <div class="bg-blue-500/10 rounded-xl p-4 border border-blue-500/20 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-blue-400 text-xs font-bold uppercase tracking-wider mb-1">Rota de Coleta Otimizada</div>
                        <div class="text-xl font-bold text-white">Pedido #{{ $pedido->id }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-400 text-xs">Progresso</div>
                        <div class="text-xl font-bold text-white">{{ count($itensSeparados) }} / {{ count($rotaColeta) }}</div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-24">
                @foreach($rotaColeta as $index => $item)
                    @php $coletado = $item['coletado']; @endphp
                    <div class="relative flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full border-4 border-gray-900 {{ $coletado ? 'bg-blue-500' : 'bg-gray-700' }} text-white flex justify-center items-center shrink-0 z-10 transition-colors">
                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1 p-4 rounded-xl border {{ $coletado ? 'bg-blue-500/10 border-blue-500/30' : 'bg-gray-800 border-gray-700' }} shadow">
                            <div class="font-bold text-lg text-white mb-1">Endereço: {{ $item['endereco_codigo'] }}</div>
                            <div class="text-gray-300 text-sm mb-2">{{ $item['produto_nome'] }}</div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Lote: <span class="text-white">{{ $item['codigo_lote'] }}</span></span>
                                <span class="text-brand-400 font-bold">Separar: {{ $item['quantidade'] }} und</span>
                            </div>
                            @if(!$coletado)
                                <button wire:click="confirmarColeta({{ $index }})" class="mt-4 w-full bg-gray-700 active:bg-gray-600 text-white py-3 rounded-lg font-bold transition">Confirmar Coleta</button>
                            @else
                                <div class="mt-4 w-full bg-blue-500/20 text-blue-400 py-2 rounded-lg font-bold text-center flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Item Coletado
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="fixed bottom-0 left-0 right-0 p-4 bg-gray-900/95 border-t border-gray-800 safe-bottom z-50">
                <button wire:click="finalizarSeparacao" @if(count($itensSeparados) < count($rotaColeta)) disabled @endif class="w-full py-4 rounded-xl font-bold text-lg {{ count($itensSeparados) === count($rotaColeta) ? 'bg-brand-500 text-gray-900 shadow-[0_0_15px_rgba(245,158,11,0.5)]' : 'bg-gray-800 text-gray-500' }}">
                    Finalizar Separação
                </button>
            </div>
        @elseif($modo === 'concluido')
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-blue-500/20 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Separação Concluída!</h2>
                <p class="text-gray-400 mb-8">O pedido está pronto para expedição.</p>
                <button wire:click="voltar" class="bg-brand-500 text-gray-900 w-full py-4 rounded-xl font-bold">Voltar para a Lista</button>
            </div>
        @endif
    </div>
</div>

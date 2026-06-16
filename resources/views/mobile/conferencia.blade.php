<div>
    <!-- Header -->
    <div class="bg-gray-900 border-b border-gray-800 safe-top p-4 sticky top-0 z-10 flex items-center gap-3">
        @if($modo !== 'selecao')
            <button wire:click="voltar" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
        @else
            <a href="{{ route('mobile.hub') }}" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
        @endif
        <h1 class="text-xl font-bold text-white">Conferência</h1>
    </div>

    <div class="p-4 page-content">
        @if($modo === 'selecao')
            <h2 class="text-gray-400 text-sm font-semibold mb-4 uppercase tracking-wider">Recebimentos Pendentes</h2>
            <div class="space-y-4">
                @forelse($recebimentos as $rec)
                    <div wire:click="selecionarRecebimento({{ $rec->id }})" class="bg-gray-800 rounded-xl p-4 border border-gray-700 active:bg-gray-700 transition">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-bold text-white">Recebimento #{{ $rec->id }}</span>
                            <span class="bg-brand-500/20 text-brand-400 text-xs px-2 py-1 rounded font-bold">{{ str_replace('_', ' ', strtoupper($rec->status)) }}</span>
                        </div>
                        <div class="text-gray-400 text-sm">Data: {{ $rec->data_recebimento?->format('d/m/Y') ?? 'N/A' }}</div>
                        <div class="text-gray-400 text-sm">Itens esperados: <span class="text-white font-bold">{{ $rec->lotes_count }}</span></div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">Nenhum recebimento pendente.</div>
                @endforelse
            </div>
        @elseif($modo === 'conferindo')
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 mb-6">
                <div class="text-gray-400 text-sm mb-1">Conferindo Recebimento</div>
                <div class="text-2xl font-bold text-white">#{{ $recebimento->id }}</div>
                <div class="mt-4 flex justify-between text-sm">
                    <span class="text-gray-400">Progresso:</span>
                    <span class="text-brand-400 font-bold">{{ count($itensConferidos) }} de {{ $totalItens }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2.5 mt-2">
                    <div class="bg-brand-500 h-2.5 rounded-full transition-all" style="width: {{ $totalItens > 0 ? (count($itensConferidos) / $totalItens * 100) : 0 }}%"></div>
                </div>
            </div>

            <div class="space-y-3 mb-24">
                @foreach($recebimento->lotes as $lote)
                    @php $conferido = in_array($lote->id, $itensConferidos); @endphp
                    <div wire:click="confirmarItem({{ $lote->id }})" class="flex items-center gap-4 bg-gray-800 p-4 rounded-xl border {{ $conferido ? 'border-green-500/50 opacity-75' : 'border-gray-700' }}">
                        <div class="w-6 h-6 rounded border flex items-center justify-center {{ $conferido ? 'bg-green-500 border-green-500' : 'border-gray-500' }}">
                            @if($conferido)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-bold">{{ $lote->produto->nome ?? 'Produto' }}</div>
                            <div class="text-gray-400 text-xs mt-1">Lote: {{ $lote->codigo_lote }} | Qtd: {{ $lote->quantidade_inicial }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="fixed bottom-0 left-0 right-0 p-4 bg-gray-900/95 border-t border-gray-800 safe-bottom z-50">
                <button wire:click="finalizarConferencia" @if(count($itensConferidos) < $totalItens) disabled @endif class="w-full py-4 rounded-xl font-bold text-lg {{ count($itensConferidos) === $totalItens ? 'bg-brand-500 text-gray-900 shadow-[0_0_15px_rgba(245,158,11,0.5)]' : 'bg-gray-800 text-gray-500' }}">
                    Finalizar Conferência
                </button>
            </div>
        @elseif($modo === 'concluido')
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Conferência Concluída!</h2>
                <p class="text-gray-400 mb-8">Todos os itens foram verificados com sucesso.</p>
                <button wire:click="voltar" class="bg-gray-800 text-white w-full py-4 rounded-xl font-bold">Voltar para a Lista</button>
            </div>
        @endif
    </div>
</div>

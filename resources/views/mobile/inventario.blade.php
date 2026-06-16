<div>
    <div class="bg-gray-900 border-b border-gray-800 safe-top p-4 sticky top-0 z-10 flex items-center gap-3">
        @if($modo !== 'selecao')
            <button wire:click="voltar" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
        @else
            <a href="{{ route('mobile.hub') }}" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
        @endif
        <h1 class="text-xl font-bold text-white">Inventário</h1>
    </div>

    <div class="p-4 page-content">
        @if($modo === 'selecao')
            <div class="mb-6 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar endereço (ex: A-1)" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-brand-500 pl-12" />
                <svg class="w-5 h-5 text-gray-500 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            @if(count($resultadosBusca) > 0)
                <h2 class="text-gray-400 text-sm font-semibold mb-4 uppercase tracking-wider">Resultados</h2>
                <div class="space-y-3">
                    @foreach($resultadosBusca as $end)
                        <div wire:click="selecionarEndereco({{ $end->id }})" class="bg-gray-800 rounded-xl p-4 border border-gray-700 active:bg-brand-500/20 transition flex justify-between items-center">
                            <div>
                                <div class="text-lg font-bold text-white">{{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</div>
                                <div class="text-gray-400 text-xs">{{ $end->codigo_barras }}</div>
                            </div>
                            <span class="text-gray-400 text-sm">{{ $end->lotes_count }} lotes</span>
                        </div>
                    @endforeach
                </div>
            @else
                <h2 class="text-gray-400 text-sm font-semibold mb-4 uppercase tracking-wider">Endereços Recentes</h2>
                <div class="space-y-3">
                    @forelse($enderecosRecentes as $end)
                        <div wire:click="selecionarEndereco({{ $end->id }})" class="bg-gray-800 rounded-xl p-4 border border-gray-700 active:bg-brand-500/20 transition flex justify-between items-center">
                            <div>
                                <div class="text-lg font-bold text-white">{{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</div>
                                <div class="text-gray-400 text-xs">{{ $end->codigo_barras }}</div>
                            </div>
                            <span class="text-gray-400 text-sm">{{ $end->lotes_count }} lotes</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">Nenhum endereço recente.</div>
                    @endforelse
                </div>
            @endif
        @elseif($modo === 'contando')
            <div class="bg-brand-500/10 rounded-xl p-4 border border-brand-500/20 mb-6 flex justify-between items-center">
                <div>
                    <div class="text-brand-400 text-sm mb-1">Endereço selecionado</div>
                    <div class="text-2xl font-bold text-white">{{ $endereco->corredor }}-{{ $endereco->estante }}-{{ $endereco->nivel }}</div>
                </div>
                <div class="text-right">
                    <div class="text-gray-400 text-xs">Lotes Registrados</div>
                    <div class="text-xl font-bold text-white">{{ count($itensNoEndereco) }}</div>
                </div>
            </div>

            <div class="space-y-4 mb-24">
                @forelse($itensNoEndereco as $lote)
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <div class="text-white font-bold mb-1">{{ $lote['produto']['nome'] ?? 'Produto' }}</div>
                        <div class="text-gray-400 text-xs mb-3">Lote: {{ $lote['codigo_lote'] }} | Qtd Sistema: {{ $lote['quantidade_atual'] }}</div>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-400">Contagem:</span>
                            <input type="number" wire:model="contagens.{{ $lote['id'] }}" class="flex-1 bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white font-bold text-center focus:border-brand-500 focus:outline-none" />
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">Nenhum produto atrelado a este endereço no sistema.</div>
                @endforelse
            </div>

            <div class="fixed bottom-0 left-0 right-0 p-4 bg-gray-900/95 border-t border-gray-800 safe-bottom z-50 flex gap-3">
                <button wire:click="finalizarContagem" class="flex-1 bg-brand-500 text-gray-900 py-4 rounded-xl font-bold text-lg shadow-[0_0_15px_rgba(245,158,11,0.5)]">
                    Salvar Inventário
                </button>
            </div>
        @endif
    </div>
</div>

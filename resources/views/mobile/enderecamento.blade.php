<div>
    <div class="bg-gray-900 border-b border-gray-800 safe-top p-4 sticky top-0 z-10 flex items-center gap-3">
        @if($modo !== 'scan_lote')
            <button wire:click="voltar" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
        @else
            <a href="{{ route('mobile.hub') }}" class="text-gray-400 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
        @endif
        <h1 class="text-xl font-bold text-white">Endereçamento</h1>
    </div>

    <div class="p-4 page-content">
        @if($modo === 'scan_lote')
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-teal-500/20 text-teal-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Escanear Lote S/ Endereço</h2>
                <p class="text-gray-400 mb-8">Escaneie o código de barras do lote recém conferido que aguarda endereçamento.</p>
                <div class="p-4 bg-gray-800 border border-gray-700 rounded-xl flex items-center gap-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <div class="text-left">
                        <div class="text-white font-bold">Leitor Ativo</div>
                        <div class="text-gray-400 text-sm">Aguardando código do lote...</div>
                    </div>
                </div>
            </div>
        @elseif($modo === 'scan_destino')
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 mb-6">
                <div class="text-gray-400 text-sm mb-1">Lote a Endereçar</div>
                <div class="text-xl font-bold text-white">{{ $loteSelecionado->produto->nome }}</div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-400 text-sm">Lote: {{ $loteSelecionado->codigo_lote }}</span>
                    <span class="bg-teal-500/20 text-teal-400 text-xs px-2 py-1 rounded font-bold">Qtd: {{ $loteSelecionado->quantidade_atual }}</span>
                </div>
            </div>

            <div class="text-center py-6">
                @if(!$enderecoDestino)
                    <h2 class="text-xl font-bold text-white mb-2">Escanear Prateleira</h2>
                    <p class="text-gray-400 mb-6">Escaneie o código de barras da prateleira onde o lote será guardado.</p>
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-xl flex items-center gap-4">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <div class="text-left">
                            <div class="text-white font-bold">Leitor Ativo</div>
                            <div class="text-gray-400 text-sm">Aguardando endereço...</div>
                        </div>
                    </div>
                @else
                    <div class="bg-teal-500/10 rounded-xl p-4 border border-teal-500/20 mb-6">
                        <div class="text-teal-400 text-sm mb-1">Localização Selecionada</div>
                        <div class="text-2xl font-bold text-white">{{ $enderecoDestino->corredor }}-{{ $enderecoDestino->estante }}-{{ $enderecoDestino->nivel }}</div>
                    </div>
                    
                    <button wire:click="confirmarEnderecamento" class="w-full py-4 rounded-xl font-bold text-lg bg-brand-500 text-gray-900 shadow-[0_0_15px_rgba(245,158,11,0.5)]">
                        Guardar Produto (Confirmar)
                    </button>
                @endif
            </div>
        @elseif($modo === 'concluido')
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Endereçado com Sucesso!</h2>
                <p class="text-gray-400 mb-8">O lote foi guardado no sistema.</p>
                <button wire:click="voltar" class="bg-gray-800 text-white w-full py-4 rounded-xl font-bold">Novo Endereçamento</button>
            </div>
        @endif
    </div>
</div>

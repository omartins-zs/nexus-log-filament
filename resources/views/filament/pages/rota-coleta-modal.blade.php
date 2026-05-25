<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    corePlugins: {
      preflight: false,
    }
  }
</script>
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
        <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Itens a Separar <span class="text-xs font-bold bg-warning-100 text-warning-800 px-2 py-1 rounded-full uppercase tracking-wider ml-2">Prioridade FEFO</span>
        </h3>
    </div>
    
    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
        @if($lotes->isEmpty())
            <div class="p-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl text-center">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="font-bold text-lg">Estoque Esgotado ou Vencido</div>
                <div class="text-sm mt-1">Nenhum lote válido disponível para coleta.</div>
            </div>
        @else
            @foreach($lotes as $index => $lote)
                <div class="relative p-5 border-2 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all hover:border-blue-500 
                    {{ $index === 0 ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700' }}">
                    
                    @if($index === 0)
                        <div class="absolute -top-3 -left-3 bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-black text-sm shadow-lg border-2 border-white dark:border-gray-900">
                            1º
                        </div>
                    @endif

                    <div class="flex-1 ml-2">
                        <div class="font-black text-xl text-gray-900 dark:text-white mb-1">{{ $lote->produto->nome }}</div>
                        <div class="flex flex-wrap gap-2 text-xs font-mono">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">
                                Lote: <span class="font-bold text-gray-900 dark:text-white">{{ $lote->codigo_lote }}</span>
                            </span>
                            <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
                                Saldo: <span class="font-bold text-blue-900 dark:text-blue-100">{{ $lote->quantidade_atual }} un</span>
                            </span>
                            @if($lote->data_validade)
                                <span class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2 py-1 rounded">
                                    Vence em: <span class="font-bold">{{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="text-left md:text-right w-full md:w-auto bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                        @if($lote->endereco)
                            <div class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-1">Vá Para o Endereço:</div>
                            <div class="text-3xl font-black text-blue-600 dark:text-blue-400 tracking-tight">
                                {{ $lote->endereco->corredor }}-{{ $lote->endereco->estante }}-{{ $lote->endereco->nivel }}
                            </div>
                            <div class="text-[10px] text-gray-400 mt-1 font-mono">Cód: {{ $lote->endereco->codigo_barras }}</div>
                        @else
                            <div class="text-2xl font-black text-gray-300 dark:text-gray-600">S/ ENDEREÇO</div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

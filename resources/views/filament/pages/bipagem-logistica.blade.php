<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="space-y-6">

        <!-- Seleção de Modo -->
        <div class="grid grid-cols-2 gap-4">
            <button 
                wire:click="alterarModo('conferencia')" 
                class="p-4 rounded-xl border-2 font-bold text-lg text-center transition-all shadow-sm
                    {{ $modo === 'conferencia' ? 'border-primary-500 bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400' : 'border-gray-200 bg-white text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
            >
                <div class="flex flex-col items-center gap-2">
                    <x-filament::icon icon="heroicon-o-clipboard-document-check" class="w-8 h-8" />
                    <span>Modo Conferência</span>
                    <span class="text-xs font-normal opacity-80">Travar fluxo para Conferência (Packing)</span>
                </div>
            </button>

            <button 
                wire:click="alterarModo('expedicao')" 
                class="p-4 rounded-xl border-2 font-bold text-lg text-center transition-all shadow-sm
                    {{ $modo === 'expedicao' ? 'border-purple-500 bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : 'border-gray-200 bg-white text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
            >
                <div class="flex flex-col items-center gap-2">
                    <x-filament::icon icon="heroicon-o-truck" class="w-8 h-8" />
                    <span>Modo Expedição</span>
                    <span class="text-xs font-normal opacity-80">Travar fluxo para Despacho na Doca</span>
                </div>
            </button>
        </div>

        <!-- Área de Input do Leitor -->
        <x-filament::section>
            <div class="flex flex-col items-center py-6">
                <div class="mb-4 text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                        Aguardando Bipagem
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        Utilize o leitor físico de código de barras ou cole o código manualmente e pressione Enter.
                    </p>
                </div>

                <form wire:submit.prevent="processarBipagem" class="w-full max-w-xl">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-filament::icon icon="heroicon-o-qr-code" class="h-6 w-6 text-gray-400" />
                        </div>
                        <input 
                            wire:model.defer="codigo" 
                            type="text" 
                            autofocus 
                            autocomplete="off"
                            class="block w-full pl-12 pr-4 py-4 text-xl border-gray-300 rounded-xl shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="BIPE O CÓDIGO AQUI..."
                        >
                    </div>
                    
                    <!-- Botão Escondido para Submissão via Enter do Leitor -->
                    <button type="submit" class="hidden">Bipar</button>
                </form>
            </div>
        </x-filament::section>

        <!-- Lista de Últimas Bipagens -->
        <x-filament::section>
            <x-slot name="heading">
                Últimas Bipagens da Sessão
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Hora</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Código Lido</th>
                            <th scope="col" class="px-6 py-3">Pedido #</th>
                            <th scope="col" class="px-6 py-3">Cliente</th>
                            <th scope="col" class="px-6 py-3">Mensagem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ultimasBipagens as $bipagem)
                            <tr wire:key="{{ $bipagem['id_unico'] }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $bipagem['hora'] }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($bipagem['sucesso'])
                                        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">
                                            <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4"/>
                                            OK
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md text-xs font-medium bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">
                                            <x-filament::icon icon="heroicon-o-x-circle" class="w-4 h-4"/>
                                            ERRO
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $bipagem['codigo_lido'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $bipagem['pedido_id'] ? '#' . $bipagem['pedido_id'] : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $bipagem['cliente'] }}
                                </td>
                                <td class="px-6 py-4 {{ $bipagem['sucesso'] ? 'text-gray-600 dark:text-gray-300' : 'text-danger-600 font-semibold' }}">
                                    {{ $bipagem['mensagem'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Nenhuma bipagem realizada nesta sessão.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false,
        }
      }
    </script>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="conferenciaPage()">
        <!-- PAINEL DE BIPAGEM (ESQUERDA) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-8 shadow-sm flex flex-col items-center justify-center text-center relative overflow-hidden">
                <!-- Efeito Visual de Laser de Fundo -->
                <div class="absolute inset-x-0 top-0 h-1 bg-red-600 animate-pulse"></div>

                <div class="w-16 h-16 bg-red-50 dark:bg-red-950/30 rounded-full flex items-center justify-center text-red-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Estação de Conferência Ativa</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mt-1 mb-6">
                    Aponte o leitor de código de barras para a etiqueta do volume e bipe. O sistema processará automaticamente.
                </p>

                <!-- INPUT DE CONFERÊNCIA -->
                <form wire:submit.prevent="conferir" class="w-full max-w-lg relative">
                    <input 
                        id="barcode-scanner-input"
                        type="text" 
                        wire:model="barcode"
                        placeholder="AGUARDANDO BIPAGEM..." 
                        autocomplete="off"
                        class="w-full text-center text-2xl font-mono tracking-widest bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl px-4 py-5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all placeholder-gray-400 dark:placeholder-gray-600"
                        autofocus
                    />
                    
                    <button type="submit" class="hidden">Conferir</button>

                    <div class="mt-3 text-xs text-gray-400 dark:text-gray-500 flex items-center justify-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        O leitor deve estar configurado para enviar "Enter" ao final
                    </div>
                </form>
            </div>

            <!-- ÚLTIMO PEDIDO CONFERIDO (DETALHES) -->
            @if($ultimoPedido)
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between border-bottom pb-4 mb-4 border-gray-100 dark:border-gray-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Dados do Último Volume Bipado
                        </h3>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Bipado às {{ $ultimoPedido['data'] }}</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="space-y-1">
                            <span class="text-xs text-gray-400 uppercase tracking-wider block">ID Pedido</span>
                            <span class="text-lg font-mono font-bold text-red-600">#{{ $ultimoPedido['id'] }}</span>
                        </div>
                        <div class="space-y-1 col-span-2">
                            <span class="text-xs text-gray-400 uppercase tracking-wider block">Cliente Destinatário</span>
                            <span class="text-sm font-semibold text-gray-950 dark:text-white block truncate">{{ $ultimoPedido['cliente'] }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-400 uppercase tracking-wider block">Status Atual</span>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold uppercase" style="background-color: #d1fae5; color: #065f46;">
                                {{ $ultimoPedido['status_label'] }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-4 border-t border-gray-100 dark:border-gray-800/50">
                        <div class="space-y-1">
                            <span class="text-xs text-gray-400 uppercase tracking-wider block">Produto</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200 block truncate">{{ $ultimoPedido['produto'] }}</span>
                            <span class="text-xs font-mono text-gray-400 block">SKU: {{ $ultimoPedido['sku'] }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-gray-400 uppercase tracking-wider block">Quantidade / CD Origem</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-200 block">{{ $ultimoPedido['quantidade'] }} volumes</span>
                            <span class="text-xs text-gray-400 block truncate">{{ $ultimoPedido['cd'] }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl p-12 text-center text-gray-400">
                    <p class="text-sm">Nenhum pedido bipado nesta sessão ainda.</p>
                </div>
            @endif
        </div>

        <!-- LOG HISTÓRICO RÁPIDO (DIREITA) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm h-full flex flex-col">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                    <span>Logs Recentes (Sessão)</span>
                    <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded text-gray-500">{{ count($historico) }}</span>
                </h3>

                <div class="flex-1 space-y-3 overflow-y-auto max-h-[350px]">
                    @forelse($historico as $item)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-950 rounded-lg border border-gray-100 dark:border-gray-800/80 hover:border-gray-200 transition-colors">
                            <span class="text-xs font-bold font-mono bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 px-1.5 py-0.5 rounded">
                                #{{ $item['id'] }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item['cliente'] }}</p>
                                <p class="text-[10px] text-gray-400 truncate">{{ $item['produto'] }} (Qtd: {{ $item['quantidade'] }})</p>
                            </div>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $item['horario'] }}</span>
                        </div>
                    @empty
                        <div class="h-40 flex items-center justify-center text-center text-gray-400 dark:text-gray-500 text-xs">
                            Aguardando primeiras bipagens...
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- AUDIO PLAYBACK & FOCUS MANAGER (Web Audio API Synthesizer) -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Monitorar som disparado pelo Livewire
            Livewire.on('play-sound', (event) => {
                const type = event.type || 'success';
                playBeep(type);
            });

            // Gerenciamento Inteligente de Foco do Barcode
            const input = document.getElementById('barcode-scanner-input');
            
            if (input) {
                // Focar no boot do Livewire
                setTimeout(() => input.focus(), 300);

                // Auto-refoco caso perca o foco (ex: clicar na tela por engano)
                document.addEventListener('click', (e) => {
                    const activeElement = document.activeElement;
                    const isInteractive = activeElement.tagName === 'INPUT' || 
                                          activeElement.tagName === 'SELECT' || 
                                          activeElement.tagName === 'TEXTAREA' || 
                                          activeElement.closest('.fi-modal') ||
                                          activeElement.closest('button');

                    if (!isInteractive) {
                        input.focus();
                    }
                });
            }
        });

        // Sintetizador Web Audio nativo para feedbacks de beeps robustos
        function playBeep(type) {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();

                if (type === 'success') {
                    // Double High Beep (Beep-Beep!)
                    beep(ctx, 880, 0.08, 0);      // A5 nota alta
                    beep(ctx, 1046.50, 0.12, 0.1); // C6 mais alta
                } else if (type === 'warning') {
                    // Warning Beep (Caution)
                    beep(ctx, 587.33, 0.2, 0);     // D5 nota média
                } else if (type === 'error') {
                    // Low Buzzer Beep
                    beep(ctx, 150, 0.4, 0, 'sawtooth'); // Frequência baixa zumbido
                }
            } catch (e) {
                console.error("Web Audio API not allowed or failed: ", e);
            }
        }

        function beep(ctx, freq, duration, delay, oscType = 'sine') {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.type = oscType;
            osc.frequency.setValueAtTime(freq, ctx.currentTime + delay);
            
            gain.gain.setValueAtTime(0.15, ctx.currentTime + delay);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + delay + duration);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.start(ctx.currentTime + delay);
            osc.stop(ctx.currentTime + delay + duration);
        }
    </script>
</x-filament-panels::page>

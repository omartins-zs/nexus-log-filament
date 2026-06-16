<div>
    @if($modo === 'selecao')
        <!-- Header -->
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('mobile.hub') }}" style="color: #94a3b8; text-decoration: none; display: flex;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </a>
                    <h1 style="font-size: 1.2rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Inventário</h1>
                </div>
            </div>
            
            <!-- Search bar -->
            <div style="position: relative;">
                <input type="text" wire:model.live="search" placeholder="Escanear ou digite o endereço (Ex: A-01-01)..." style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 10px 12px 10px 36px; color: #f8fafc; font-family: inherit; font-size: 0.85rem; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='rgba(59, 130, 246, 0.4)'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" style="width: 16px; height: 16px; position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 1rem 1.25rem 2rem; display: flex; flex-direction: column; gap: 0.85rem;">
            @if(strlen($search) >= 2)
                <!-- Search Results -->
                <p style="font-size: 0.7rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.25rem; padding-left: 2px;">Resultados da Busca ({{ count($resultadosBusca) }})</p>
                @forelse($resultadosBusca as $index => $end)
                    <div wire:click="selecionarEndereco({{ $end->id }})" class="glass-card animate-fade-in-up" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-color: rgba(59, 130, 246, 0.15); background: rgba(59, 130, 246, 0.02);">
                        <div>
                            <span style="font-size: 0.95rem; font-weight: 800; color: #f8fafc; font-family: monospace;">📍 {{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</span>
                            <div style="font-size: 0.7rem; color: #64748b; margin-top: 1px;">Cód: {{ $end->codigo_barras }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.75rem; color: #94a3b8; background: rgba(248, 250, 252, 0.05); padding: 2px 8px; border-radius: 6px;">
                                {{ $end->lotes_count }} lote(s)
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#3b82f6" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </div>
                    </div>
                @empty
                    <div class="glass-card text-center" style="padding: 2rem 1.5rem; text-align: center; color: #64748b; font-size: 0.8rem;">
                        Nenhum endereço encontrado para "{{ $search }}".
                    </div>
                @endforelse
            @else
                <!-- Recent Locations -->
                <p style="font-size: 0.7rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.25rem; padding-left: 2px;">Endereços do Galpão</p>
                @forelse($enderecosRecentes as $index => $end)
                    <div wire:click="selecionarEndereco({{ $end->id }})" class="glass-card animate-fade-in-up stagger-{{ $index % 5 + 1 }}" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; opacity: 0;">
                        <div>
                            <span style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1; font-family: monospace;">📍 {{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</span>
                            <div style="font-size: 0.65rem; color: #64748b; margin-top: 1px;">Código: {{ $end->codigo_barras }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.7rem; color: #64748b; background: rgba(15, 23, 42, 0.3); padding: 2px 8px; border-radius: 6px;">
                                {{ $end->lotes_count }} lote(s)
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#475569" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </div>
                    </div>
                @empty
                    <div class="glass-card text-center" style="padding: 2rem; color: #64748b; font-size: 0.8rem;">
                        Nenhum endereço cadastrado no sistema.
                    </div>
                @endforelse
            @endif
        </div>
    @elseif($modo === 'contando')
        <!-- Header -->
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem 0.75rem; position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.4);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <button wire:click="voltar" style="background: none; border: none; padding: 0; color: #94a3b8; display: flex; cursor: pointer; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </button>
                <div>
                    <h1 style="font-size: 1.15rem; font-weight: 800; color: #f8fafc; margin: 0; font-family: monospace;">📍 {{ $endereco->corredor }}-{{ $endereco->estante }}-{{ $endereco->nivel }}</h1>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">Contagem de Inventário Física</p>
                </div>
            </div>
        </div>

        <!-- Counting Checklist -->
        <div style="padding: 1rem 1.25rem 6.5rem; display: flex; flex-direction: column; gap: 0.85rem;">
            <p style="font-size: 0.7rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.25rem; padding-left: 2px;">Lotes no Endereço ({{ count($itensNoEndereco) }})</p>
            
            @forelse($itensNoEndereco as $index => $item)
                @php
                    $loteId = $item['id'];
                    $quantidadeEsperada = $item['quantidade_atual'];
                    $quantidadeContada = $contagens[$loteId] ?? 0;
                    $divergente = $quantidadeContada != $quantidadeEsperada;
                @endphp
                <div class="glass-card" style="padding: 1rem; border-color: {{ $divergente ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)' }}; background: {{ $divergente ? 'rgba(239, 68, 68, 0.02)' : 'rgba(16, 185, 129, 0.02)' }}; display: flex; flex-direction: column; gap: 8px;">
                    <!-- Product details -->
                    <div>
                        <h4 style="font-size: 0.85rem; font-weight: 700; color: #f8fafc; margin: 0 0 4px;">{{ $item['produto']['nome'] }}</h4>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span style="font-size: 0.65rem; background: rgba(248, 250, 252, 0.04); color: #94a3b8; padding: 1px 5px; border-radius: 4px; font-family: monospace;">Lote: {{ $item['codigo_lote'] }}</span>
                            <span style="font-size: 0.65rem; color: #64748b;">Sistema: <strong style="color: #cbd5e1; font-family: monospace;">{{ $quantidadeEsperada }}</strong></span>
                        </div>
                    </div>
                    
                    <!-- Numeric input and helpers -->
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 4px; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 8px;">
                        <!-- Quick buttons -->
                        <div style="display: flex; gap: 4px;">
                            <button type="button" wire:click="registrarContagem({{ $loteId }}, 0)" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 8px; padding: 6px 10px; font-size: 0.7rem; font-weight: 700; cursor: pointer; font-family: inherit;">
                                Zerar
                            </button>
                            <button type="button" wire:click="registrarContagem({{ $loteId }}, {{ $quantidadeEsperada }})" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 8px; padding: 6px 10px; font-size: 0.7rem; font-weight: 700; cursor: pointer; font-family: inherit;">
                                Bater
                            </button>
                        </div>
                        
                        <!-- Value editor -->
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <button type="button" onclick="adjustCountVal({{ $loteId }}, -1)" style="background: rgba(248, 250, 252, 0.05); color: #cbd5e1; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; width: 32px; height: 32px; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center;">-</button>
                            
                            <input type="number" id="countInput_{{ $loteId }}" value="{{ $quantidadeContada }}" onchange="updateCountVal({{ $loteId }}, this.value)" style="width: 64px; text-align: center; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; padding: 6px; color: #f8fafc; font-family: monospace; font-size: 0.9rem; font-weight: 700; outline: none;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'">
                            
                            <button type="button" onclick="adjustCountVal({{ $loteId }}, 1)" style="background: rgba(248, 250, 252, 0.05); color: #cbd5e1; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; width: 32px; height: 32px; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card text-center" style="padding: 2.5rem 1rem; color: #64748b; font-size: 0.8rem; text-align: center;">
                    Este endereço está vazio no momento.
                </div>
            @endforelse
        </div>

        <!-- Sticky Bottom Bar -->
        <div style="position: fixed; bottom: calc(4.75rem + env(safe-area-inset-bottom, 0px)); left: 0; right: 0; padding: 0.75rem 1.25rem; background: linear-gradient(to top, #0f172a 70%, transparent); z-index: 30;">
            <button type="button" wire:click="finalizarContagem" style="width: 100%; border: none; font-family: inherit; font-size: 0.95rem; font-weight: 700; border-radius: 14px; padding: 14px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Salvar Contagem
            </button>
        </div>

        <script>
            function adjustCountVal(loteId, delta) {
                const input = document.getElementById('countInput_' + loteId);
                if (input) {
                    let val = parseInt(input.value) || 0;
                    val = Math.max(0, val + delta);
                    input.value = val;
                    updateCountVal(loteId, val);
                }
            }

            function updateCountVal(loteId, value) {
                const val = Math.max(0, parseInt(value) || 0);
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('registrar-contagem-js', { loteId: loteId, val: val });
                }
            }
        </script>
        
        <!-- Livewire listener inside component helper -->
        @script
        <script>
            $wire.on('registrar-contagem-js', (data) => {
                $wire.registrarContagem(data.loteId, data.val);
            });
        </script>
        @endscript
    @endif
</div>

<div>
    <!-- Header -->
    @if($modo !== 'concluido')
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem 0.75rem; position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.4);">
            <div style="display: flex; align-items: center; gap: 10px;">
                @if($modo !== 'origem')
                    <button wire:click="voltar" style="background: none; border: none; padding: 0; color: #94a3b8; display: flex; cursor: pointer; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </button>
                @else
                    <a href="{{ route('mobile.hub') }}" style="color: #94a3b8; text-decoration: none; display: flex; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </a>
                @endif
                <div>
                    <h1 style="font-size: 1.15rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Transferência</h1>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                        @switch($modo)
                            @case('origem') Passo 1: Selecione a Origem @break
                            @case('lote_selecao') Passo 1: Selecione o Lote @break
                            @case('quantidade') Passo 2: Informe a Quantidade @break
                            @case('destino') Passo 3: Selecione o Destino @break
                            @case('confirmar') Passo 4: Confirme a Transferência @break
                        @endswitch
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Content -->
    <div style="padding: 1rem 1.25rem 2rem; display: flex; flex-direction: column; gap: 0.85rem;">
        @if($modo === 'origem')
            <!-- Step 1: Scan/Search Origin -->
            <div class="glass-card" style="padding: 0.85rem 1rem; border-color: rgba(168, 85, 247, 0.15); background: rgba(168, 85, 247, 0.02); display: flex; flex-direction: column; gap: 6px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #a855f7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                    <span style="font-size: 0.8rem; font-weight: 700;">Instrução de Origem</span>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; line-height: 1.4; margin: 0;">Use o botão **Scan** para ler o código do endereço de origem (ou do lote diretamente). Ou digite e selecione abaixo.</p>
            </div>

            <!-- Search bar -->
            <div style="position: relative; margin-top: 4px;">
                <input type="text" wire:model.live="searchOrigem" placeholder="Buscar endereço de origem (Ex: A-01)..." style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 10px 12px 10px 36px; color: #f8fafc; font-family: inherit; font-size: 0.85rem; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='rgba(168, 85, 247, 0.4)'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" style="width: 16px; height: 16px; position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>

            <!-- Results -->
            @if(strlen($searchOrigem) >= 2)
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 4px;">
                    @forelse($resultadosOrigem as $end)
                        <div wire:click="selecionarOrigemEndereco({{ $end->id }})" class="glass-card animate-fade-in-up" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px;">
                            <div>
                                <span style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1; font-family: monospace;">📍 {{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#a855f7" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </div>
                    @empty
                        <div class="glass-card text-center" style="padding: 1.5rem; color: #64748b; font-size: 0.8rem; text-align: center;">
                            Nenhum endereço de origem encontrado.
                        </div>
                    @endforelse
                </div>
            @endif
        @elseif($modo === 'lote_selecao')
            <!-- Step 1b: Select Lot at Address -->
            <p style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 2px;">Vários lotes encontrados no endereço **{{ $origemEndereco->corredor }}-{{ $origemEndereco->estante }}-{{ $origemEndereco->nivel }}**. Selecione qual deseja movimentar:</p>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($lotes as $l)
                    <div wire:click="selecionarLote({{ $l->id }})" class="glass-card animate-fade-in-up" style="cursor: pointer; padding: 12px; display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <h4 style="font-size: 0.85rem; font-weight: 700; color: #f8fafc; margin: 0;">{{ $l->produto->nome }}</h4>
                            <span style="font-size: 0.85rem; font-weight: 800; color: #a855f7; font-family: monospace; flex-shrink: 0;">Saldo: {{ $l->quantidade_atual }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 6px;">
                            <span style="font-size: 0.65rem; color: #64748b; font-family: monospace;">Lote: {{ $l->codigo_lote }}</span>
                            <span style="font-size: 0.75rem; color: #a855f7; font-weight: 600; display: flex; align-items: center; gap: 2px;">
                                Selecionar
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 12px; height: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($modo === 'quantidade')
            <!-- Step 2: Choose quantity -->
            <div class="glass-card" style="padding: 1.15rem; display: flex; flex-direction: column; gap: 8px;">
                <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Produto Selecionado</span>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #f8fafc; margin: 0;">{{ $origemLote->produto->nome }}</h3>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 8px; margin-top: 2px;">
                    <div>
                        <p style="font-size: 0.7rem; color: #64748b; margin: 0 0 2px;">Origem: <strong style="color: #cbd5e1; font-family: monospace;">{{ $origemEndereco ? $origemEndereco->corredor.'-'.$origemEndereco->estante.'-'.$origemEndereco->nivel : 'Estoque Externo' }}</strong></p>
                        <p style="font-size: 0.7rem; color: #64748b; margin: 0;">Lote: <strong style="color: #cbd5e1; font-family: monospace;">{{ $origemLote->codigo_lote }}</strong></p>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.65rem; color: #64748b; display: block;">Saldo Atual</span>
                        <strong style="font-size: 1rem; color: #a855f7; font-family: monospace;">{{ $origemLote->quantidade_atual }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quantity input form -->
            <div class="glass-card" style="padding: 1.15rem; display: flex; flex-direction: column; gap: 10px; margin-top: 4px;">
                <label for="transferQtd" style="font-size: 0.8rem; font-weight: 600; color: #94a3b8;">Quantidade a Transferir</label>
                
                <div style="display: flex; align-items: center; gap: 10px; justify-content: center; margin: 0.5rem 0;">
                    <button type="button" onclick="adjustTransferQtd(-10)" style="background: rgba(248, 250, 252, 0.05); color: #cbd5e1; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; width: 40px; height: 36px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">-10</button>
                    <button type="button" onclick="adjustTransferQtd(-1)" style="background: rgba(248, 250, 252, 0.05); color: #cbd5e1; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; width: 36px; height: 36px; font-size: 1.1rem; font-weight: 700; cursor: pointer;">-</button>
                    
                    <input type="number" id="transferQtd" value="{{ $quantidade }}" onchange="updateTransferQtd(this.value)" style="width: 76px; text-align: center; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; padding: 8px; color: #f8fafc; font-family: monospace; font-size: 1.1rem; font-weight: 700; outline: none;" onfocus="this.style.borderColor='#a855f7'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'" min="1" max="{{ $origemLote->quantidade_atual }}">
                    
                    <button type="button" onclick="adjustTransferQtd(1)" style="background: rgba(248, 250, 252, 0.05); color: #cbd5e1; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; width: 36px; height: 36px; font-size: 1.1rem; font-weight: 700; cursor: pointer;">+</button>
                    <button type="button" onclick="adjustTransferQtd(10)" style="background: rgba(248, 250, 252, 0.05); color: #cbd5e1; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; width: 40px; height: 36px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">+10</button>
                </div>
                
                <button type="button" onclick="setTransferMax()" style="background: rgba(168, 85, 247, 0.1); color: #a855f7; border: 1px solid rgba(168, 85, 247, 0.15); border-radius: 8px; padding: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; width: 100%;">
                    Selecionar Total Máximo ({{ $origemLote->quantidade_atual }})
                </button>
            </div>
            
            <button type="button" onclick="submitTransferQtd()" style="background: linear-gradient(135deg, #a855f7, #9333ea); color: #f8fafc; border: none; border-radius: 12px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; box-shadow: 0 4px 15px rgba(168, 85, 247, 0.25); margin-top: 8px;">
                Avançar para Destino
            </button>

            <script>
                function adjustTransferQtd(delta) {
                    const input = document.getElementById('transferQtd');
                    if (input) {
                        let val = parseInt(input.value) || 0;
                        val = Math.max(1, Math.min({{ $origemLote->quantidade_atual }}, val + delta));
                        input.value = val;
                        updateTransferQtd(val);
                    }
                }

                function setTransferMax() {
                    const input = document.getElementById('transferQtd');
                    if (input) {
                        input.value = {{ $origemLote->quantidade_atual }};
                        updateTransferQtd({{ $origemLote->quantidade_atual }});
                    }
                }

                function updateTransferQtd(value) {
                    const val = Math.max(1, Math.min({{ $origemLote->quantidade_atual }}, parseInt(value) || 0));
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('update-qty-js', { val: val });
                    }
                }

                function submitTransferQtd() {
                    const input = document.getElementById('transferQtd');
                    if (input) {
                        const val = parseInt(input.value) || 0;
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('confirm-qty-js', { val: val });
                        }
                    }
                }
            </script>
            
            @script
            <script>
                $wire.on('update-qty-js', (data) => { $wire.quantidade = data.val; });
                $wire.on('confirm-qty-js', (data) => { $wire.confirmarQuantidade(data.val); });
            </script>
            @endscript
        @elseif($modo === 'destino')
            <!-- Step 3: Scan/Search Destination -->
            <div class="glass-card" style="padding: 0.85rem 1rem; border-color: rgba(16, 185, 129, 0.15); background: rgba(16, 185, 129, 0.02); display: flex; flex-direction: column; gap: 6px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #10b981;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    <span style="font-size: 0.8rem; font-weight: 700;">Instrução de Destino</span>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; line-height: 1.4; margin: 0;">Use o botão **Scan** para ler o código do endereço de destino. Ou digite e selecione abaixo.</p>
            </div>

            <!-- Search bar -->
            <div style="position: relative; margin-top: 4px;">
                <input type="text" wire:model.live="searchDestino" placeholder="Buscar endereço de destino (Ex: B-02)..." style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 10px 12px 10px 36px; color: #f8fafc; font-family: inherit; font-size: 0.85rem; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='rgba(16, 185, 129, 0.4)'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" style="width: 16px; height: 16px; position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>

            <!-- Results -->
            @if(strlen($searchDestino) >= 2)
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 4px;">
                    @forelse($resultadosDestino as $end)
                        @if($end->id !== $origemEnderecoId)
                            <div wire:click="selecionarDestinoEndereco({{ $end->id }})" class="glass-card animate-fade-in-up" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px;">
                                <div>
                                    <span style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1; font-family: monospace;">📍 {{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10b981" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </div>
                        @endif
                    @empty
                        <div class="glass-card text-center" style="padding: 1.5rem; color: #64748b; font-size: 0.8rem; text-align: center;">
                            Nenhum endereço de destino encontrado.
                        </div>
                    @endforelse
                </div>
            @endif
        @elseif($modo === 'confirmar')
            <!-- Step 4: Confirm transaction summary -->
            <p style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 2px;">Confirme os dados antes de efetuar a transferência física do lote:</p>
            
            <div class="glass-card" style="padding: 1.15rem; display: flex; flex-direction: column; gap: 10px;">
                <div>
                    <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Produto</span>
                    <div style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1;">{{ $origemLote->produto->nome }}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace; margin-top: 1px;">Lote: {{ $origemLote->codigo_lote }}</div>
                </div>
                
                <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 10px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                    <div>
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">De (Origem)</span>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #f59e0b; font-family: monospace;">
                            📍 {{ $origemEndereco ? $origemEndereco->corredor.'-'.$origemEndereco->estante.'-'.$origemEndereco->nivel : 'Estoque Externo' }}
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Para (Destino)</span>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #10b981; font-family: monospace;">
                            📍 {{ $destinoEndereco->corredor }}-{{ $destinoEndereco->estante }}-{{ $destinoEndereco->nivel }}
                        </div>
                    </div>
                </div>
                
                <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Quantidade a Mover</span>
                    <span style="font-size: 1.15rem; font-weight: 900; color: #a855f7; font-family: monospace; background: rgba(168, 85, 247, 0.1); padding: 4px 12px; border-radius: 8px;">
                        {{ $quantidade }}
                    </span>
                </div>
            </div>
            
            <button type="button" wire:click="executarTransferencia" style="background: linear-gradient(135deg, #10b981, #059669); color: #0f172a; border: none; border-radius: 12px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); margin-top: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Confirmar Transferência
            </button>
        @endif
    </div>

    @if($modo === 'concluido')
        <!-- Completed Celebration -->
        <div style="padding: 3rem 1.5rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; gap: 1.5rem;">
            <!-- Outer Glow ring -->
            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                <div style="position: absolute; width: 110px; height: 110px; border-radius: 50%; background: rgba(168, 85, 247, 0.15); filter: blur(10px); animation: pulseGlowRing 2s infinite;"></div>
                
                <div style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, #a855f7, #9333ea); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); position: relative; z-index: 10;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#f8fafc" style="width: 34px; height: 34px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <h2 style="font-size: 1.4rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Transferência Efetuada!</h2>
                <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.4; max-width: 260px; margin: 0 auto;">O lote foi fisicamente transferido de endereço e registrado no histórico logístico.</p>
            </div>
            
            <!-- Summary card -->
            <div class="glass-card animate-fade-in-up" style="width: 100%; max-width: 320px; padding: 1.15rem; background: rgba(30, 41, 59, 0.5); border-color: rgba(248, 250, 252, 0.05); text-align: left; display: flex; flex-direction: column; gap: 8px;">
                <div>
                    <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Produto</span>
                    <div style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1;">{{ $origemLote->produto->nome }}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace; margin-top: 1px;">Lote: {{ $origemLote->codigo_lote }}</div>
                </div>
                
                <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 8px; margin-top: 2px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                    <div>
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Origem</span>
                        <div style="font-size: 0.8rem; font-weight: 600; color: #cbd5e1;">
                            📍 {{ $origemEndereco ? $origemEndereco->corredor.'-'.$origemEndereco->estante.'-'.$origemEndereco->nivel : 'Estoque Externo' }}
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Destino</span>
                        <div style="font-size: 0.8rem; font-weight: 600; color: #10b981; font-weight: 700;">
                            📍 {{ $destinoEndereco->corredor }}-{{ $destinoEndereco->estante }}-{{ $destinoEndereco->nivel }}
                        </div>
                    </div>
                </div>
                
                <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Quantidade Transferida</span>
                    <span style="font-size: 0.95rem; font-weight: 800; color: #a855f7; font-family: monospace;">{{ $quantidade }}</span>
                </div>
            </div>
            
            <button type="button" wire:click="voltar" style="width: 100%; max-width: 320px; background: rgba(248, 250, 252, 0.06); color: #f8fafc; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; font-family: inherit; transition: all 0.2s;" onmouseover="this.style.background='rgba(248, 250, 252, 0.1)'" onmouseout="this.style.background='rgba(248, 250, 252, 0.06)'">
                Nova Transferência
            </button>
        </div>
        
        <style>
            @keyframes pulseGlowRing {
                0%, 100% { transform: scale(1); opacity: 0.8; }
                50% { transform: scale(1.15); opacity: 0.4; }
            }
        </style>
    @endif
</div>

<div>
    @if($modo === 'selecao')
        <!-- Header -->
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('mobile.hub') }}" style="color: #94a3b8; text-decoration: none; display: flex;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </a>
                    <h1 style="font-size: 1.2rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Separação</h1>
                </div>
                <div style="background: rgba(245, 158, 11, 0.1); border-radius: 20px; padding: 4px 10px; border: 1px solid rgba(245, 158, 11, 0.2); flex-shrink: 0;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #f59e0b;">{{ count($pedidos) }} pendente(s)</span>
                </div>
            </div>
            <p style="font-size: 0.75rem; color: #64748b; margin: 0; padding-left: 32px;">Selecione um pedido para iniciar a rota de coleta.</p>
        </div>

        <!-- Content -->
        <div style="padding: 1rem 1.25rem 2rem; display: flex; flex-direction: column; gap: 0.85rem;">
            @forelse($pedidos as $index => $ped)
                <div wire:click="selecionarPedido({{ $ped->id }})" class="glass-card animate-fade-in-up stagger-{{ $index % 5 + 1 }}" style="cursor: pointer; display: flex; flex-direction: column; gap: 10px; opacity: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="min-width: 0;">
                            <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Cliente</span>
                            <h3 style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-top: 1px;">
                                {{ $ped->cliente->nome_fantasia ?? $ped->cliente->nome ?? 'Cliente Geral' }}
                            </h3>
                        </div>
                        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0;">
                            @if($ped->status->value === 'em_separacao')
                                <span style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase;">Separando</span>
                            @else
                                <span style="background: rgba(148, 163, 184, 0.1); color: #94a3b8; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase;">Pendente</span>
                            @endif
                            <span style="font-size: 0.7rem; color: #64748b;">{{ $ped->data_pedido?->format('d/m/Y') ?? now()->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div style="background: rgba(15, 23, 42, 0.3); border-radius: 10px; padding: 10px; display: flex; flex-direction: column; gap: 4px;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #f8fafc; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $ped->produto->nome }}
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2px;">
                            <span style="font-size: 0.7rem; color: #64748b; font-family: monospace;">SKU: {{ $ped->produto->sku }}</span>
                            <span style="font-size: 0.85rem; font-weight: 800; color: #f59e0b; font-family: monospace;">Qtd: {{ $ped->quantidade }}</span>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 8px; display: flex; justify-content: flex-end; align-items: center;">
                        <span style="font-size: 0.75rem; color: #f59e0b; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                            Iniciar Coleta
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </span>
                    </div>
                </div>
            @empty
                <div class="glass-card animate-fade-in-up" style="text-align: center; padding: 3rem 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 12px;">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(148, 163, 184, 0.08); display: flex; align-items: center; justify-content: center; color: #475569;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 26px; height: 26px;"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 0.95rem; font-weight: 700; color: #cbd5e1; margin-bottom: 2px;">Sem pedidos pendentes</h3>
                        <p style="font-size: 0.75rem; color: #64748b; max-width: 240px; margin: 0 auto; line-height: 1.35;">Nenhum pedido aguardando separação foi encontrado no momento.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @elseif($modo === 'separando')
        <!-- Header & Progress -->
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem 0.75rem; position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.4);">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 10px; overflow: hidden; min-width: 0;">
                    <button wire:click="voltar" style="background: none; border: none; padding: 0; color: #94a3b8; display: flex; cursor: pointer; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </button>
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0;">
                        <h1 style="font-size: 1.05rem; font-weight: 800; color: #f8fafc; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $pedido->cliente->nome_fantasia ?? $pedido->cliente->nome ?? 'Cliente Geral' }}</h1>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $pedido->produto->nome }}</p>
                    </div>
                </div>
                
                <div style="text-align: right; flex-shrink: 0;">
                    <span style="font-size: 0.85rem; font-weight: 800; color: #f59e0b; font-family: monospace;">{{ count($itensSeparados) }}</span>
                    <span style="font-size: 0.75rem; color: #64748b;"> / {{ count($rotaColeta) }}</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            @php
                $totalColetas = count($rotaColeta);
                $porcentagem = $totalColetas > 0 ? round((count($itensSeparados) / $totalColetas) * 100) : 0;
                $progressoCor = $porcentagem === 100 ? '#10b981' : '#f59e0b';
            @endphp
            <div style="width: 100%; height: 6px; background: rgba(15, 23, 42, 0.6); border-radius: 3px; overflow: hidden; position: relative;">
                <div style="width: {{ $porcentagem }}%; height: 100%; background: {{ $progressoCor }}; border-radius: 3px; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 0 10px {{ $progressoCor }}40;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                <span style="font-size: 0.65rem; color: #64748b; font-weight: 600;">Progresso da Rota</span>
                <span style="font-size: 0.65rem; color: {{ $progressoCor }}; font-weight: 700;">{{ $porcentagem }}%</span>
            </div>
        </div>

        <!-- Scanning instructions -->
        <div style="padding: 1rem 1.25rem 0.25rem;">
            <div class="glass-card" style="padding: 0.85rem 1rem; border-color: rgba(245, 158, 11, 0.15); background: rgba(245, 158, 11, 0.03); display: flex; flex-direction: column; gap: 6px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #f59e0b;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                    <span style="font-size: 0.8rem; font-weight: 700;">Instrução de Separação (FEFO)</span>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; line-height: 1.4; margin: 0;">Siga a rota de endereços abaixo. Use o botão **Scan** para bipar o lote correto do produto ou clique em **Coletar** para simulação.</p>
            </div>
        </div>

        <!-- Picking Route Checklist -->
        <div style="padding: 0.75rem 1.25rem 6.5rem; display: flex; flex-direction: column; gap: 0.85rem;">
            <p style="font-size: 0.7rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.25rem; padding-left: 2px;">Rota de Coleta</p>
            
            @if(empty($rotaColeta))
                <div class="glass-card" style="text-align: center; padding: 2rem 1rem; color: #94a3b8; font-size: 0.8rem;">
                    Nenhum lote com estoque disponível foi encontrado para este produto.
                </div>
            @endif

            @foreach($rotaColeta as $index => $item)
                @php
                    $coletado = $item['coletado'];
                @endphp
                <div class="glass-card" style="padding: 1rem; border-color: {{ $coletado ? 'rgba(16, 185, 129, 0.25)' : 'rgba(248, 250, 252, 0.06)' }}; background: {{ $coletado ? 'rgba(16, 185, 129, 0.05)' : 'rgba(30, 41, 59, 0.6)' }}; display: flex; flex-direction: column; gap: 8px; transition: all 0.2s;">
                    <!-- Top Location Badge -->
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; background: {{ $coletado ? 'rgba(16, 185, 129, 0.2)' : 'rgba(245, 158, 11, 0.15)' }}; color: {{ $coletado ? '#10b981' : '#f59e0b' }}; padding: 3px 10px; border-radius: 8px; font-weight: 800; border: 1px solid {{ $coletado ? 'rgba(16, 185, 129, 0.25)' : 'rgba(245, 158, 11, 0.2)' }}; letter-spacing: 0.5px; font-family: monospace;">
                            📍 ENDEREÇO: {{ $item['endereco_codigo'] }}
                        </span>
                        
                        @if($coletado)
                            <span style="color: #10b981; font-size: 0.7rem; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
                                COLETADO
                            </span>
                        @else
                            <span style="color: #f59e0b; font-size: 0.7rem; font-weight: 700; animation: pulseText 1.5s infinite;">
                                AGUARDANDO
                            </span>
                        @endif
                    </div>
                    
                    <!-- Details -->
                    <div style="opacity: {{ $coletado ? 0.65 : 1 }}; display: flex; justify-content: space-between; align-items: center; gap: 12px; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 8px;">
                        <div>
                            <h4 style="font-size: 0.85rem; font-weight: 700; color: #f8fafc; margin: 0 0 2px;">{{ $item['produto_nome'] }}</h4>
                            <p style="font-size: 0.7rem; color: #64748b; margin: 0 0 2px;">Lote: <strong style="font-family: monospace; color: #cbd5e1;">{{ $item['codigo_lote'] }}</strong></p>
                            @if($item['data_validade'])
                                <p style="font-size: 0.65rem; color: #e11d48; margin: 0; font-weight: 600;">Validade: {{ $item['data_validade'] }} (FEFO)</p>
                            @endif
                        </div>
                        
                        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0;">
                            <span style="font-size: 0.95rem; font-weight: 900; color: #cbd5e1; font-family: monospace; background: rgba(248, 250, 252, 0.05); padding: 4px 10px; border-radius: 8px;">
                                RETIRAR: {{ $item['quantidade'] }}
                            </span>
                            
                            @if(!$coletado)
                                <button type="button" wire:click="confirmarColeta({{ $index }})" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 8px; padding: 6px 12px; font-size: 0.7rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: all 0.2s;" onmouseover="this.style.background='#f59e0b'; this.style.color='#0f172a'" onmouseout="this.style.background='rgba(245, 158, 11, 0.1)'; this.style.color='#f59e0b'">
                                    Coletar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Bottom Bar -->
        @php
            $tudoColetado = !empty($rotaColeta) && count($itensSeparados) === count($rotaColeta);
        @endphp
        <div style="position: fixed; bottom: calc(4.75rem + env(safe-area-inset-bottom, 0px)); left: 0; right: 0; padding: 0.75rem 1.25rem; background: linear-gradient(to top, #0f172a 70%, transparent); z-index: 30; display: flex; flex-direction: column; gap: 8px;">
            <button type="button" wire:click="finalizarSeparacao" @if(!$tudoColetado) disabled @endif style="width: 100%; border: none; font-family: inherit; font-size: 0.95rem; font-weight: 700; border-radius: 14px; padding: 14px; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px;
                @if($tudoColetado)
                    background: linear-gradient(135deg, #10b981, #059669); color: #0f172a; cursor: pointer; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); animation: pulseGlowGreen 2s infinite;
                @else
                    background: #334155; color: #64748b; cursor: not-allowed; opacity: 0.75;
                @endif
            ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Finalizar Separação
            </button>
        </div>
        
        <style>
            @keyframes pulseText {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
            @keyframes pulseGlowGreen {
                0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
                50% { box-shadow: 0 0 15px 4px rgba(16, 185, 129, 0.2); }
            }
        </style>
    @elseif($modo === 'concluido')
        <!-- Completed Celebration -->
        <div style="padding: 3rem 1.5rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; gap: 1.5rem;">
            <!-- Outer Glow ring -->
            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                <div style="position: absolute; width: 110px; height: 110px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); filter: blur(10px); animation: pulseGlowRing 2s infinite;"></div>
                
                <div style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); position: relative; z-index: 10;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#0f172a" style="width: 38px; height: 38px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <h2 style="font-size: 1.4rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Separação Concluída!</h2>
                <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.4; max-width: 260px; margin: 0 auto;">Os itens do pedido foram retirados dos endereços de estoque e direcionados para conferência.</p>
            </div>
            
            <!-- Summary card -->
            @if($pedido)
                <div class="glass-card animate-fade-in-up" style="width: 100%; max-width: 320px; padding: 1.15rem; background: rgba(30, 41, 59, 0.5); border-color: rgba(248, 250, 252, 0.05); text-align: left; display: flex; flex-direction: column; gap: 8px;">
                    <div>
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Cliente</span>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1;">{{ $pedido->cliente->nome_fantasia ?? $pedido->cliente->nome ?? 'Cliente Geral' }}</div>
                    </div>
                    
                    <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 8px; margin-top: 2px;">
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Produto</span>
                        <div style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $pedido->produto->nome }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace; margin-top: 1px;">SKU: {{ $pedido->produto->sku }}</div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 8px; margin-top: 2px;">
                        <div>
                            <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Quantidade Total</span>
                            <div style="font-size: 0.95rem; font-weight: 800; color: #f59e0b; font-family: monospace;">{{ $pedido->quantidade }}</div>
                        </div>
                        <div>
                            <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Localizações</span>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">{{ count($rotaColeta) }} endereço(s)</div>
                        </div>
                    </div>
                </div>
            @endif
            
            <button type="button" wire:click="voltar" style="width: 100%; max-width: 320px; background: rgba(248, 250, 252, 0.06); color: #f8fafc; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; font-family: inherit; transition: all 0.2s;" onmouseover="this.style.background='rgba(248, 250, 252, 0.1)'" onmouseout="this.style.background='rgba(248, 250, 252, 0.06)'">
                Voltar para Pedidos
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

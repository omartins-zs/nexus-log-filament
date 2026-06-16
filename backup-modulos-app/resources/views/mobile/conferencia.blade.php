<div>
    @if($modo === 'selecao')
        <!-- Header -->
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('mobile.hub') }}" style="color: #94a3b8; text-decoration: none; display: flex;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </a>
                    <h1 style="font-size: 1.2rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Recebimento</h1>
                </div>
                <div style="background: rgba(245, 158, 11, 0.1); border-radius: 20px; padding: 4px 10px; border: 1px solid rgba(245, 158, 11, 0.2); flex-shrink: 0;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #f59e0b;">{{ count($recebimentos) }} pendente(s)</span>
                </div>
            </div>
            
            <!-- Search bar -->
            <div style="position: relative;">
                <input type="text" wire:model.live="search" placeholder="Buscar por fornecedor ou NF-e..." style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 10px 12px 10px 36px; color: #f8fafc; font-family: inherit; font-size: 0.85rem; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='rgba(245, 158, 11, 0.4)'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" style="width: 16px; height: 16px; position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 1rem 1.25rem 2rem; display: flex; flex-direction: column; gap: 0.85rem;">
            @forelse($recebimentos as $index => $rec)
                <div wire:click="selecionarRecebimento({{ $rec->id }})" class="glass-card animate-fade-in-up stagger-{{ $index % 5 + 1 }}" style="cursor: pointer; display: flex; flex-direction: column; gap: 8px; opacity: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div>
                            <h3 style="font-size: 0.95rem; font-weight: 700; color: #f8fafc; margin-bottom: 2px;">{{ $rec->fornecedor }}</h3>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">NF-e: <span style="font-family: monospace; color: #cbd5e1;">{{ $rec->codigo_nfe }}</span></p>
                        </div>
                        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0;">
                            @if($rec->status === 'em_conferencia')
                                <span style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase;">Em progresso</span>
                            @else
                                <span style="background: rgba(148, 163, 184, 0.1); color: #94a3b8; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase;">Novo</span>
                            @endif
                            <span style="font-size: 0.7rem; color: #64748b;">{{ $rec->data_recebimento->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid rgba(248, 250, 252, 0.05); margin-top: 4px; padding-top: 8px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                            {{ $rec->lotes_count }} lote(s) cadastrado(s)
                        </span>
                        
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#f59e0b" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </div>
                </div>
            @empty
                <div class="glass-card animate-fade-in-up" style="text-align: center; padding: 3rem 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 12px;">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(148, 163, 184, 0.08); display: flex; align-items: center; justify-content: center; color: #475569;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 26px; height: 26px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" /></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 0.95rem; font-weight: 700; color: #cbd5e1; margin-bottom: 2px;">Sem recebimentos pendentes</h3>
                        <p style="font-size: 0.75rem; color: #64748b; max-width: 240px; margin: 0 auto; line-height: 1.35;">Nenhum recebimento em rascunho ou conferência foi encontrado no momento.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @elseif($modo === 'conferindo')
        <!-- Header & Progress -->
        <div class="mobile-header safe-top" style="padding: 1rem 1.25rem 0.75rem; position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.4);">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 10px; overflow: hidden; min-width: 0;">
                    <button wire:click="voltar" style="background: none; border: none; padding: 0; color: #94a3b8; display: flex; cursor: pointer; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </button>
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0;">
                        <h1 style="font-size: 1.05rem; font-weight: 800; color: #f8fafc; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $recebimento->fornecedor }}</h1>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">NF-e: <span style="font-family: monospace;">{{ $recebimento->codigo_nfe }}</span></p>
                    </div>
                </div>
                
                <div style="text-align: right; flex-shrink: 0;">
                    <span style="font-size: 0.85rem; font-weight: 800; color: #f59e0b; font-family: monospace;">{{ count($itensConferidos) }}</span>
                    <span style="font-size: 0.75rem; color: #64748b;"> / {{ $totalItens }}</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            @php
                $porcentagem = $totalItens > 0 ? round((count($itensConferidos) / $totalItens) * 100) : 0;
                $progressoCor = $porcentagem === 100 ? '#10b981' : '#f59e0b';
            @endphp
            <div style="width: 100%; height: 6px; background: rgba(15, 23, 42, 0.6); border-radius: 3px; overflow: hidden; position: relative;">
                <div style="width: {{ $porcentagem }}%; height: 100%; background: {{ $progressoCor }}; border-radius: 3px; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 0 10px {{ $progressoCor }}40;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                <span style="font-size: 0.65rem; color: #64748b; font-weight: 600;">Progresso da Conferência</span>
                <span style="font-size: 0.65rem; color: {{ $progressoCor }}; font-weight: 700;">{{ $porcentagem }}%</span>
            </div>
        </div>

        <!-- Scanning instructions -->
        <div style="padding: 1rem 1.25rem 0.25rem;">
            <div class="glass-card" style="padding: 0.85rem 1rem; border-color: rgba(245, 158, 11, 0.15); background: rgba(245, 158, 11, 0.03); display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #f59e0b;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                    <span style="font-size: 0.8rem; font-weight: 700;">Instrução de Leitura</span>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; line-height: 1.4; margin: 0;">Use o botão **Scan** flutuante no rodapé para ler o código de cada lote. Também é possível clicar em **Conferir** individualmente em cada item.</p>
            </div>
        </div>

        <!-- Checklist -->
        <div style="padding: 0.75rem 1.25rem 6.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <p style="font-size: 0.7rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.25rem; padding-left: 2px;">Lista de Lotes ({{ $totalItens }})</p>
            
            @foreach($recebimento->lotes as $lote)
                @php
                    $conferido = in_array($lote->id, $itensConferidos);
                @endphp
                <div class="glass-card" style="padding: 1rem; border-color: {{ $conferido ? 'rgba(16, 185, 129, 0.25)' : 'rgba(248, 250, 252, 0.06)' }}; background: {{ $conferido ? 'rgba(16, 185, 129, 0.05)' : 'rgba(30, 41, 59, 0.6)' }}; display: flex; align-items: center; gap: 12px; transition: all 0.2s;">
                    <!-- Status Icon -->
                    <div style="flex-shrink: 0;">
                        @if($conferido)
                            <div style="width: 22px; height: 22px; border-radius: 50%; background: rgba(16, 185, 129, 0.2); display: flex; align-items: center; justify-content: center; border: 1.5px solid #10b981;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#10b981" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" /></svg>
                            </div>
                        @else
                            <div style="width: 22px; height: 22px; border-radius: 50%; border: 1.5px solid #475569; cursor: pointer;" wire:click="confirmarItem({{ $lote->id }})"></div>
                        @endif
                    </div>
                    
                    <!-- Details -->
                    <div style="flex: 1; min-width: 0; opacity: {{ $conferido ? 0.6 : 1 }};">
                        <div style="display: flex; align-items: baseline; gap: 6px; justify-content: space-between; margin-bottom: 2px;">
                            <h4 style="font-size: 0.85rem; font-weight: 700; color: #f8fafc; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin: 0;">{{ $lote->produto->nome }}</h4>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #cbd5e1; font-family: monospace; flex-shrink: 0;">Qtd: {{ $lote->quantidade_inicial }}</span>
                        </div>
                        
                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 2px;">
                            @if($lote->produto->sku)
                                <span style="font-size: 0.65rem; background: rgba(248, 250, 252, 0.05); color: #94a3b8; padding: 1px 5px; border-radius: 4px; font-family: monospace;">SKU: {{ $lote->produto->sku }}</span>
                            @endif
                            @if($lote->produto->cor)
                                <span style="font-size: 0.65rem; background: rgba(248, 250, 252, 0.05); color: #cbd5e1; padding: 1px 5px; border-radius: 4px; display: flex; align-items: center; gap: 3px;">
                                    <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: {{ $lote->produto->cor }}; border: 1px solid rgba(255,255,255,0.2);"></span>
                                    {{ $lote->produto->cor }}
                                </span>
                            @endif
                            @if($lote->produto->linha)
                                <span style="font-size: 0.65rem; background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 1px 5px; border-radius: 4px;">{{ $lote->produto->linha }}</span>
                            @endif
                        </div>
                        
                        <p style="font-size: 0.7rem; color: #64748b; margin: 0;">Código Lote: <strong style="font-family: monospace; color: #94a3b8;">{{ $lote->codigo_lote }}</strong></p>
                    </div>
                    
                    <!-- Actions -->
                    @if(!$conferido)
                        <button type="button" wire:click="confirmarItem({{ $lote->id }})" style="background: rgba(248, 250, 252, 0.05); color: #f8fafc; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 8px; padding: 6px 10px; font-size: 0.7rem; font-weight: 600; cursor: pointer; flex-shrink: 0; font-family: inherit; transition: all 0.2s;" onmouseover="this.style.background='rgba(245, 158, 11, 0.1)'; this.style.borderColor='rgba(245, 158, 11, 0.2)'; this.style.color='#f59e0b'" onmouseout="this.style.background='rgba(248, 250, 252, 0.05)'; this.style.borderColor='rgba(248, 250, 252, 0.08)'; this.style.color='#f8fafc'">
                            Conferir
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sticky Bottom Bar -->
        @php
            $tudoConferido = count($itensConferidos) === $totalItens;
        @endphp
        <div style="position: fixed; bottom: calc(4.75rem + env(safe-area-inset-bottom, 0px)); left: 0; right: 0; padding: 0.75rem 1.25rem; background: linear-gradient(to top, #0f172a 70%, transparent); z-index: 30; display: flex; flex-direction: column; gap: 8px;">
            <button type="button" wire:click="finalizarConferencia" @if(!$tudoConferido) disabled @endif style="width: 100%; border: none; font-family: inherit; font-size: 0.95rem; font-weight: 700; border-radius: 14px; padding: 14px; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px;
                @if($tudoConferido)
                    background: linear-gradient(135deg, #10b981, #059669); color: #0f172a; cursor: pointer; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); animation: pulseGlowGreen 2s infinite;
                @else
                    background: #334155; color: #64748b; cursor: not-allowed; opacity: 0.75;
                @endif
            ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Finalizar Conferência
            </button>
        </div>
        
        <style>
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
                <h2 style="font-size: 1.4rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Conferência Concluída!</h2>
                <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.4; max-width: 260px; margin: 0 auto;">O recebimento foi conferido física e quantitativamente com sucesso.</p>
            </div>
            
            <!-- Summary card -->
            @if($recebimento)
                <div class="glass-card animate-fade-in-up" style="width: 100%; max-width: 320px; padding: 1.15rem; background: rgba(30, 41, 59, 0.5); border-color: rgba(248, 250, 252, 0.05); text-align: left; display: flex; flex-direction: column; gap: 8px;">
                    <div>
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Fornecedor</span>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1;">{{ $recebimento->fornecedor }}</div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; border-top: 1px solid rgba(248, 250, 252, 0.05); padding-top: 8px; margin-top: 2px;">
                        <div>
                            <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">NF-e</span>
                            <div style="font-size: 0.8rem; font-weight: 600; color: #cbd5e1; font-family: monospace;">{{ $recebimento->codigo_nfe }}</div>
                        </div>
                        <div>
                            <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Lotes Conferidos</span>
                            <div style="font-size: 0.8rem; font-weight: 600; color: #cbd5e1;">{{ $totalItens }} de {{ $totalItens }}</div>
                        </div>
                    </div>
                </div>
            @endif
            
            <button type="button" wire:click="voltar" style="width: 100%; max-width: 320px; background: rgba(248, 250, 252, 0.06); color: #f8fafc; border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 14px; font-weight: 700; font-size: 0.95rem; cursor: pointer; font-family: inherit; transition: all 0.2s;" onmouseover="this.style.background='rgba(248, 250, 252, 0.1)'" onmouseout="this.style.background='rgba(248, 250, 252, 0.06)'">
                Voltar para Recebimentos
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

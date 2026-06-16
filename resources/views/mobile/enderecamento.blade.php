<div>
    <!-- Header -->
    <div class="mobile-header safe-top" style="padding: 1rem 1.25rem 0.75rem; position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.4);">
        <div style="display: flex; align-items: center; gap: 10px;">
            @if($modo !== 'pesquisa')
                <button wire:click="voltar" style="background: none; border: none; padding: 0; color: #94a3b8; display: flex; cursor: pointer; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </button>
            @else
                <a href="{{ route('mobile.hub') }}" style="color: #94a3b8; text-decoration: none; display: flex; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </a>
            @endif
            <div>
                <h1 style="font-size: 1.15rem; font-weight: 800; color: #f8fafc; margin: 0; letter-spacing: -0.3px;">Endereçamento</h1>
                <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                    @if($modo === 'pesquisa')
                        Consultar Armazém
                    @elseif($modo === 'endereco_detalhes')
                        📍 Endereço: {{ $selectedEndereco->corredor }}-{{ $selectedEndereco->estante }}-{{ $selectedEndereco->nivel }}
                    @elseif($modo === 'produto_detalhes')
                        📦 Produto: {{ $selectedProduto->sku }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div style="padding: 1rem 1.25rem 2rem; display: flex; flex-direction: column; gap: 0.85rem;">
        
        @if($modo === 'pesquisa')
            <!-- Tab switches -->
            <div style="display: flex; background: rgba(15, 23, 42, 0.6); padding: 4px; border-radius: 10px; gap: 4px;">
                <button wire:click="$set('subModo', 'endereco')" type="button" style="flex: 1; padding: 8px; border-radius: 8px; border: none; background: {{ $subModo === 'endereco' ? '#f59e0b' : 'transparent' }}; color: {{ $subModo === 'endereco' ? '#0f172a' : '#94a3b8' }}; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; font-family: inherit;">
                    📍 Consultar Endereço
                </button>
                <button wire:click="$set('subModo', 'produto')" type="button" style="flex: 1; padding: 8px; border-radius: 8px; border: none; background: {{ $subModo === 'produto' ? '#f59e0b' : 'transparent' }}; color: {{ $subModo === 'produto' ? '#0f172a' : '#94a3b8' }}; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; font-family: inherit;">
                    📦 Consultar Produto
                </button>
            </div>

            <!-- Instruction -->
            <div class="glass-card" style="padding: 0.85rem 1rem; border-color: rgba(16, 185, 129, 0.15); background: rgba(16, 185, 129, 0.02); display: flex; flex-direction: column; gap: 6px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #10b981;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" /></svg>
                    <span style="font-size: 0.8rem; font-weight: 700;">Localização Rápida</span>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; line-height: 1.4; margin: 0;">Escaneie o código de barras de um endereço ou de um produto para visualização instantânea.</p>
            </div>

            <!-- Search input -->
            <div style="position: relative; margin-top: 4px;">
                <input type="text" wire:model.live="search" placeholder="{{ $subModo === 'endereco' ? 'Buscar endereço (Ex: A-01-1)...' : 'Buscar produto (SKU, nome, ean)...' }}" style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.08); border-radius: 12px; padding: 10px 12px 10px 36px; color: #f8fafc; font-family: inherit; font-size: 0.85rem; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(248, 250, 252, 0.08)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" style="width: 16px; height: 16px; position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>

            <!-- Search results -->
            @if(strlen($search) >= 2)
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 4px;">
                    @if($subModo === 'endereco')
                        @forelse($resultados as $end)
                            <div wire:click="selecionarEndereco({{ $end->id }})" class="glass-card animate-fade-in-up" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px;">
                                <div>
                                    <span style="font-size: 0.9rem; font-weight: 700; color: #cbd5e1; font-family: monospace;">📍 {{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</span>
                                    <span style="display: block; font-size: 0.65rem; color: #64748b; margin-top: 2px;">Cód: {{ $end->codigo_barras }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#f59e0b" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </div>
                        @empty
                            <div class="glass-card text-center" style="padding: 1.5rem; color: #64748b; font-size: 0.8rem; text-align: center;">
                                Nenhum endereço encontrado.
                            </div>
                        @endforelse
                    @else
                        @forelse($resultados as $prod)
                            <div wire:click="selecionarProduto({{ $prod->id }})" class="glass-card animate-fade-in-up" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px;">
                                <div style="max-width: 85%;">
                                    <span style="font-size: 0.85rem; font-weight: 700; color: #cbd5e1; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $prod->nome }}</span>
                                    <span style="font-size: 0.7rem; color: #94a3b8; font-family: monospace;">SKU: {{ $prod->sku }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#f59e0b" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </div>
                        @empty
                            <div class="glass-card text-center" style="padding: 1.5rem; color: #64748b; font-size: 0.8rem; text-align: center;">
                                Nenhum produto encontrado.
                            </div>
                        @endforelse
                    @endif
                </div>
            @else
                <!-- Recentes / Ativos -->
                <div style="margin-top: 8px;">
                    <h3 style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.5px;">Recentes consultados</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @if($subModo === 'endereco')
                            @forelse($recentes as $end)
                                <div wire:click="selecionarEndereco({{ $end->id }})" class="glass-card" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px;">
                                    <div>
                                        <span style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; font-family: monospace;">📍 {{ $end->corredor }}-{{ $end->estante }}-{{ $end->nivel }}</span>
                                        <span style="display: block; font-size: 0.65rem; color: #64748b;">Lotes armazenados: {{ $end->lotes_count }}</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#475569" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                </div>
                            @empty
                                <div style="font-size: 0.75rem; color: #64748b; text-align: center; padding: 1rem;">Nenhum endereço consultado recentemente.</div>
                            @endforelse
                        @else
                            @forelse($recentes as $prod)
                                <div wire:click="selecionarProduto({{ $prod->id }})" class="glass-card" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px;">
                                    <div style="max-width: 85%;">
                                        <span style="font-size: 0.8rem; font-weight: 600; color: #cbd5e1; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $prod->nome }}</span>
                                        <span style="font-size: 0.65rem; color: #64748b; font-family: monospace;">SKU: {{ $prod->sku }}</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#475569" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                </div>
                            @empty
                                <div style="font-size: 0.75rem; color: #64748b; text-align: center; padding: 1rem;">Nenhum produto consultado recentemente.</div>
                            @endforelse
                        @endif
                    </div>
                </div>
            @endif

        @elseif($modo === 'endereco_detalhes')
            <!-- Address details -->
            <div class="glass-card" style="padding: 1.15rem; display: flex; flex-direction: column; gap: 8px;">
                <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Endereço de Armazenagem</span>
                <h2 style="font-size: 1.3rem; font-weight: 800; color: #f8fafc; margin: 0; font-family: monospace;">📍 {{ $selectedEndereco->corredor }}-{{ $selectedEndereco->estante }}-{{ $selectedEndereco->nivel }}</h2>
                <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 8px; margin-top: 2px;">
                    Código de barras: <strong>{{ $selectedEndereco->codigo_barras }}</strong>
                </div>
                
                <!-- Quick actions -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 10px;">
                    <a href="{{ route('mobile.inventario', ['enderecoId' => $selectedEndereco->id]) }}" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.15); border-radius: 10px; padding: 10px; text-align: center; text-decoration: none; color: #3b82f6; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                        Inventariar
                    </a>
                    <a href="{{ route('mobile.transferencia', ['enderecoId' => $selectedEndereco->id]) }}" style="background: rgba(168, 85, 247, 0.1); border: 1px solid rgba(168, 85, 247, 0.15); border-radius: 10px; padding: 10px; text-align: center; text-decoration: none; color: #a855f7; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                        Transferir
                    </a>
                </div>
            </div>

            <!-- List of stored lots -->
            <div style="margin-top: 6px;">
                <h3 style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 8px;">Itens neste endereço</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @forelse($lotes as $lote)
                        <div class="glass-card animate-fade-in-up" style="padding: 12px; display: flex; flex-direction: column; gap: 6px; active: none;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <h4 wire:click="selecionarProduto({{ $lote->produto->id }})" style="font-size: 0.85rem; font-weight: 700; color: #f8fafc; margin: 0; cursor: pointer; text-decoration: underline; text-underline-offset: 3px; decoration-color: rgba(245, 158, 11, 0.5);">
                                    {{ $lote->produto->nome }}
                                </h4>
                                <span style="font-size: 0.9rem; font-weight: 800; color: #f59e0b; font-family: monospace; flex-shrink: 0;">Saldo: {{ $lote->quantidade_atual }}</span>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; font-size: 0.7rem; color: #94a3b8; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 6px;">
                                <div>SKU: <strong style="color: #cbd5e1;">{{ $lote->produto->sku }}</strong></div>
                                <div>Lote: <strong style="color: #cbd5e1; font-family: monospace;">{{ $lote->codigo_lote }}</strong></div>
                                <div>Fab: <span style="font-family: monospace;">{{ $lote->data_fabricacao?->format('d/m/Y') ?? 'N/A' }}</span></div>
                                <div>Val: <span style="font-family: monospace; color: {{ $lote->data_validade && $lote->data_validade->isPast() ? '#ef4444' : '#10b981' }}">{{ $lote->data_validade?->format('d/m/Y') ?? 'N/A' }}</span></div>
                            </div>

                            <div style="border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 6px; display: flex; justify-content: flex-end;">
                                <a href="{{ route('mobile.transferencia', ['enderecoId' => $selectedEndereco->id, 'loteId' => $lote->id]) }}" style="font-size: 0.7rem; font-weight: 700; color: #a855f7; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                    Transferir lote
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 12px; height: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card text-center" style="padding: 2.5rem 1.5rem; text-align: center; color: #64748b; font-size: 0.8rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#475569" style="width: 36px; height: 36px; margin: 0 auto 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                            Endereço vazio. Sem saldo em estoque.
                        </div>
                    @endforelse
                </div>
            </div>

        @elseif($modo === 'produto_detalhes')
            <!-- Product details -->
            <div class="glass-card" style="padding: 1.15rem; display: flex; flex-direction: column; gap: 8px;">
                <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Informações do Produto</span>
                <h2 style="font-size: 1.1rem; font-weight: 800; color: #f8fafc; margin: 0;">{{ $selectedProduto->nome }}</h2>
                
                <div style="border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 8px; margin-top: 2px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; font-size: 0.75rem; color: #94a3b8;">
                    <div>SKU: <strong style="color: #cbd5e1;">{{ $selectedProduto->sku }}</strong></div>
                    <div>EAN: <strong style="color: #cbd5e1;">{{ $selectedProduto->codigo_barras ?? 'N/A' }}</strong></div>
                    <div>Embalagem: <span style="color: #cbd5e1;">{{ $selectedProduto->embalagem ? $selectedProduto->embalagem->nome : 'Unidade' }}</span></div>
                    <div>Peso: <span style="color: #cbd5e1;">{{ $selectedProduto->peso ?? 'N/A' }} kg</span></div>
                </div>
            </div>

            <!-- List of stored locations -->
            <div style="margin-top: 6px;">
                <h3 style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 8px;">Localizações no Estoque</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @forelse($lotes as $lote)
                        @if($lote->endereco)
                            <div class="glass-card animate-fade-in-up" style="padding: 12px; display: flex; flex-direction: column; gap: 6px; active: none;">
                                <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                    <h4 wire:click="selecionarEndereco({{ $lote->endereco->id }})" style="font-size: 0.9rem; font-weight: 700; color: #f59e0b; margin: 0; font-family: monospace; cursor: pointer; text-decoration: underline; text-underline-offset: 3px; decoration-color: rgba(245, 158, 11, 0.5);">
                                        📍 {{ $lote->endereco->corredor }}-{{ $lote->endereco->estante }}-{{ $lote->endereco->nivel }}
                                    </h4>
                                    <span style="font-size: 0.9rem; font-weight: 800; color: #cbd5e1; font-family: monospace; flex-shrink: 0;">Qtd: {{ $lote->quantidade_atual }}</span>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; font-size: 0.7rem; color: #94a3b8; border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 6px;">
                                    <div>Lote: <strong style="color: #cbd5e1; font-family: monospace;">{{ $lote->codigo_lote }}</strong></div>
                                    <div>Validade: <span style="font-family: monospace; color: {{ $lote->data_validade && $lote->data_validade->isPast() ? '#ef4444' : '#10b981' }}">{{ $lote->data_validade?->format('d/m/Y') ?? 'N/A' }}</span></div>
                                </div>

                                <div style="border-top: 1px solid rgba(248, 250, 252, 0.04); padding-top: 6px; display: flex; justify-content: space-between; align-items: center; font-size: 0.7rem;">
                                    <a href="{{ route('mobile.inventario', ['enderecoId' => $lote->endereco->id]) }}" style="color: #3b82f6; text-decoration: none; font-weight: 700;">
                                        Contar inventário
                                    </a>
                                    <a href="{{ route('mobile.transferencia', ['enderecoId' => $lote->endereco->id, 'loteId' => $lote->id]) }}" style="color: #a855f7; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 2px;">
                                        Mover lote
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 12px; height: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="glass-card text-center" style="padding: 2.5rem 1.5rem; text-align: center; color: #64748b; font-size: 0.8rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#475569" style="width: 36px; height: 36px; margin: 0 auto 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                            Este produto não possui saldo em nenhuma localização do estoque.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

    </div>
</div>

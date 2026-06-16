<div>
    {{-- Header --}}
    <div class="mobile-header safe-top" style="padding: 1rem 1.25rem 0.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 2px;">👋 Olá,</p>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #f8fafc; letter-spacing: -0.3px;">{{ auth()->user()->name }}</h1>
            </div>
            <div style="text-align: right;">
                <p style="font-size: 0.7rem; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">{{ now()->locale('pt_BR')->translatedFormat('l') }}</p>
                <p style="font-size: 0.85rem; color: #94a3b8; font-weight: 500;">{{ now()->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Install PWA Banner --}}
    <div id="installBanner" class="install-banner" onclick="installApp()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        <span style="flex: 1;">Instalar App no dispositivo</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
    </div>

    {{-- Funcionalidades Grid --}}
    <div style="padding: 1rem 1rem 1.5rem;">
        <p style="font-size: 0.7rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem; padding-left: 4px;">Operações</p>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
            @foreach($funcionalidades as $index => $func)
                <a href="{{ route($func['rota']) }}" class="glass-card animate-fade-in-up stagger-{{ $index + 1 }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; justify-content: space-between; min-height: 140px; opacity: 0; position: relative; overflow: hidden;">
                    {{-- Decorative gradient blob --}}
                    <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: {{ $func['bg'] }}; border-radius: 50%; filter: blur(20px);"></div>

                    {{-- Icon --}}
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: {{ $func['bg'] }}; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem; position: relative; z-index: 1;">
                        @switch($func['icone'])
                            @case('clipboard-check')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $func['cor'] }}" style="width: 26px; height: 26px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                </svg>
                            @break
                            @case('archive')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $func['cor'] }}" style="width: 26px; height: 26px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                </svg>
                            @break
                            @case('cube')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $func['cor'] }}" style="width: 26px; height: 26px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                </svg>
                            @break
                            @case('arrows-right-left')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $func['cor'] }}" style="width: 26px; height: 26px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                            @break
                            @case('map-pin')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $func['cor'] }}" style="width: 26px; height: 26px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                            @break
                        @endswitch
                    </div>

                    {{-- Text --}}
                    <div style="position: relative; z-index: 1;">
                        <h3 style="font-size: 0.95rem; font-weight: 700; color: #f8fafc; margin-bottom: 3px;">{{ $func['nome'] }}</h3>
                        <p style="font-size: 0.7rem; color: #64748b; line-height: 1.3;">{{ $func['descricao'] }}</p>
                    </div>

                    {{-- Arrow indicator --}}
                    <div style="position: absolute; bottom: 12px; right: 12px; opacity: 0.3;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $func['cor'] }}" style="width: 14px; height: 14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Quick Stats --}}
    <div style="padding: 0 1rem 1.5rem;">
        <div class="glass-card animate-fade-in-up" style="display: flex; align-items: center; gap: 12px; opacity: 0; animation-delay: 0.35s;">
            <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f59e0b" style="width: 22px; height: 22px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
            </div>
            <div>
                <p style="font-size: 0.8rem; color: #94a3b8; line-height: 1.4;">Use o botão <strong style="color: #f59e0b;">Scan</strong> na barra inferior para ler códigos de barras rapidamente em qualquer módulo.</p>
            </div>
        </div>
    </div>

    {{-- Footer info --}}
    <div style="padding: 0 1rem 2rem; text-align: center;">
        <p style="font-size: 0.65rem; color: #1e293b;">Nexus WMS v1.0 • {{ now()->format('Y') }}</p>
    </div>
</div>

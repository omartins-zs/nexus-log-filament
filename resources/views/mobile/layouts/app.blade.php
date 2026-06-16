<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <title>{{ $title ?? 'Nexus WMS' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d',
                            400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309',
                            800: '#92400e', 900: '#78350f'
                        }
                    }
                }
            },
            corePlugins: { preflight: false }
        }
    </script>

    @livewireStyles

    <style>
        /* Reset and base */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }

        /* Safe area for notch */
        .safe-top { padding-top: env(safe-area-inset-top, 0px); }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom, 0px); }

        /* Page content area */
        .page-content {
            padding-bottom: 5rem;
            min-height: 100vh;
            min-height: 100dvh;
        }

        /* Bottom nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(248, 250, 252, 0.08);
            z-index: 50;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 8px 4px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .bottom-nav a.active {
            color: #f59e0b;
        }

        .bottom-nav a.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #f59e0b;
            border-radius: 0 0 4px 4px;
        }

        .bottom-nav svg {
            width: 22px;
            height: 22px;
        }

        /* Glassmorphism card */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(248, 250, 252, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
            transition: all 0.3s ease;
        }

        .glass-card:active {
            transform: scale(0.97);
            background: rgba(30, 41, 59, 0.9);
        }

        /* Header */
        .mobile-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(248, 250, 252, 0.06);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.3); }
            50% { box-shadow: 0 0 20px 5px rgba(245, 158, 11, 0.15); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease forwards;
        }

        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.1s; }
        .stagger-3 { animation-delay: 0.15s; }
        .stagger-4 { animation-delay: 0.2s; }
        .stagger-5 { animation-delay: 0.25s; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 0; height: 0; }

        /* Install banner */
        .install-banner {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #0f172a;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: none;
            align-items: center;
            gap: 8px;
            margin: 0 1rem 1rem;
        }

        .install-banner.show { display: flex; }
    </style>
</head>
<body class="dark">
    <div class="safe-top"></div>

    <div class="page-content">
        {{ $slot }}
    </div>

    {{-- Bottom Navigation --}}
    @auth
    <nav class="bottom-nav safe-bottom">
        <div style="display: flex; justify-content: space-around; align-items: center; padding: 4px 0;">
            <a href="{{ route('mobile.hub') }}" class="{{ request()->routeIs('mobile.hub') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Início</span>
            </a>
            <a href="{{ route('mobile.conferencia') }}" class="{{ request()->routeIs('mobile.conferencia') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Conferir</span>
            </a>
            <a href="javascript:void(0)" onclick="openScanner()" style="margin-top: -20px;">
                <div style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; padding: 14px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#0f172a" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75H16.5v-.75Z" />
                    </svg>
                </div>
                <span style="margin-top: 4px;">Scan</span>
            </a>
            <a href="{{ route('mobile.separacao') }}" class="{{ request()->routeIs('mobile.separacao') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
                <span>Separar</span>
            </a>
            <a href="#" onclick="document.getElementById('moreMenu').classList.toggle('hidden'); return false;" class="{{ request()->routeIs('mobile.inventario', 'mobile.transferencia', 'mobile.enderecamento') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <span>Mais</span>
            </a>
        </div>
    </nav>

    {{-- More Menu Overlay --}}
    <div id="moreMenu" class="hidden" style="position: fixed; inset: 0; z-index: 60;">
        <div onclick="document.getElementById('moreMenu').classList.add('hidden')" style="position: absolute; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);"></div>
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: #1e293b; border-radius: 20px 20px 0 0; padding: 1.5rem; padding-bottom: calc(1.5rem + env(safe-area-inset-bottom, 0px));">
            <div style="width: 40px; height: 4px; background: #475569; border-radius: 2px; margin: 0 auto 1.25rem;"></div>
            <a href="{{ route('mobile.inventario') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 8px; color: #e2e8f0; text-decoration: none; border-radius: 12px;" onmouseover="this.style.background='rgba(248,250,252,0.05)'" onmouseout="this.style.background='transparent'">
                <div style="background: rgba(59, 130, 246, 0.15); border-radius: 10px; padding: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b82f6" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                </div>
                <div><div style="font-weight: 600;">Inventário</div><div style="font-size: 0.75rem; color: #94a3b8;">Contagem de estoque</div></div>
            </a>
            <a href="{{ route('mobile.transferencia') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 8px; color: #e2e8f0; text-decoration: none; border-radius: 12px;" onmouseover="this.style.background='rgba(248,250,252,0.05)'" onmouseout="this.style.background='transparent'">
                <div style="background: rgba(168, 85, 247, 0.15); border-radius: 10px; padding: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#a855f7" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                </div>
                <div><div style="font-weight: 600;">Transferência</div><div style="font-size: 0.75rem; color: #94a3b8;">Mover entre endereços</div></div>
            </a>
            <a href="{{ route('mobile.enderecamento') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 8px; color: #e2e8f0; text-decoration: none; border-radius: 12px;" onmouseover="this.style.background='rgba(248,250,252,0.05)'" onmouseout="this.style.background='transparent'">
                <div style="background: rgba(16, 185, 129, 0.15); border-radius: 10px; padding: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#10b981" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                </div>
                <div><div style="font-weight: 600;">Endereçamento</div><div style="font-size: 0.75rem; color: #94a3b8;">Gerenciar localizações</div></div>
            </a>
            <div style="border-top: 1px solid rgba(248,250,252,0.08); margin-top: 12px; padding-top: 12px;">
                <form method="POST" action="{{ route('mobile.logout') }}">
                    @csrf
                    <button type="submit" style="display: flex; align-items: center; gap: 12px; padding: 14px 8px; color: #ef4444; background: none; border: none; width: 100%; cursor: pointer; border-radius: 12px; font-family: inherit; font-size: 1rem;">
                        <div style="background: rgba(239, 68, 68, 0.15); border-radius: 10px; padding: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                        </div>
                        <div style="font-weight: 600;">Sair</div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    @livewireScripts

    <script>
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('SW registered:', reg.scope))
                .catch(err => console.log('SW failed:', err));
        }

        // Install PWA prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const banner = document.getElementById('installBanner');
            if (banner) banner.classList.add('show');
        });

        function installApp() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((result) => {
                    deferredPrompt = null;
                    const banner = document.getElementById('installBanner');
                    if (banner) banner.classList.remove('show');
                });
            }
        }

        // Scanner placeholder function (will be implemented with html5-qrcode)
        function openScanner() {
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('open-scanner');
            }
        }

        // Haptic feedback on button press
        document.addEventListener('click', function(e) {
            if (e.target.closest('.glass-card, .bottom-nav a, button')) {
                if (navigator.vibrate) navigator.vibrate(10);
            }
        });
    </script>
</body>
</html>

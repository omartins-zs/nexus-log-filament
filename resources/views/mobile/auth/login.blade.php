<div style="min-height: 100vh; min-height: 100dvh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 1.5rem; position: relative; overflow: hidden;">
    {{-- Animated background gradient --}}
    <div style="position: absolute; inset: 0; z-index: 0; overflow: hidden;">
        <div style="position: absolute; top: -30%; left: -20%; width: 70vw; height: 70vw; background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%); border-radius: 50%; animation: pulse-glow 6s ease-in-out infinite;"></div>
        <div style="position: absolute; bottom: -20%; right: -20%; width: 60vw; height: 60vw; background: radial-gradient(circle, rgba(217, 119, 6, 0.08) 0%, transparent 70%); border-radius: 50%; animation: pulse-glow 8s ease-in-out infinite 2s;"></div>
    </div>

    {{-- Logo Area --}}
    <div style="position: relative; z-index: 1; text-align: center; margin-bottom: 2.5rem;" class="animate-fade-in-up">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 20px; background: linear-gradient(135deg, #f59e0b, #d97706); margin-bottom: 1rem; box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#0f172a" style="width: 36px; height: 36px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
        </div>
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; color: #f8fafc; margin-bottom: 0.25rem;">
            NEXUS <span style="background: linear-gradient(135deg, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">WMS</span>
        </h1>
        <p style="font-size: 0.8rem; color: #64748b; font-weight: 400; letter-spacing: 0.5px;">
            Sistema de Gestão de Armazém
        </p>
    </div>

    {{-- Login Card --}}
    <div style="position: relative; z-index: 1; width: 100%; max-width: 380px;" class="animate-fade-in-up stagger-2">
        <div class="glass-card" style="padding: 2rem 1.5rem;">
            <h2 style="font-size: 1.15rem; font-weight: 700; color: #f8fafc; margin-bottom: 0.25rem;">Entrar</h2>
            <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 1.75rem;">Acesse com suas credenciais</p>

            @if($error)
                <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" style="width: 18px; height: 18px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span style="font-size: 0.8rem; color: #fca5a5;">{{ $error }}</span>
                </div>
            @endif

            <form wire:submit="authenticate">
                {{-- Email --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">E-mail</label>
                    <div style="position: relative;">
                        <div style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="seu@email.com"
                            autocomplete="email"
                            inputmode="email"
                            style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.1); border-radius: 12px; padding: 14px 14px 14px 44px; color: #f8fafc; font-size: 0.95rem; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s ease;"
                            onfocus="this.style.borderColor='rgba(245, 158, 11, 0.5)'"
                            onblur="this.style.borderColor='rgba(248, 250, 252, 0.1)'"
                        >
                    </div>
                    @error('email')
                        <span style="font-size: 0.7rem; color: #fca5a5; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Senha</label>
                    <div style="position: relative;">
                        <div style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <input
                            wire:model="password"
                            type="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(248, 250, 252, 0.1); border-radius: 12px; padding: 14px 14px 14px 44px; color: #f8fafc; font-size: 0.95rem; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s ease;"
                            onfocus="this.style.borderColor='rgba(245, 158, 11, 0.5)'"
                            onblur="this.style.borderColor='rgba(248, 250, 252, 0.1)'"
                        >
                    </div>
                    @error('password')
                        <span style="font-size: 0.7rem; color: #fca5a5; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 1.75rem;">
                    <input
                        wire:model="remember"
                        type="checkbox"
                        id="remember"
                        style="width: 16px; height: 16px; border-radius: 4px; accent-color: #f59e0b; cursor: pointer;"
                    >
                    <label for="remember" style="font-size: 0.8rem; color: #94a3b8; cursor: pointer;">Lembrar de mim</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    style="width: 100%; padding: 14px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #0f172a; font-weight: 700; font-size: 0.95rem; border: none; border-radius: 12px; cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.2s ease; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); position: relative; overflow: hidden;"
                    onmousedown="this.style.transform='scale(0.97)'"
                    onmouseup="this.style.transform='scale(1)'"
                    ontouchstart="this.style.transform='scale(0.97)'"
                    ontouchend="this.style.transform='scale(1)'"
                >
                    <span wire:loading.remove wire:target="authenticate">Entrar</span>
                    <span wire:loading wire:target="authenticate" style="display: inline-flex; align-items: center; gap: 8px;">
                        <svg style="width: 18px; height: 18px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Entrando...
                    </span>
                </button>
            </form>
        </div>
    </div>

    {{-- Install PWA Banner --}}
    <div id="installBanner" class="install-banner animate-fade-in-up stagger-3" onclick="installApp()" style="position: relative; z-index: 1; margin-top: 1.5rem; width: 100%; max-width: 380px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        <span>Instalar App no dispositivo</span>
    </div>

    {{-- Footer --}}
    <div style="position: relative; z-index: 1; margin-top: 2rem; text-align: center;" class="animate-fade-in-up stagger-4">
        <p style="font-size: 0.7rem; color: #334155;">Nexus WMS • Fábrica de Tintas</p>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</div>

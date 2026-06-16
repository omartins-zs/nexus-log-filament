<?php

namespace App\Livewire\Mobile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $error = '';

    public function authenticate()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('mobile')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('mobile.hub'));
        }

        $this->error = 'Credenciais inválidas.';
    }

    public function render()
    {
        return view('mobile.auth.login')
            ->layout('mobile.layouts.app', ['title' => 'Login — Nexus WMS']);
    }
}

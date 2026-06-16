<?php

namespace App\Livewire\Mobile;

use Livewire\Component;

class Hub extends Component
{
    public array $funcionalidades = [];

    public function mount()
    {
        $user = auth()->user();
        $permissoes = $user->permissoes_mobile ?? [];

        $todas = [
            [
                'key' => 'conferencia',
                'nome' => 'Conferência',
                'descricao' => 'Conferir recebimentos e pedidos',
                'icone' => 'clipboard-check',
                'cor' => '#22c55e',
                'bg' => 'rgba(34, 197, 94, 0.12)',
                'rota' => 'mobile.conferencia',
            ],
            [
                'key' => 'inventario',
                'nome' => 'Inventário',
                'descricao' => 'Contagem e ajuste de estoque',
                'icone' => 'archive',
                'cor' => '#3b82f6',
                'bg' => 'rgba(59, 130, 246, 0.12)',
                'rota' => 'mobile.inventario',
            ],
            [
                'key' => 'separacao',
                'nome' => 'Separação',
                'descricao' => 'Separar itens dos pedidos',
                'icone' => 'cube',
                'cor' => '#f59e0b',
                'bg' => 'rgba(245, 158, 11, 0.12)',
                'rota' => 'mobile.separacao',
            ],
            [
                'key' => 'transferencia',
                'nome' => 'Transferência',
                'descricao' => 'Mover entre endereços',
                'icone' => 'arrows-right-left',
                'cor' => '#a855f7',
                'bg' => 'rgba(168, 85, 247, 0.12)',
                'rota' => 'mobile.transferencia',
            ],
            [
                'key' => 'enderecamento',
                'nome' => 'Endereçamento',
                'descricao' => 'Gerenciar localizações',
                'icone' => 'map-pin',
                'cor' => '#10b981',
                'bg' => 'rgba(16, 185, 129, 0.12)',
                'rota' => 'mobile.enderecamento',
            ],
        ];

        // Filter by user permissions (if empty array, show all = admin)
        $this->funcionalidades = empty($permissoes)
            ? $todas
            : array_values(array_filter($todas, fn($f) => in_array($f['key'], $permissoes)));
    }

    public function render()
    {
        return view('mobile.hub')
            ->layout('mobile.layouts.app', ['title' => 'Nexus WMS']);
    }
}

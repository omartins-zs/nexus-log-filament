<?php

namespace App\Livewire\Mobile;

use Livewire\Component;

class Transferencia extends Component
{
    public function render()
    {
        return view('mobile.transferencia')
            ->layout('mobile.layouts.app', ['title' => 'Transferência — Nexus WMS']);
    }
}

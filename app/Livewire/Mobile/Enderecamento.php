<?php

namespace App\Livewire\Mobile;

use Livewire\Component;

class Enderecamento extends Component
{
    public function render()
    {
        return view('mobile.enderecamento')
            ->layout('mobile.layouts.app', ['title' => 'Endereçamento — Nexus WMS']);
    }
}

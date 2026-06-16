<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tv', \App\Livewire\PublicTv::class);

require __DIR__.'/mobile.php';

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tv', \App\Livewire\PublicTv::class);

Route::get('/login', function () {
    return redirect()->route('mobile.login');
})->name('login');
require __DIR__.'/mobile.php';

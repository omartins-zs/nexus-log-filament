<?php

use Illuminate\Support\Facades\Route;

// Mobile App Routes
Route::prefix('app')->group(function () {
    // Auth routes (no middleware)
    Route::get('/login', \App\Livewire\Mobile\Login::class)->name('mobile.login');
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('mobile.login');
    })->name('mobile.logout');

    // Protected routes
    Route::middleware('auth')->group(function () {
        Route::get('/', \App\Livewire\Mobile\Hub::class)->name('mobile.hub');
        Route::get('/conferencia', \App\Livewire\Mobile\Conferencia::class)->name('mobile.conferencia');
        Route::get('/inventario', \App\Livewire\Mobile\Inventario::class)->name('mobile.inventario');
        Route::get('/separacao', \App\Livewire\Mobile\Separacao::class)->name('mobile.separacao');
        Route::get('/transferencia', \App\Livewire\Mobile\Transferencia::class)->name('mobile.transferencia');
        Route::get('/enderecamento', \App\Livewire\Mobile\Enderecamento::class)->name('mobile.enderecamento');
    });
});

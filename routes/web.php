<?php

use App\Livewire\TaskList;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', TaskList::class)->name('dashboard');
    Route::get('tasks', TaskList::class)->name('tasks');
});

require __DIR__.'/settings.php';

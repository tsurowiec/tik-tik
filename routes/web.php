<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('tasks');
    }

    return view('welcome');
})->name('home');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/tasks', 'pages::tasks.list')->name('tasks');
    Route::livewire('/tasks/create', 'pages::tasks.create')->name('tasks.create');
    Route::livewire('/tasks/{task}', 'pages::tasks.show')->name('tasks.show');
    Route::livewire('/tasks/{task}/edit', 'pages::tasks.edit')->name('tasks.edit');
    Route::livewire('/countdowns/{task}', 'pages::countdowns.show')->name('countdowns.show');
});

require __DIR__.'/settings.php';

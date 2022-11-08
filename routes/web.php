<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify'=>true]);

Route::middleware('auth')->middleware('verified')->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/contas', function() {
        return view("app\contas");
    })->name('contas');
});

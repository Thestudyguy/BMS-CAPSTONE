<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
Auth::routes();
Route::get('/', function () {return view('auth.login');});
Route::middleware('authenticated')->group(function(){
    Route::get('/dashboard', function(){return view('pages.dashboard');})->name('dashboard');
    Route::get('/clients', function(){return view('pages.clients');})->name('clients');
});
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Auth::routes();
Route::get('/', function () {return view('auth.login');});
Route::middleware('authenticated')->group(function(){
    Route::get('/dashboard', function(){return view('pages.dashboard');})->name('dashboard');
    Route::get('/clients', function(){return view('pages.clients');})->name('clients');
    Route::get('/external-services', [Controller::class, 'services'])->name('external-services');
    Route::get('/admin-hub', [Controller::class, 'adminHub'])->name('admin-hub');

});
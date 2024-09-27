<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Auth::routes();
Route::get('/', function () {return view('auth.login');});
Route::middleware('authenticated')->group(function(){
    Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [ClientController::class, 'returnClientData'])->name('clients');
    Route::get('/external-services', [ServicesController::class, 'returnServices'])->name('external-services');
    Route::get('/admin-hub', [Controller::class, 'adminHub'])->name('admin-hub');
    Route::post('sub-services-{id}', [ServicesController::class, 'returnSubServicesById']);
    Route::post('new-service', [ServicesController::class, 'NewService']);
    Route::post('remove-service-{id}', [ServicesController::class, 'removeService']);
    Route::post('update-service', [ServicesController::class, 'UpdateService']);
    Route::post('new-client-record', [ClientController::class, 'CreateNewClient']);
    Route::get('/new-client-form', [Controller::class, 'newClient'])->name('new-client-form');
    Route::get('/add-services', [Controller::class, 'addClientServices'])->name('add-services');
    Route::post('/fetch-sub-services-{id}', [ServicesController::class, 'returnSubServices']);
    Route::post('client-services-{id}', [ClientController::class, 'ClientServices']);
    Route::get('client-profile', [ClientController::class, 'viewClientProfile'])->name('client-profile');
    Route::get('client-journal', [ClientController::class, 'ClientJournal'])->name('client-journal');
    Route::get('client-journal-form', [ClientController::class, 'ClientJournalForm'])->name('client-journal-form');
    Route::get('chart-of-accounts', [Controller::class, 'ChartOfAccounts'])->name('chart-of-accounts');
});
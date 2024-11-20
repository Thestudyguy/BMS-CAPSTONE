<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\PDFController;
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
    Route::get('client-billing', [ClientController::class, 'ClientBillingLists'])->name('client-billing');
    Route::get('generate-client-billing', [ClientController::class, 'ClientBilling'])->name('generate-client-billing');
    Route::get('client-journal-form', [ClientController::class, 'ClientJournalForm'])->name('client-journal-form');
    Route::get('chart-of-accounts', [Controller::class, 'ChartOfAccounts'])->name('chart-of-accounts');
    Route::post('new-account-type', [Controller::class, 'NewAccountType']);
    Route::post('new-account', [Controller::class, 'NewAccount']);
    Route::get('users', [Controller::class, 'Users'])->name('users');
    Route::post('new-user', [Controller::class, 'NewUser']);
    Route::post('remove-user-{id}', [Controller::class, 'RemoveUser']);
    Route::post('update-user', [Controller::class, 'UpdateUser']);
    Route::get('billings', function(){return view('pages.billings');})->name('billings');
    Route::get('mail-client-service', [MailerController::class, 'MailClientServices']);
    Route::get('settings', [Controller::class, 'Settings'])->name('settings');
    Route::post('get-account-types-{id}', [Controller::class, 'GetAccountTypes']);
    Route::post('new-account-description', [Controller::class, 'NewAccountDescription']);
    Route::post('new-sub-service', [ServicesController::class, 'NewSubService']);
    Route::post('retrieve-sub-service-data-{id}', [ServicesController::class, 'RetrieveSubService']);
    Route::post('edit-sub-service', [ServicesController::class, 'EditSubService']);
    Route::post('mail-client-bs-{id}', [MailerController::class, 'MailClientBillingStatement']);
    Route::post('mail-client-billing', [MailerController::class, 'NewClientBilling']);
    Route::post('get-accounts-{id}', [Controller::class, 'ReturnAccounts']);
    Route::post('edit-coa', [Controller::class, 'EditCOA']);
    Route::post('new-client-journal-entry', [ClientController::class, 'NewJournalEntry']);
    // if (Auth::check() && Auth::user()->Role === 'Accountant') {
    //     Route::post('view-client-journal-{id}', [PDFController::class, 'ViewClientJournal']);
    // }
    Route::post('view-client-journal-{id}', [PDFController::class, 'ViewClientJournal']);
    Route::get('view-client-billing', [ClientController::class, 'ViewClientBilling'])->name('view-client-billing');
    Route::post('journal-pin-entry', [ClientController::class, 'BookkeeperJournalView']);
    Route::post('request-journal-pin_{id}', [MailerController::class, 'SendJournalPINRequest']);
    Route::post('client-billing-data_{id}', [PDFController::class, 'ClientBillingData']);
    Route::post('update-client-service', [ClientController::class, 'UpdateClientService']);
    Route::get('/journals', [Controller::class, 'AccountantInterface'])->name('journals');
    Route::post('/update-journal-status', [Controller::class, 'UpdateJournalStatus']);
    Route::post('view-client-journal-{id}', [PDFController::class, 'ViewClientJournal']);
    Route::post('archive-journal-entry-{id}', [Controller::class, 'ArchiveJournalEntry']);
    Route::post('update-company-info', [ClientController::class, 'UpdateClientCompanyInfo']);
    Route::put('update-company-profile', [ClientController::class, 'updateCompanyProfile'])->name('update-company-profile');

});

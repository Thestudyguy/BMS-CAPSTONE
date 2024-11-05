@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h6 class="h4 fw-bold">Add New Journal</h6>
                    <span class="text-md fw-bold" style="color: #063D58;">Client: {{ $client->CEO }}</span><br>
                    <span class="text-md fw-bold" style="color: #063D58;">Company: {{ $client->CompanyName }}</span>
                    <hr>

                    <div class="step-indicator-container text-center mb-4">
                        <section class="step-indicator">
                            <div class="step step1 active">
                                <div class="step-icon">1</div>
                                <p>Income</p>
                            </div>
                            <div class="indicator-line active"></div>
                            <div class="step step2">
                                <div class="step-icon">2</div>
                                <p>Expense</p>
                            </div>
                            <div class="indicator-line"></div>
                            <div class="step step3">
                                <div class="step-icon">3</div>
                                <p>Assets</p>
                            </div>
                            <div class="indicator-line"></div>
                            <div class="step step4">
                                <div class="step-icon">4</div>
                                <p>Liability</p>
                            </div>
                            <div class="indicator-line"></div>
                            <div class="step step5">
                                <div class="step-icon">5</div>
                                <p>Equity</p>
                            </div>
                            <div class="indicator-line"></div>
                            <div class="step step5">
                                <div class="step-icon">6</div>
                                <p>Adjustments</p>
                            </div>
                            <div class="indicator-line"></div>
                            <div class="step step6">
                                <div class="step-icon">7</div>
                                <p>Summary</p>
                            </div>
                        </section>
                    </div>

                    {{-- Expense Form --}}
                    <div class="multi-step-journal income">
                        <form action="" class="income-form">
                            <div class="card elevation-2 mt-5">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Income</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="income" class="form-control" id="income-category">
                                                <option value="" selected hidden>Select Account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}_{{ $account->Account }}">
                                                        {{ $account->Account }} - ({{ $account->AT }},
                                                        {{ $account->Category }})</option>
                                                @endforeach
                                            </select>
                                            <div class="row visually-hidden income-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month"
                                                            class="form-control income-start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month"
                                                            class="form-control income-end-date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 income-months-container m-3">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="saved-months-table text-sm">
                                                <table class="table" id="saved-income-months">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- Income form --}}
                    <div class="multi-step-journal expense" style="display: none;">
                        <form action="" class="expense-form">
                            <div class="card elevation-2 mt-5">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Expense</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="expense" class="form-control" id="expense-category">
                                                <option value="" selected hidden>Select Account</option>
                                                @foreach ($accounts as $account)
                                                @if (trim(strtolower($account->AT)) === 'less direct cost' || trim(strtolower($account->AT)) === 'operating expenses')
                                                    <option value="{{ $account->id }}_{{ $account->Account }}_{{ $account->AT }}">
                                                        {{ $account->Account }} - ({{ $account->AT }}, {{ $account->Category }})
                                                    </option>
                                                @endif
                                            @endforeach
                                                
                                            </select>
                                            <div class="row visually-hidden expense-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month"
                                                            class="form-control start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month"
                                                            class="form-control end-date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 months-container m-3">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="saved-months-table text-sm">
                                                <table class="table" id="saved-months">

                                                    {{-- <tbody class="saved-months123"> --}}
                                                    {{-- <table class="table table-hover">
                                                                <tr class="external-service" data-widget="expandable-table" aria-expanded="false">
                                                                    <td>
                                                                        asd
                                                                    </td>
                                                                </tr>
                                                                <tr class="expandable-body bg-light">
                                                                    <td>
                                                                        <div class="p-0 text-center expandable-body-append-table">
                                                                            <table class="table table-hover float-left">
                                                                               <tr>
                                                                                <td>asd</td>
                                                                                <td>asd</td>
                                                                               </tr>
                                                                               <tr>
                                                                                <td>asd</td>
                                                                                <td>asd</td>
                                                                               </tr>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        </table> --}}
                                                    {{-- </tbody> --}}
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{--      --}}
                    </div>
                    <div class="multi-step-journal assets" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Asset</h6>
                            <form action="" class="journal-asset-form">
                                <div class="card-body">{{-- style="display: flex; flex-direction: column; justify-content: center; align-items: center;" --}}
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <select class="form-control" name="assetType" id="asset_account">
                                        <option value="" selected hidden>Select asset type</option>
                                        @foreach ($ats as $at)
                                            <option value="{{ $at->AccountType }}_{{ $at->id }}">{{ $at->AccountType }}</option>
                                        @endforeach
                                    </select>
                                    <select name="assetAccount" id="asset_account_name" class="form-control my-3">
                                        <option value="" selected hidden>Select an asset type first</option>
                                    </select>
                                    <input type="text" name="assetAmount" class="form-control"
                                        placeholder="Enter amount..." oninput="formatValueInput(this)" id="">
                                    <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" type="button" style="background: #063D58; align-self: flex-end;">Save</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover">
                                                <thead">
                                                    <tr>
                                                        
                                                        <td style="font-size: 0.8em;">Asset Type</td>
                                                        <td style="font-size: 0.8em;">Asset Account</td>
                                                        <td style="font-size: 0.8em;">Amount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="append-asset-accounts"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal liability" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Liability</h6>
                            <form action="" class="journal-liability-form">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select class="form-control" name="liability-account" id="liability_account">
                                                <option value="" selected hidden>Select liability type</option>
                                                @foreach ($lts as $lt)
                                                    <option value="{{ $lt->AccountType }}_{{ $lt->id }}">{{ $lt->AccountType }}</option>
                                                @endforeach
                                            </select>
                                            <select name="liability-account-name" id="liability_account_name"
                                                class="form-control my-3">
                                                <option value="" selected hidden>Select a liability type first</option>
                                            </select>
                                            <input type="text" name="liability-amount" class="form-control"
                                                placeholder="Enter amount..." oninput="formatValueInput(this)" id="">
                                                <button class="btn btn-sm mb-5 text-light fw-bold save-liability-info" type="button" style="background: #063D58; align-self: flex-end;">Save</button>
                                            </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover">
                                                <thead">
                                                    <tr>
                                                        
                                                        <td style="font-size: 0.8em;">Asset Type</td>
                                                        <td style="font-size: 0.8em;">Asset Account</td>
                                                        <td style="font-size: 0.8em;">Amount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="append-liability-accounts"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                   
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal equity" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Owner's Equity</h6>
                            <form action="" class="journal-oe-form border border-danger">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select class="form-control" name="oe-account" id="oe_account">
                                                <option value="" selected hidden>Select account type</option>
                                                @foreach ($oets as $oet)
                                                    <option value="{{ $oet->AccountType }}_{{ $oet->id }}">{{ $oet->AccountType }}</option>
                                                @endforeach
                                            </select>
                                            <select name="oe-account-name" id="oe_account_name" class="form-control my-3">
                                                <option value="" selected hidden>Select a owner's equity type first</option>
                                            </select>
                                            <input type="text" name="oe-amount" class="form-control"
                                                placeholder="Enter amount..." oninput="formatValueInput(this)" id="">
                                                <button class="btn btn-sm mb-5 text-light fw-bold save-oe-info" type="button" style="background: #063D58; align-self: flex-end;">Save</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover">
                                                <thead">
                                                    <tr>
                                                        
                                                        <td style="font-size: 0.8em;">Asset Type</td>
                                                        <td style="font-size: 0.8em;">Asset Account</td>
                                                        <td style="font-size: 0.8em;">Amount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="append-oe-accounts"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal adjustments" style="display: none; font-family: Poppins;">
                        <div class="card border p-3">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Owner's Equity Adjustments</h6>
                            <form action="" class="journal-adjustments-form">
                                <label for="OnwersContribution">Owner's Contribution</label>
                                <input type="text" name="owners_contribution" class="form-control" oninput="formatValueInput(this)" id="" placeholder="Enter amount...">
                                <label for="OnwersContribution">Owner's Withdrawal</label>
                                <input type="text" name="owners_withdrawal" class="form-control" oninput="formatValueInput(this)" id="" placeholder="Enter amount...">
                            </form>
                        </div>
                    </div>
                    <div class="multi-step-journal summary" style="display: none; font-family: Poppins;">
                        summary here
                        {{-- <div class="row">
                            <div class="col-sm-6 border">
                                <div class="row">
                                    <div class="col border">
                                        <div class="card fo-card rounded-0">
                                            <center><h4 class="h4 fw-bold text-dark">Financial Operation</h4></center>
                                            <center><h5 class="h5 fw-bold text-dark">{{$client->CompanyName}}</h5></center>
                                            <center><h6 class="h6 text-dark">{{$client->CompanyAddress }}</h6></center>
                                            <div class="row">
                                                <div class="col-sm-12" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58">For the year Ended <span class="float-right">2024</span></div>
                                                <div class="col-sm-12"></div>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <span class="float-left fw-bold mt-5">Revenues</span>
                                                        <div class="col-sm-12 ml-3">
                                                            <span class="revenue-accounts float-left">Rev Account</span>
                                                            <span class="revenue-amount float-right">Rev Amount</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <span class="float-left fw-bold">Less:Direct Cost</span>
                                                        <div class="col-sm-12 ml-3">
                                                            <span class="expenses-accounts float-left">Expenses Account</span>
                                                            <span class="expenses-amount float-right">Rev Amount</span><br>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="expenses-total float-left fw-bold">Total Direct Cost</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="expenses-total float-left fw-bold">Total Gross Income from Engineering Services Cost</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <span class="float-left fw-bold">Total Gross Income</span>
                                                    <span class="float-right fw-bold">Amount</span>
                                                </div>
                                                <br>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <span class="float-left fw-bold mt-5">Less:Operating Expenses</span>
                                                        <div class="col-sm-12 ml-3">
                                                            <span class="expenses-accounts float-left">Expenses Account</span>
                                                            <span class="revenue-amount float-right">Expenses Amount</span>
                                                        </div>
                                                        <div class="col-sm-12 fw-bold">Total Operating Expense</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <span class="net-income fw-bold float-left">Net Income</span>
                                                    <span class="net-amount fw-bold float-right">20,000,000</span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <span class="float-left fw-bold">Certified True & Correct</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="float-left fw-bold" style="text-decoration: underline">Rogelio O. Magandam, Jr.</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="float-left">Proprietor</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="float-left">TIN: 291-273-180-000</span>
                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 border">
                                Statement of Financial Position
                            </div>
                        </div> --}}
                    </div>

                    <div class="multi-step-action-buttons float-right">
                        <button class="btn btn-secondary prev-btn" style="font-weight: bold;">Previous</button>
                        <button class="btn next-btn"
                            style="background: #063D58; font-weight: bold; color: whitesmoke;">Next</button>
                        <button class="btn btn-primary save-btn" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 border">
                    <div class="row">
                        <div class="col border">
                            <div class="card fo-card rounded-0">
                                <center>
                                    <h4 class="h4 fw-bold text-dark">Financial Operation</h4>
                                </center>
                                <center>
                                    <h5 class="h5 fw-bold text-dark">{{ $client->CompanyName }}</h5>
                                </center>
                                <center>
                                    <h6 class="h6 text-dark">{{ $client->CompanyAddress }}</h6>
                                </center>
                                <div class="row">
                                    <div class="col-sm-12"
                                        style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58">For the
                                        year Ended <span class="float-right">2024</span></div>
                                    <div class="col-sm-12"></div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <span class="float-left fw-bold mt-5">Revenues</span>
                                            <div class="col-sm-12 ml-3 text-dark" id="append-expenses-choy">
                                                {{-- <span class="revenue-accounts float-left">Rev Account</span>
                                                <span class="revenue-amount float-right">Rev Amount</span> --}}
                                            </div>
                                            <div class="col-sm-12 ml-3 append-expense-total">
                                                {{-- <span class="revenue-accounts float-left">Total</span>
                                                <span class="revenue-amount float-right">00.00</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <span class="float-left fw-bold">Less:Direct Cost</span>
                                            <div class="col-sm-12 ml-3 append-ldc">
                                               {{-- append less direct costs here --}}
                                            </div>
                                            <div class="col-sm-12 ml-3">
                                                <span class="float-left fw-bold">Total Direct Cost</span>
                                                <span class="expenses-total float-right"></span>
                                            </div>
                                            <div class="col-sm-12 m-3 mt-0 pt-0">
                                                <span class="float-left fw-bold">Total Gross Income from
                                                    Engineering Services Cost</span>
                                                    <span class="gries-total float-right fw-bold"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <span class="float-left fw-bold">Total Gross Income</span>
                                        <span class="float-right fw-bold tgi"></span>
                                    </div>
                                    <br>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <span class="float-left fw-bold mt-5">Less:Operating Expenses</span>
                                            <div class="col-sm-12 ml-3 append-oe">
                                                {{-- <span class="expenses-accounts float-left">Expenses Account</span>
                                                <span class="revenue-amount float-right">Expenses Amount</span> --}}
                                            </div>
                                            <div class="col-sm-12 fw-bold">
                                                <span class="float-left fw-bold">Total Operating Expense</span>
                                                <span class="float-right fw-bold oe-total"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <span class="net-income fw-bold float-left">Net Income</span>
                                        <span class="net-amount fw-bold float-right">20,000,000</span>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <span class="float-left fw-bold">Certified True & Correct</span>
                                            </div>
                                            <div class="col-sm-12">
                                                <span class="float-left fw-bold"
                                                    style="text-decoration: underline">Rogelio O. Magandam, Jr.</span>
                                            </div>
                                            <div class="col-sm-12">
                                                <span class="float-left">Proprietor</span>
                                            </div>
                                            <div class="col-sm-12">
                                                <span class="float-left">TIN: 291-273-180-000</span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 border">
                   <div class="row">
                    <div class="col border">
                        <div class="card fp-card rounded-0">
                                <center>
                                    <h4 class="h4 fw-bold text-dark">Financial Position</h4>
                                </center>
                                <center>
                                    <h5 class="h5 fw-bold text-dark">{{ $client->CompanyName }}</h5>
                                </center>
                                <center>
                                    <h6 class="h6 text-dark">{{ $client->CompanyAddress }}</h6>
                                </center>
                                <div class="row">
                                    <div class="col-sm-12"style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58">
                                        <span class="float-left text-sm">For the years ended november</span>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-12">
                                        {{-- <span class="fw-bold text-md float-left">Current Assets</span>
                                        <span class="fw-bold text-md float-left">Total Current Assets</span> --}}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12">
                                                    <span class="fw-bold text-md">Current Assets</span>
                                                </div>
                                                <div class="col-sm-12 ml-3 append-ca">
                                                    
                                                </div>
                                                
                                            </div>
                                            <div class="col-sm-12" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58;">
                                                <span class="fw-bold text-md float-left ml-3">Total Current Assets</span>
                                                <span class="fw-normal text-md float-right total-ca"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 m-3"></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <span class="fw-bold text-md float-left">Non-Current Assets</span><br>
                                                <div class="col-sm-12 ml-3 append-nca"></div>
                                            </div>
                                            <div class="col-sm-12" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58;">
                                                <span class="fw-bold text-md float-left ml-3">Total Non-Current Assets</span>
                                                <span class="fw-bold text-md float-right tnca-amount"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 m-3"></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <span class="fw-bold text-md float-left">Fixed Assets</span>
                                                <div class="col-sm-12 ml-3 append-fa"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 m-3"></div>
                                        <div class="row">
                                            <div class="col-sm-12" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58;"s>
                                                <span class="fw-bold text-md float-left">Total Assets</span>
                                                <span class="fw-normal text-sm float-right total-assets"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <span class="fw-bold text-md float-left">Current Liabilities</span>
                                                    </div>
                                                    <div class="col-sm-12 ml-3 append-cl">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 m-3"></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <span class="fw-bold text-md float-left"><i>Owner's Equity / Net Worth</i></span>
                                                <div class="col-sm-12 ml-3 append-oenw"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 m-3"></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <span class="fw-bold text-md float-left">Total Liabilities & Capital</span>
                                                <span class="fw-normal text-md float-right tlc"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
@endsection

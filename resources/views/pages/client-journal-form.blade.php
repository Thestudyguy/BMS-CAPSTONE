@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h6 class="h4 fw-bold">Add New Journal</h6>
                    <span class="text-md fw-bold" style="color: #063D58;">Client: {{$client->CEO}}</span><br>
                    <span class="text-md fw-bold" style="color: #063D58;">Company: {{$client->CompanyName}}</span>
                    <hr>

                    <div class="step-indicator-container text-center mb-4">
                        <section class="step-indicator">
                            <div class="step step1 active">
                                <div class="step-icon">1</div>
                                <p>Expense</p>
                            </div>
                            <div class="indicator-line active"></div>
                            <div class="step step2">
                                <div class="step-icon">2</div>
                                <p>Income</p>
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
                            <div class="step step6">
                                <div class="step-icon">6</div>
                                <p>Summary</p>
                            </div>
                        </section>
                    </div>

                    {{-- Expense Form --}}
                    <div class="multi-step-journal expense">
                        <form action="" class="expense-form">
                            <div class="card elevation-2 mt-5">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Expense</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="expense" class="form-control" id="expense-category">
                                                <option value="" selected hidden>Select Account</option>
                                                @foreach ($accounts as $account)
                                                        <option value="{{$account->id}}_{{$account->Account}}">{{$account->Account}} - ({{$account->AT}}, {{$account->Category}})</option>
                                                @endforeach
                                            </select>
                                            <div class="row visually-hidden expense-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month" class="form-control start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month" class="form-control end-date">
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
                    </div>
                    {{-- Income form --}}
                    <div class="multi-step-journal income" style="display: none;">
                        <form action="" class="income-form">
                            <div class="card elevation-2 mt-5">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Income</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="income" class="form-control" id="income-category">
                                                <option value="" selected hidden>Select Account</option>
                                                @foreach ($accounts as $account)
                                                        <option value="{{$account->id}}_{{$account->Account}}">{{$account->Account}} - ({{$account->AT}}, {{$account->Category}})</option>
                                                @endforeach
                                            </select>
                                            <div class="row visually-hidden income-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month" class="form-control income-start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month" class="form-control income-end-date">
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
                    <div class="multi-step-journal assets" style="display: none;">
                            <div class="card border">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Asset</h6>
                                <form action="" class="journal-asset-form">
                                <div class="card-body" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                        <select class="form-control" name="asset-account" id="asset_account">
                                            <option value="" selected hidden>Select asset type</option>
                                            @foreach ($ats as $at)
                                                <option value="asset_{{$at->id}}">{{$at->AccountType}}</option>
                                            @endforeach
                                        </select>
                                        <select name="asset-account-name" id="asset_account_name" class="form-control my-3">
                                            <option value="" selected hidden>Select an asset type first</option>
                                        </select>
                                        <input type="text" name="asset-amount" class="form-control" placeholder="Enter amount..." oninput="formatValueInput(this)" id="">
                                        {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58; align-self: flex-end;">Save</button> --}}
                                        {{-- <input type="text" name="asset-amount" class="form-control" placeholder="Enter amount..." oninput="formatValueInput(this)" id=""> --}}
                                        {{-- <div class="row w-100">
                                        </div> --}}
                                    </div>
                                </form>
                                {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                            </div>
                    </div>
                    <div class="multi-step-journal liability" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Liability</h6>
                            <form action="" class="journal-liability-form">
                            <div class="card-body" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                    <select class="form-control" name="liability-account" id="liability_account">
                                        <option value="" selected hidden>Select asset type</option>
                                        @foreach ($lts as $lt)
                                            <option value="liability_{{$lt->id}}">{{$lt->AccountType}}</option>
                                        @endforeach
                                    </select>
                                    <select name="liability-account-name" id="liability_account_name" class="form-control my-3">
                                        <option value="" selected hidden>Select a liability type first</option>
                                    </select>
                                    <input type="text" name="liability-amount" class="form-control" placeholder="Enter amount..." oninput="formatValueInput(this)" id="">
                                    {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58; align-self: flex-end;">Save</button> --}}
                                    {{-- <input type="text" name="asset-amount" class="form-control" placeholder="Enter amount..." oninput="formatValueInput(this)" id=""> --}}
                                    {{-- <div class="row w-100">
                                    </div> --}}
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal equity" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Owner's Equity</h6>
                            <form action="" class="journal-oe-form">
                            <div class="card-body" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                    <select class="form-control" name="oe-account" id="oe_account">
                                        <option value="" selected hidden>Select asset type</option>
                                        @foreach ($oets as $oet)
                                            <option value="oe_{{$oet->id}}">{{$oet->AccountType}}</option>
                                        @endforeach
                                    </select>
                                    <select name="oe-account-name" id="oe_account_name" class="form-control my-3">
                                        <option value="" selected hidden>Select a owner's equity type first</option>
                                    </select>
                                    <input type="text" name="oe-amount" class="form-control" placeholder="Enter amount..." oninput="formatValueInput(this)" id="">
                                    {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58; align-self: flex-end;">Save</button> --}}
                                    {{-- <input type="text" name="asset-amount" class="form-control" placeholder="Enter amount..." oninput="formatValueInput(this)" id=""> --}}
                                    {{-- <div class="row w-100">
                                    </div> --}}
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal summary" style="display: none;">Summary of all the steps</div>

                    <div class="multi-step-action-buttons float-right">
                        <button class="btn btn-secondary prev-btn" style="font-weight: bold;">Previous</button>
                        <button class="btn next-btn" style="background: #063D58; font-weight: bold; color: whitesmoke;">Next</button>
                        <button class="btn btn-primary save-btn" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

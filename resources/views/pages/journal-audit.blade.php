@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="audit-client-journal-loader visually-hidden" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.8); z-index: 10; display: flex; justify-content:center; align-items: center;">
            <div class="loader"></div>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h6 class="h4 fw-bold">Edit Journal</h6>
                    <span class="text-md fw-bold" style="color: #063D58;">Client: {{ $client->CEO }}</span><br>
                    <span class="text-md fw-bold" style="color: #063D58;">Company: {{ $client->CompanyName }}</span><br>
                    <span class="text-md fw-bold" style="color: #063D58;">Journal ID#: {{ $journal->journal_id }}</span>
                    <hr>

                    <div class="step-indicator-container text-center mb-4">
                        <section class="step-indicator">
                            <div class="audit-step step1 active">
                                <div class="audit-step-icon">1</div>
                                <p>Income</p>
                            </div>
                            <div class="audit-indicator-line active"></div>
                            <div class="audit-step step2">
                                <div class="audit-step-icon">2</div>
                                <p>Expense</p>
                            </div>
                            <div class="audit-indicator-line"></div>
                            <div class="audit-step step3">
                                <div class="audit-step-icon">3</div>
                                <p>Assets</p>
                            </div>
                            <div class="audit-indicator-line"></div>
                            <div class="audit-step step4">
                                <div class="audit-step-icon">4</div>
                                <p>Liability</p>
                            </div>
                            <div class="audit-indicator-line"></div>
                            <div class="audit-step step5">
                                <div class="audit-step-icon">5</div>
                                <p>Equity</p>
                            </div>
                            <div class="audit-indicator-line"></div>
                            <div class="audit-step step5">
                                <div class="audit-step-icon">6</div>
                                <p>Adjustments</p>
                            </div>
                            <div class="audit-indicator-line"></div>
                            <div class="audit-step step6">
                                <div class="audit-step-icon">7</div>
                                <p>Summary</p>
                            </div>
                        </section>
                    </div>

                    {{-- Expense Form --}}
                    <div class="multi-step-journal-audit income">
                        <form action="" class="audit-income-form">
                            <div class="card elevation-2 mt-5">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Income</h6>
                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="income" class="form-control" id="audit-income-category">
                                                <option value="" selected hidden>Select Account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}_{{ $account->Account }}">
                                                        {{ $account->Account }} - ({{ $account->AT }},
                                                        {{ $account->Category }})</option>
                                                @endforeach
                                            </select>
                                            <div class="row visually-hidden audit-income-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month"
                                                            class="form-control audit-income-start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month"
                                                            class="form-control audit-income-end-date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 audit-income-months-container m-3">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="saved-audited-months-table text-sm">
                                                <table class="table" id="saved-audited-income-months">
                                                    @foreach ($groupedIncomeData as $account => $data)
                                                        @php
                                                            $preparedAccount = explode('_', $account);
                                                        @endphp
                                                        <tr id="{{ $account }}" class="client-journal-accounts text-sm" data-widget="expandable-table" aria-expanded="true">
                                                            <td>{{ $preparedAccount[1] }}</td>
                                                            <td>
                                                                <span class="fw-bold text-dark remove-audit-income" id="{{ $account }}">
                                                                    <i class="fas fa-times"></i>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr class="expandable-body bg-light">
                                                            <td>
                                                                <div class="">
                                                                    <table class="table table-hover float-left">
                                                                        <thead>
                                                                            <tr>
                                                                                <td>Month</td>
                                                                                <td>Amount</td>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($data['months'] as $month)
                                                                                <tr>
                                                                                    <td>{{ $month['incomeMonthName'] }}</td>
                                                                                    <td>{{ number_format($month['value'], 2) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- Income form --}}
                    <div class="multi-step-journal-audit expense" style="display: none;">
                        <form action="" class="expense-form">
                            <div class="card elevation-2 mt-5">
                                <h6 class="h4 fw-bold p-3" style="color:#063D58;">Expense</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="expense" class="form-control" id="audit-expense-category">
                                                <option value="" selected hidden>Select Account</option>
                                                @foreach ($accounts as $account)
                                                @if (trim(strtolower($account->AT)) === 'less direct cost' || trim(strtolower($account->AT)) === 'operating expenses')
                                                    <option value="{{ $account->id }}_{{ $account->Account }}_{{ $account->AT }}">
                                                        {{ $account->Account }} - ({{ $account->AT }}, {{ $account->Category }})
                                                    </option>
                                                @endif
                                            @endforeach
                                                
                                            </select>
                                            <div class="row visually-hidden audit-expense-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month"
                                                            class="form-control audit-expense-start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month"
                                                            class="form-control audit-expense-end-date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 audit-expense-months-container m-3">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="saved-months-table text-sm">
                                                <table class="table" id="saved-audited-expense-months">
                                                    @foreach ($groupedExpenseData as $account => $data)
                                                        @php
                                                            $preparedAccount = explode('_', $account);
                                                        @endphp
                                                        <tr id="{{ $account }}" class="client-journal-accounts text-sm" data-widget="expandable-table" aria-expanded="true">
                                                            <td>{{ $preparedAccount[1] }}</td>
                                                            <td>
                                                                <span class="fw-bold text-dark remove-audit-expense" id="{{ $account }}">
                                                                    <i class="fas fa-times"></i>
                                                                    {{-- {{$groupedExpenseData}} --}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr class="expandable-body bg-light">
                                                            <td>
                                                                <div class="">
                                                                    <table class="table table-hover float-left">
                                                                        <thead>
                                                                            <tr>
                                                                                <td>Month</td>
                                                                                <td>Amount</td>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($data['months'] as $month)
                                                                                <tr>
                                                                                    <td>{{ $month['expenseMonthName'] }}</td>
                                                                                    <td>{{ $month['value'] }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{--      --}}
                    </div>
                    <div class="multi-step-journal-audit assets" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Asset</h6>
                            <form action="" class="journal-audit-asset-form">
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
                                        placeholder="Enter amount..." oninput="formatValueInput(this)" id="assetAmount">
                                    <button class="btn btn-sm mb-5 text-light fw-bold save-audit-asset-info" type="button" style="background: #063D58; align-self: flex-end;">Save</button>
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
                                                <tbody class="append-audit-asset-accounts">
                                                    @foreach ($journalAsset as $asset)
                                                        <tr id="{{$asset->id}}" class="text-sm">
                                                            <td>{{$asset->asset_category}}</td>
                                                            <td>{{$asset->account}}</td>
                                                            <td>{{number_format($asset->amount, 2)}}<span class="badge fw-bold text-dark float-right remove-audited-asset" id="{{$asset->account}}"><i class="fas fa-times"></i></span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal-audit liability" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Liability</h6>
                            <form action="" class="journal-audit-liability-form">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select class="form-control" name="audit-liability-account" id="liability_account">
                                                <option value="" selected hidden>Select liability type</option>
                                                @foreach ($lts as $lt)
                                                    <option value="{{ $lt->AccountType }}_{{ $lt->id }}">{{ $lt->AccountType }}</option>
                                                @endforeach
                                            </select>
                                            <select name="audit-liability-account-name" id="liability_account_name"
                                                class="form-control my-3">
                                                <option value="" selected hidden>Select a liability type first</option>
                                            </select>
                                            <input type="text" name="liability-amount" class="form-control"
                                                placeholder="Enter amount..." oninput="formatValueInput(this)" id="liabilityAmount">
                                                <button class="btn btn-sm mb-5 text-light fw-bold save-audit-liability-info" type="button" style="background: #063D58; align-self: flex-end;">Save</button>
                                            </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover">
                                                <thead">
                                                    <tr>
                                                        
                                                        <td style="font-size: 0.8em;">Account Type</td>
                                                        <td style="font-size: 0.8em;">Account</td>
                                                        <td style="font-size: 0.8em;">Amount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="append-audit-liability-accounts text-sm">
                                                    @foreach ($journalLiability as $liability)
                                                        <tr id="{{$liability->id}}" class="text-sm">
                                                            <td>{{$liability->AccountType}}</td>
                                                            <td>{{$liability->account}}</td>
                                                            <td>{{number_format($liability->amount, 2)}}<span class="badge fw-bold text-dark float-right remove-audit-liability" id="{{$liability->account}}"><i class="fas fa-times"></i></span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                   
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal-audit equity" style="display: none;">
                        <div class="card border">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Owner's Equity</h6>
                            <form action="" class="journal-audit-oe-form">
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
                                                placeholder="Enter amount..." oninput="formatValueInput(this)" id="oeAmount">
                                                <button class="btn btn-sm mb-5 text-light fw-bold save-audit-oe-info" type="button" style="background: #063D58; align-self: flex-end;">Save</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover">
                                                <thead">
                                                    <tr>
                                                        
                                                        <td style="font-size: 0.8em;">Account Type</td>
                                                        <td style="font-size: 0.8em;">Account</td>
                                                        <td style="font-size: 0.8em;">Amount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="append-audit-oe-accounts">
                                                    @foreach ($journalOE as $oe)
                                                        <tr id="{{$oe->id}}" class="text-sm">
                                                            <td>{{$oe->AccountType}}</td>
                                                            <td>{{$oe->account}}</td>
                                                            <td>
                                                                {{number_format($oe->amount, 2)}}
                                                                <span class="badge fw-bold text-dark float-right remove-audit-oe {{$oe->id}}" id="{{$oe->account}}"><i class="fas fa-times"></i></span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {{-- <button class="btn btn-sm mb-5 text-light fw-bold save-asset-info" style="background: #063D58">Save</button> --}}
                        </div>
                    </div>
                    <div class="multi-step-journal-audit adjustments" style="display: none; font-family: Poppins;">
                        <div class="card border p-3">
                            <h6 class="h4 fw-bold p-3" style="color:#063D58;">Owner's Equity Adjustments</h6>
                            <form action="" class="journal-audit-adjustments-form">
                                <label for="OnwersContribution">Owner's Contribution</label>
                                <input type="text" value="{{number_format($journaladjustment->owners_contribution, 2)}}" name="audit-owners_contribution" class="form-control" oninput="formatValueInput(this)" id="" placeholder="Enter amount...">
                                <label for="OnwersContribution">Owner's Withdrawal</label>
                                <input type="text" value="{{number_format($journaladjustment->owners_withdrawal, 2)}}" name="audit-owners_withdrawal" class="form-control" oninput="formatValueInput(this)" id="" placeholder="Enter amount...">
                            </form>
                        </div>
                    </div>
                    <div class="multi-step-journal-audit summary" style="display: none; font-family: Poppins;">
                        {{-- style="display: none; font-family: Poppins;" ibalik after --}}
                        {{-- ibalik ang code diri after --}}
                        <div class="row">
                            <div class="col-sm-6 border">
                                <div class="row">
                                    <div class="col border">
                                        <div class="card fo-card rounded-0 p-3">
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
                                                        <div class="col-sm-12 ml-3 text-dark" id="append-audit-expenses-choy">
                                                            @php
                                                                $totalIncome = 0;
                                                                $totalExpenseLDC = 0;
                                                                $totalExpenseOE = 0;
                                                                $totalAssets = 0;
                                                                $tca = 0;
                                                                $tnca = 0;
                                                                $fa = 0;
                                                                $totaloe = 0;
                                                                $totalLiability = 0;
                                                                $totalAdjustments = 0;
                                                            @endphp
                                                            @foreach ($journalIncome as $income)
                                                                @php
                                                                    $preparedAccount = Str::contains($income->account, '_') 
                                                                                        ? explode('_', $income->account)[1] 
                                                                                        : $income->account;
                                                                    $totalIncome += $income->amount;
                                                                @endphp
                                                                <span class="revenue-audit-accounts float-left">{{ $preparedAccount }}</span>
                                                                <span class="revenue-audit-amount float-right">{{ number_format($income->amount, 2) }}</span>
                                                            @endforeach
                                                        </div>
                                                        
                                                        <div class="col-sm-12 ml-3 append-expense-total">
                                                            <span class="revenue-accounts float-left">Total</span>
                                                            <span class="revenue-audit-income-total float-right">{{number_format($income->amount, 2)}}</span>
                                                            {{-- <span class="revenue-accounts float-left">Total</span>
                                                            <span class="revenue-amount float-right">00.00</span> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <span class="float-left fw-bold">Less:Direct Cost</span>
                                                        <div class="col-sm-12 ml-3 append-audit-ldc">
                                                            @foreach ($journalExpense as $expense)
                                                            @php
                                                                $preparedAccount = explode('_', $expense->account);
                                                                
                                                            @endphp
                                                            @if ($preparedAccount[2] === 'Less Direct Cost')
                                                            @php
                                                                $totalExpenseLDC += $expense->amount;
                                                            @endphp
                                                            <div class="row">
                                                                <span class="col-sm-6 text-left">{{$preparedAccount[1]}}</span>
                                                                <span class="col-sm-6 text-right">{{number_format($expense->amount, 2)}}</span>
                                                            </div>
                                                            @endif
                                                            
                                                            @endforeach
                                                           {{-- append less direct costs here --}}
                                                        </div>
                                                        <div class="col-sm-12 ml-3">
                                                            <span class="float-left fw-bold">Total Direct Cost</span>
                                                            <span class="expenses-ldc-audit-total float-right fw-bold">{{number_format($totalExpenseLDC, 2)}}</span>
                                                        </div>
                                                        <div class="col-sm-12 m-3 mt-0 pt-0">
                                                            <span class="float-left fw-bold">Total Gross Income from
                                                                Engineering Services Cost</span>
                                                                <span class="gries-audit-total float-right fw-bold">{{number_format($totalIncome - $totalExpenseLDC, 2)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <span class="float-left fw-bold">Total Gross Income</span>
                                                    <span class="float-right fw-bold tgi-audit">{{number_format($totalIncome - $totalExpenseLDC, 2)}}</span>
                                                </div>
                                                <br>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <span class="float-left fw-bold mt-5">Less:Operating Expenses</span>
                                                        <div class="col-sm-12 ml-3 append-audit-oe">
                                                            @foreach ($journalExpense as $expense)
                                                            @php
                                                                $preparedAccount = explode('_', $expense->account);
                                                                
                                                            @endphp
                                                            @if ($preparedAccount[2] === 'Operating Expenses')
                                                            @php
                                                                $totalExpenseOE += $expense->amount;
                                                            @endphp
                                                            <div class="row">
                                                                <span class="col-sm-6 text-left">{{$preparedAccount[1]}}</span>
                                                                <span class="col-sm-6 text-right">{{number_format($expense->amount, 2)}}</span>
                                                            </div>
                                                            @endif
                                                            
                                                            @endforeach
                                                            {{-- <span class="expenses-accounts float-left">Expenses Account</span>
                                                            <span class="revenue-amount float-right">Expenses Amount</span> --}}
                                                        </div>
                                                        <div class="col-sm-12 fw-bold">
                                                            <span class="float-left fw-bold">Total Operating Expense</span>
                                                            <span class="float-right fw-bold oe-audit-total">{{number_format($totalExpenseOE, 2)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <span class="net-income fw-bold float-left">Net Income</span>
                                                    <span class="net-audit-amount fw-bold float-right">{{number_format($totalIncome - $totalExpenseLDC - $totalExpenseOE, 2)}}</span>
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
                                    <div class="card fp-card rounded-0 p-3">
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
                                                            <div class="col-sm-12 ml-3 append-ca-audit">
                                                                @php
                                                                $hasCurrentAssets = false;
                                                                @endphp
                                                                @foreach ($journalAsset as $asset)
                                                                    @if ($asset->asset_category === 'Current Asset')
                                                                    @php
                                                                        $hasCurrentAssets = true;
                                                                        $tca += $asset->amount;
                                                                    @endphp
                                                                    <div class="row">
                                                                        <span class="col-sm-6 text-sm text-left">{{$asset->account}}</span>
                                                                        <span class="col-sm-6 text-sm text-right">{{number_format($asset->amount, 2)}}</span>
                                                                    </div>
                                                                    @endif
                                                                @endforeach
                                                                @if (!$hasCurrentAssets)
                                                                <div class="row">
                                                                    <span class="col-sm-6 text-sm text-left">-</span>
                                                                    <span class="col-sm-6 text-sm text-right">-</span>
                                                                </div>
                                                            @endif
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-sm-12 text-sm" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58;">
                                                            <span class="fw-bold text-md float-left ml-3">Total Current Assets</span>
                                                            <span class="fw-normal text-md float-right total-audit-ca">{{$tca ? number_format($tca, 2) : '-'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 m-3"></div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <span class="fw-bold text-md float-left">Non-Current Assets</span><br>
                                                            <div class="col-sm-12 ml-3 append-audit-nca">
                                                                @php
                                                                $hasNonCurrentAssets = false;
                                                                @endphp
                                                            @foreach ($journalAsset as $asset)
                                                                @if ($asset->asset_category === 'Non-Current Assets')
                                                                    @php
                                                                        $hasNonCurrentAssets = true;
                                                                        $tnca += $asset->amount;
                                                                    @endphp
                                                                    <div class="row">
                                                                        <span class="col-sm-6 text-sm text-left">{{ $asset->account }}</span>
                                                                        <span class="col-sm-6 text-sm text-right">{{ number_format($asset->amount, 2) }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            
                                                            @if (!$hasNonCurrentAssets)
                                                                <div class="row">
                                                                    <span class="col-sm-6 text-sm text-left">-</span>
                                                                    <span class="col-sm-6 text-sm text-right">-</span>
                                                                </div>
                                                            @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58;">
                                                            <span class="fw-bold text-md float-left ml-3">Total Non-Current Assets</span>
                                                                   <span class="fw-bold text-md float-right tnca-audit-amount">{{$tnca ? number_format($tnca, 2) : '-'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 m-3"></div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <span class="fw-bold text-md float-left">Fixed Assets</span>
                                                                </div>
                                                                <div class="col-sm-12 ml-3 append-audit-fa">
                                                                    @php
                                                                    $hasFixedtAssets = false;
                                                                    @endphp
                                                                @foreach ($journalAsset as $asset)
                                                                    @if ($asset->asset_category === 'Fixed Assets')
                                                                        @php
                                                                            $hasFixedtAssets = true;
                                                                            $fa += $asset->amount;
                                                                        @endphp
                                                                        <div class="row">
                                                                            <span class="col-sm-6 text-sm text-left">{{ $asset->account }}</span>
                                                                            <span class="col-sm-6 text-sm text-right">{{number_format($asset->amount, 2)}}</span>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                                
                                                                @if (!$hasFixedtAssets)
                                                                    <div class="row">
                                                                        <span class="col-sm-6 text-sm text-left">-</span>
                                                                        <span class="col-sm-6 text-sm text-right">-</span>
                                                                    </div>
                                                                @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 m-3"></div>
                                                    <div class="row">
                                                        <div class="col-sm-12" style="border-top: 1px solid #063D58; border-bottom: 1px solid #063D58;"s>
                                                            <span class="fw-bold text-md float-left">Total Assets</span>
                                                            <span class="text-sm float-right total-audit-assets fw-bold">{{number_format($fa + $tnca + $tca, 2)}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <span class="fw-bold text-md float-left">Current Liabilities</span>
                                                                </div>
                                                                <div class="col-sm-12 ml-3 append-audit-cl">
                                                                    @foreach ($journalLiability as $liabilities)
                                                                    @php
                                                                        $totalLiability += $liabilities->amount;
                                                                    @endphp
                                                                        <div class="row">
                                                                            <span class="col-sm-6 text-sm text-left">{{$liabilities->account}}</span>
                                                                            <span class="col-sm-6 text-sm text-right">{{number_format($liabilities->amount, 2)}}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 m-3"></div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <span class="fw-bold text-md float-left"><i>Owner's Equity / Net Worth</i></span>
                                                                </div>
                                                                <div class="col-sm-12 ml-3 append-audit-oenw">
                                                                    @foreach ($journalOE as $oe)
                                                                    @php
                                                                        $totaloe += $oe->amount;
                                                                    @endphp
                                                                        <div class="row">
                                                                            <span class="col-sm-6 text-sm text-left">{{$oe->account}}</span>
                                                                            <span class="col-sm-6 text-sm text-right">{{number_format($oe->amount, 2)}}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                    $net =  $totalIncome - $totalExpenseLDC - $totalExpenseOE;
                                                    $appraisal = $net + $journaladjustment->owners_withdrawal;
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <span class="fw-normal">Add: Net increase to Capital</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="fw-normal float-left ml-3">Additional Capital</span>
                                                            <span class="fw-normal float-right additional-audit-capital fw-bold">{{number_format($journaladjustment->owners_contribution, 2)}}</span>
                                                        </div>
                                                        <div class="col-sm-12" style="border-bottom: 1px solid #063D58;">
                                                            <span class="fw-normal float-left ml-3">Net Income</span>
                                                            <span class="fw-normal float-right fp-audit-nc fw-bold">{{number_format($totalIncome - $totalExpenseLDC - $totalExpenseOE, 2)}}</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="fw-normal float-left">Appraisal Capital</span>
                                                            <span class="fw-normal float-right appraisal-audit-capital fw-bold">{{number_format($net + $journaladjustment->owners_withdrawal, 2)}}</span>
                                                        </div>
                                                        
                                                        <div class="col-sm-12">
                                                            <span class="fw-normal float-left">Less Drawings</span>
                                                            <span class="fw-normal float-right audit-less-drawings fw-bold">{{number_format($journaladjustment->owners_withdrawal, 2)}}</span>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <span class="fw-normal float-left">Capital End</span>
                                                            <span class="fw-normal float-right audit-capital-end fw-bold">{{number_format($appraisal - $journaladjustment->owners_withdrawal, 2)}}</span>
                                                        </div>
                                                    </div>
            
                                                    <div class="col-sm-12 m-3"></div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <span class="fw-bold text-md float-left">Total Liabilities & Capital</span>
                                                            <span class="fw-normal text-md float-right tlc-audit">{{number_format($totaloe + $totalLiability, 2)}}</span>
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

                    <div class="multi-step-action-buttons float-right">
                        <button class="btn btn-secondary audit-prev-btn" style="font-weight: bold;">Previous</button>
                        <button class="btn audit-next-btn"
                            style="background: #063D58; font-weight: bold; color: whitesmoke;">Next</button>
                        <button class="btn btn-primary audit-save-btn save-audit-journal-btn" id="{{$client->id}}_{{$journal->journal_id}}" style="display:none;">Update</button>
                    </div>
                </div>
            </div>
            
        </div>
        <script>
            window.journalIncome = @json($groupedIncomeData);
            window.journalExpense = @json($groupedExpenseData);
            window.journalAssets = @json($journalAsset);
            window.journalLiability = @json($journalLiability);
            window.journalOE = @json($journalOE);
            window.adjustment = @json($journaladjustment);
        </script>
    </div>
@endsection
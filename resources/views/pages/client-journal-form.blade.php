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
                        <h6 class="h4 fw-bold p-3" style="color:#063D58; font-family: 'Open Sans', sans-serif;">Expense</h6>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <select name="expense" class="form-control" id="expense-category">
                                                <option value="" selected hidden>Select Account</option>
                                                
                                            </select>
                                            <div class="row visually-hidden expense-form">
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Select Start Date</span>
                                                        </div>
                                                        <input type="month" name="start-month" class="form-control start-date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Select End Date</span>
                                                        </div>
                                                        <input type="month" name="end-month" class="form-control end-date">
                                                    </div>
                                                </div>
                                                <div class="save-expense-category">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 test">
                                            <div class="months-container"></div>
                                            <button class="btn btn-primary float-right visually-hidden save-expense">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table table-hover table-bordered table-striped">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{-- Expense Form --}}
            <div class="multi-step-journal income" style="display: none;">asd</div>
            <div class="multi-step-journal assets" style="display: none;"></div>
            <div class="multi-step-journal liability" style="display: none;"></div>
            <div class="multi-step-journal equity" style="display: none;"></div>
            <div class="multi-step-journal summary" style="display: none;"></div>
            <div class="multi-step-action-buttons float-right">
                <button class="btn btn-secondary" style="font-weight: bold; font-family: Open Sans, sans-serif;">Previous</button>
                <button class="btn" style="background: #063D58; font-family: Open Sans, sans-serif; font-weight: Bold; color: whitesmoke;">Next</button>
                <button class="btn btn-primary" style="background: #063D58; font-family: Open Sans, sans-serif; font-weight: Bold; display:none;">Save</button>
            </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- 
<select name="" class="form-control" id="">
                                                        <option value="" selected hidden>Select Account</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{$account->Account}}">{{$account->Account}}</option>
                                                        @endforeach
                                                    </select> --}}
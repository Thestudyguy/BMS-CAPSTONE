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
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover table-bordered table-striped"></table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- Additional forms for other steps (hidden initially) --}}
                    <div class="multi-step-journal income" style="display: none;">Income form here</div>
                    <div class="multi-step-journal assets" style="display: none;">Assets form here</div>
                    <div class="multi-step-journal liability" style="display: none;">Liability form here</div>
                    <div class="multi-step-journal equity" style="display: none;">Equity form here</div>
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

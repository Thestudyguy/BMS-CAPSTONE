@extends('layout')

@section('content')
<div class="container-fluid p-5 my-5">
    <div class="container">
        <div class="row">
            <h6 class="h4 fw-bold">Chart of Accounts</h6>
            <div class="col-sm-8">
                <div class="card elevation-3">
                    <div class="card-header">
                    <div class="card-title">
                    <h6 class="fw-bold">Accounts</h6>
                    </div>
                    <div class="card-tools">
                    <button class="btn ml-3 fw-bold" data-bs-target='#new-COA' data-bs-toggle='modal'
                        style="background: #063D58; color: whitesmoke; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                        <i class="fas fa-plus text-sm"></i></button>
                    </div>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow: auto;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-select form-control rounded-0" name="" id="filter-coa">
                                    <option value="" selected hidden>Filter</option>
                                    <option value="Asset">Asset</option>
                                    <option value="Liability">Liability</option>
                                    <option value="Equity">Equity</option>
                                    <option value="clear">Clear Filter</option>
                                </select>
                            </div>
                            <input type="search" class="form-control search-coa" name="search-coa" id="search-coa"
                                placeholder="search...">
                        </div>
                        <table class="table table-hover table-bordered table-striped coa-table" id="coa-table">
                            <thead style="position: sticky;">
                                <tr>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1; font-size: 14px;">Account
                                        Name</th>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1; font-size: 14px;">Account
                                        Type</th>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1; font-size: 14px;">Account
                                        Category</th>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1; font-size: 14px;">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="font-size: .8em;">
                                        @foreach ($account as $accounts)
                                            <tr id="{{$accounts->id}}">
                                                <td>{{$accounts->AccountName}}</td>
                                                <td>{{$accounts->AccountType}}</td>
                                                <td>{{$accounts->Category}}</td>
                                            </tr>
                                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h6 class="fw-bold">Account Types</h6>
                        </div>
                        <div class="card-tools">
                            <button class="btn ml-3 fw-bold" data-bs-target='#new-acc-type' data-bs-toggle='modal'
                                style="background: #063D58; color: whitesmoke; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                                <i class="fas fa-plus text-sm"></i></button>
                        </div>
                    </div>

                    <div class="card-body" style="max-height: 400px; overflow: auto;">
                        <table class="table table-hovered table-bordered table-striped">
                            <tbody style="font-size: .6rem; font-family 'Open Sans', sans-serif;">
                                @foreach ($at as $ats)
                                    <tr id="{{$ats->id}}">
                                        <td>{{$ats->AccountType}}</td>
                                        <td>{{$ats->Category}}</td>
                                        <td>
                                            <span class="badge bg-warning p-1 rounded-1" style="font-size: .7rem;"><i class="fas fa-pen"></i></span>
                                            <span class="badge bg-warning p-1 rounded-1" style="font-size: .7rem;"><i class="fas fa-trash"></i></span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('modals.create-new-COA')
@include('modals.new-account-type-modal')
@endsection
@extends('layout')

@section('content')
<div class="container-fluid p-5 my-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h6 class="h4 fw-bold">Chart of Accounts</h6>
                <div class="coa-action-buttons">
                    <button class="btn ml-3 fw-bold"
                        data-bs-target='#new-COA' data-bs-toggle='modal'
                        style="background: #063D58; color: whitesmoke; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                        New Account <i class="fas fa-plus text-sm"></i></button>
                    <button class="btn ml-3 fw-bold"
                        data-bs-target='#new-acc-type' data-bs-toggle='modal'
                        style="background: #063D58; color: whitesmoke; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                        New Account Type <i class="fas fa-plus text-sm"></i></button>
                       
                </div>
                <div class="card elevation-3 p-3">
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
                            <input type="search" class="form-control search-coa" name="search-coa" id="search-coa" placeholder="search...">
                        </div>
                        <table class="table table-hover table-bordered table-striped coa-table" id="coa-table">
                            <thead style="position: sticky;">
                                <tr>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1;">Account Name</th>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1;">Account Type</th>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1;">Account Category</th>
                                    <th style="position: sticky; top: 0; background-color: white; z-index: 1;">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: .8em;">
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <h6 class="h4 fw-bold">Account Types</h6>
                <div class="card">
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('modals.create-new-COA')
    @include('modals.new-account-type-modal')
@endsection
{{-- @foreach ($ChartofAccounts as $coa)
                                  <tr id="{{$coa->id}}" data-category="{{$coa->Category}}">
                                    <td>{{$coa->Account}}</td>
                                    <td>{{$coa->AccountType}}</td>
                                    <td>{{$coa->Category}}</td>
                                    <td>
                                        <span id="edit-coa-{{$coa->id}}" class="badge bg-warning fw-bold text-light">
                                            <i class="fas fa-pen p-1" style="color:#063D58"></i>
                                        </span>
                                        <span id="remove-coa-{{$coa->id}}" class="badge bg-warning fw-bold text-light">
                                            <i class="fas fa-trash p-1" style="color:#063D58"></i>
                                        </span>
                                    </td>
                                  </tr>
                              @endforeach --}}
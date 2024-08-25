@extends('layout')
@section('content')
    <div class="container-fluid border-0 p-5">

      <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header rounded-0">Services</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header rounded-0">Activity Log</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header rounded-0">Financial Statements</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header rounded-0">Billing Statements</div>
                </div>
            </div>
        </div>
      </div>
       <div class="card card-collapsed">
        <div class="card-header">
            <div class="card-title">
                Bookkeeping
            </div>
            <div class="card-tools">
                <button class="btn btn-tools text-light" data-card-widget='collapse'>
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <td>Book Keeping Data</td>
                    </tr>
                </thead>
            </table>
            <tbody>
                <tr>
                    <td>Book Keeping Data</td>
                </tr>
            </tbody>
        </div>
       </div>
    </div>
@endsection

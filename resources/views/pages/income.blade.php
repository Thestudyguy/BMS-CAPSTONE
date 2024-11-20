@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="h4 fw-bold lead">Firm Income</h4>
            </div>
                <div>

            <div class="text-end">
                <button class="btn btn-link">
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-file-pdf"></i> Generate PDF
                    </span>
                </button>
            </div>
              

            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table">
                    <thead>
                        <tr>
                            <td>Client</td>
                            <td>Company Name</td>
                            <td>Date</td>
                            <td>Billing id</td>
                            <td>Total Income</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Cris</td>
                            <td>Dave Company</td>
                            <td>2024-11-20</td>
                            <td>UHWJAU</td>
                            <td>2,500</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

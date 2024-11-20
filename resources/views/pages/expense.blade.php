@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="h4 fw-bold lead">Firm Expense</h4>
            </div>

            <div class="text-end">
                <button class="btn btn-link">
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-file-pdf"></i> Generate PDF
                    </span>
                </button>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table">
                    <thead>
                        <tr>
                            <td>Journal ID</td>
                            <td>Date</td>
                            <td>Total Expense</td>
                        </tr>
                    </thead>
                    <tbody>
                            <td>UHWJAU</td>
                            <td>2024-11-20</td>
                            <td>1,000,000</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <span class="fw-bold text-lg text-light">Firm Expense</span>
                <div class="card-tools"><button class="btn bg-transparent fw-bold text-light"><i class="fas fa-file"></i></button></div>
            </div>
                <div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>Journal ID</td>
                            <td>Date</td>
                            <td>Total Expense</td>
                        </tr>
                    </thead>
                    <tbody>
                           @foreach ($expenses as $items)
                               <tr>
                                <td>{{$items->journal_id}}</td>
                                <td>{{ \Carbon\Carbon::parse($items->created_at)->format('F d, Y \a\t h:i A') }}</td>
                                <td>{{number_format($items->total_expense, 2)}}</td>
                               </tr>
                           @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
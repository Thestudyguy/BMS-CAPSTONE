@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <span class="fw-bold text-lg text-light">Billings</span>
                <div class="card-tools"><button class="btn bg-transparent gen-expense-pdf fw-bold text-light"><i class="fas fa-file"></i></button></div>
            </div>
                <div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>Billing ID</td>
                            <td>Client</td>
                            <td>Date</td>
                            <td>Action</td>
                            {{-- <td>Total Expense</td> --}}
                        </tr>
                    </thead>
                    <tbody>
                           @foreach ($billing as $items)
                               <tr>
                                <td>{{$items->billing_id}}</td>
                                <td>{{$items->CEO}} - {{$items->CompanyName}}</td>
                                <td>{{ \Carbon\Carbon::parse($items->created_at)->format('F d, Y \a\t h:i A') }}</td>
                                <td></td>
                            </tr>
                           @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
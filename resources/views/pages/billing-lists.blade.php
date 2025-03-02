@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <span class="fw-bold text-lg text-light">Billings</span>
                <div class="card-tools"><button class="btn bg-transparent gen-billing-pdf fw-bold text-light"><i class="fas fa-print"></i></button></div>
            </div>
                <div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <td class="fw-bold">Billing ID</td>
                            <td class="fw-bold">Client</td>
                            <td class="fw-bold">Date</td>
                            {{-- <td>Action</td> --}}
                            {{-- <td>Total Expense</td> --}}
                        </tr>
                    </thead>
                    <tbody>
                           @foreach ($billing as $items)
                               <tr>
                                <td class="text-sm">{{$items->billing_id}}</td>
                                <td class="text-sm">{{$items->CEO}} - {{$items->CompanyName}}</td>
                                <td class="text-sm">{{ \Carbon\Carbon::parse($items->created_at)->format('F d, Y \a\t h:i A') }}</td>
                                {{-- <td></td> --}}
                            </tr>
                           @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <span class="fw-bold text-lg text-light">Firm Income</span>
                <div class="card-tools"><button class="btn bg-transparent income-gen-pdf fw-bold text-light"><i class="fas fa-file"></i></button></div>
            </div>
                <div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
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
                        
                        @foreach ($incomeData as $income)
                        @php
                            $totalIncome = $income->amount + $income->addedAmount;
                        @endphp
                            <tr>
                                <td>{{$income->CEO}}</td>
                                <td>{{$income->CompanyName}}</td>
                                <td>{{ \Carbon\Carbon::parse($income->created_at)->format('F d, Y \a\t h:i A') }}</td>
                                <td class="fw-bold">{{$income->billing_id}}</td>
                                <td>{{number_format($totalIncome, 2)}}</td>
                            </tr>
                        @endforeach
                        {{-- <tr>
                            <td>Cris</td>
                            <td>Dave Company</td>
                            <td>2024-11-20</td>
                            <td>UHWJAU</td>
                            <td>2,500</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
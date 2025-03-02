@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="row">
            <div class="col-sm-12 text-sm">
                <div class="float-left ml-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Client Services</li>
                        </ol>
                </div>
            </div>
            <div class="fw-bold p-3" style="color: rgb(6,61,88);">{{$client->CEO}} - {{$client->CompanyName}}</div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title fw-bold mt-2">Billings</div>
                        <div class="card-tools">
                            <button class="btn btn-transparent text-light" id="{{$client->id}}" onclick="window.location.href='{{ route('generate-client-billing', ['id' => $client->id]) }}'"><i class="fas fa-plus"></i></button>
                            <button class="btn btn-transparent text-light generate-client-billing-pdf" id="{{$client->id}}" title="generate-pdf"><i class="fas fa-print"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover client-billing-table">
                            <thead>
                                <tr>
                                    <td>Billing #ID</td>
                                    <td>Due Date</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($clientBilling->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center">No billings available.</td>
                                </tr>
                            @else
                                @foreach ($clientBilling as $billing)
                                    <tr id="{{ $billing->id }}">
                                        <td>{{ $billing->billing_id }}</td>
                                        <td>{{ $billing->due_date }}</td>
                                        <td>
                                            <span class="badge bg-warning fw-bold" style="font-size: 0.8rem;" ><i class="fas fa-trash"></i></span>
                                            <span class="badge bg-warning fw-bold view-client-billing" id="{{$billing->billing_id}}_{{$client->id}}" onclick="window.location.href='{{ route('view-client-billing', ['client_id' => $client->id, 'billing_id' => $billing->billing_id]) }}'" style="font-size: 0.8rem;" ><i class="fas fa-eye"></i></span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-8">
                <div class="lead" style="font-size: 18px;">click a billing to view data</div>
            </div> --}}
            {{-- <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>Test</td>
                                    <td>Test</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
    @endsection

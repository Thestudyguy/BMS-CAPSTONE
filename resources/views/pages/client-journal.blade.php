@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="lead fw-bold mb-3 p-2" style="font-size: 1.25rem;">
            {{ $client->CEO }} - {{ $client->CompanyName }}
        </div>
        <div class="row">
            <div class="col">
                {{-- <div class="card mb-4">
                    <div class="card-header fw-bold" style="font-size: 1rem;">Charts of Accounts</div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size: 0.9rem;">Account Name</th>
                                    <th style="font-size: 0.9rem;">Account Type</th>
                                    <th style="font-size: 0.9rem;">Balance</th>
                                    <th style="font-size: 0.9rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-size: 0.9rem;">Cash</td>
                                    <td style="font-size: 0.9rem;">Asset</td>
                                    <td style="font-size: 0.9rem;">1,000</td>
                                    <td>
                                        <span class="badge bg-warning text-dark" style="font-size: 0.8rem;">remove</span>
                                    </td>
                                </tr>
                                <!-- More rows can be added here -->
                            </tbody>
                        </table>
                    </div>
                </div> --}}

                <div class="card">
                    <div class="card-header fw-bold" style="font-size: 1rem;">
                        Journal Entries
                        <div class="card-tools float-right">
                            <button class="btn btn-transparent text-light" id="{{$client->id}}" onclick="window.location.href='{{ route('client-journal-form', ['id' => $client->id]) }}'"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size: 0.9rem;">Date</th>
                                    @if (Auth::user()->Role === 'Admin')
                                    <td style="font-size: 0.9rem;">Journal ID</td>
                                    @endif
                                    {{-- <th style="font-size: 0.9rem;">Debit</th> --}}
                                    {{-- <th style="font-size: 0.9rem;">Credit</th> --}}
                                    <th style="font-size: 0.9rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($journals as $journal)
                                <tr>
                                    @php
                                        $preparedDate = explode('-', $journal->start_date);
                                    @endphp
                                    <td style="font-size: 0.9rem;">{{$preparedDate[0]}}</td>
                                    @if (Auth::user()->Role === 'Admin')
                                    <td style="font-size: 0.9rem;">{{$journal->journal_id}}</td>
                                    @endif
                                    <td>
                                        <span class="badge bg-warning text-dark" style="font-size: 0.8rem;" id="{{$client->id}}_{{$journal->journal_id}}"><i class="fas fa-trash" style="color: #063d58"></i></span>
                                        <span class="badge bg-warning text-dark view-journal-btn" style="font-size: 0.8rem;" id="{{$client->id}}_{{$journal->journal_id}}"><i class="fas fa-eye" style="color: #063d58"></i></span>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- More rows can be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- <div class="col-sm-4 pt-5">
                <div class="card pt-1">
                    <div class="card-header fw-bold" style="font-size: 1rem;">Summary</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 0.9rem;">
                                Total Assets
                                <span class="badge bg-primary rounded-pill">10,000</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 0.9rem;">
                                Total Liabilities
                                <span class="badge bg-danger rounded-pill">5,000</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 0.9rem;">
                                Equity
                                <span class="badge bg-success rounded-pill">5,000</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

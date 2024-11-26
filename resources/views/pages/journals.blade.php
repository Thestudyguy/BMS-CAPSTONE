@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="h4 fw-bold lead">Client Journals</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table">
                    <thead>
                        <tr>
                            <td>Client</td>
                            <td>Journal ID</td>
                            <td>Journal Status</td>
                            <td>Created By</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($journals as $journal)
                            <tr id="{{$journal->id}}">
                                <td>{{$journal->CEO}}, {{$journal->CompanyName}}</td>
                                <td class="fw-bold">{{$journal->journal_id}}</td>
                                <td><span class="badge 
                                    @if($journal->JournalStatus === 'Rejected') badge-danger
                                    @elseif($journal->JournalStatus === 'Canceled') badge-warning
                                    @elseif($journal->JournalStatus === 'Approved') badge-success
                                    @else badge-secondary
                                    @endif" id="{{$journal->id}}_{{$journal->journal_id}}"
                                    @if ($journal->JournalStatus !== 'Approved')
                                        data-bs-target='#update-journal-status-{{$journal->journal_id}}' 
                                        data-bs-toggle='modal'
                                    @endif>
                                    {{$journal->JournalStatus}}
                                </span></td>
                                <td>{{$journal->LastName}}, {{$journal->FirstName}} - {{$journal->Role}}</td>
                                <td>
                                    {{-- @if ($journal->JournalStatus !== 'Approved')
                                    <span class="badge fw-bold bg-warning text-dark" id="{{$journal->client_id}}_{{$journal->journal_id}}"><i class="fas fa-pen"></i></span>
                                    @endif --}}
                                    <span class="badge fw-bold bg-warning text-dark audit-journal" 
                                    id="{{$journal->client_id}}_{{$journal->journal_id}}"
                                    onclick="window.location.href='{{ route('journal-audit', ['id' => $journal->journal_id]) }}'"
                                    >
                                  <i class="fas fa-pen"></i>
                              </span>
                                    <span class="badge fw-bold bg-warning text-dark" id="{{$journal->client_id}}_{{$journal->journal_id}}" data-bs-target="#remove-journal-entry-{{$journal->journal_id}}" data-bs-toggle='modal'><i class="fas fa-trash"></i></span>
                                    <span class="badge fw-bold bg-warning text-dark view-journal-btn" id="{{$journal->client_id}}_{{$journal->journal_id}}"><i class="fas fa-eye"></i></span>
                                </td>
                            </tr>

                            @include('modals.remove-journal-entry')
                            @include('modals.update-journal-status')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

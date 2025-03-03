@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        
        <!-- Approved Journals -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h4 class="h4 fw-bold lead text-success">Approved Journals</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table" style=" max-height: 400px;overflow-y: auto;">
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
                        @foreach ($journals->where('JournalStatus', 'Approved') as $journal)
                            <tr id="{{ $journal->id }}">
                                <td>{{ $journal->CEO }}, {{ $journal->CompanyName }}</td>
                                <td class="fw-bold">{{ $journal->journal_id }}</td>
                                <td>
                                    @if (Auth::check() && Auth::user()->Role !== 'Bookkeeper')
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold text-success" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#update-journal-status-{{ $journal->journal_id }}" 
                                              data-bs-toggle="modal">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @else
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold text-secondary" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $journal->LastName }}, {{ $journal->FirstName }} - {{ $journal->Role }}</td>
                                <td>
                                    @if (Auth::check() && (Auth::user()->Role === 'Admin' || Auth::user()->Role === 'Bookkeeper'))
                                        <span class="badge fw-bold bg-warning text-dark audit-journal" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              onclick="window.location.href='{{ route('journal-audit', ['id' => $journal->journal_id]) }}'">
                                            <i class="fas fa-pen"></i>
                                        </span>
                                    @endif
                                    <span class="badge fw-bold bg-warning text-dark" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                          data-bs-target="#remove-journal-entry-{{ $journal->journal_id }}" 
                                          data-bs-toggle="modal">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="badge fw-bold bg-warning text-dark view-journal-btn" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                    {{-- @if ($journal->note) --}}
                                        <span class="badge fw-bold bg-warning text-dark" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#journal-note-{{ $journal->id }}" 
                                              data-bs-toggle="modal">
                                            <i class="fas fa-book"></i>
                                        </span>
                                    {{-- @endif --}}
                                </td>
                            </tr>
                            @include('modals.view-journal-note')
                            @include('modals.remove-journal-entry')
                            @include('modals.update-journal-status')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Journals -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h4 class="h4 fw-bold lead text-info">Pending Journals</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table"  style=" max-height: 400px;overflow-y: auto;">
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
                        @foreach ($journals->where('JournalStatus', 'Pending') as $journal)
                            <tr id="{{ $journal->id }}">
                                <td>{{ $journal->CEO }}, {{ $journal->CompanyName }}</td>
                                <td class="fw-bold">{{ $journal->journal_id }}</td>
                                <td>
                                    @if (Auth::check() && Auth::user()->Role !== 'Bookkeeper')
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold badge-secondary" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#update-journal-status-{{ $journal->journal_id }}" 
                                              data-bs-toggle="modal">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @else
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold badge-secondary" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $journal->LastName }}, {{ $journal->FirstName }} - {{ $journal->Role }}</td>
                                <td>
                                    @if (Auth::check() && (Auth::user()->Role === 'Admin' || Auth::user()->Role === 'Bookkeeper'))
                                        <span class="badge fw-bold bg-warning text-dark audit-journal" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              onclick="window.location.href='{{ route('journal-audit', ['id' => $journal->journal_id]) }}'">
                                            <i class="fas fa-pen"></i>
                                        </span>
                                    @endif
                                    <span class="badge fw-bold bg-warning text-dark" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                          data-bs-target="#remove-journal-entry-{{ $journal->journal_id }}" 
                                          data-bs-toggle="modal">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="badge fw-bold bg-warning text-dark view-journal-btn" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                    @if ($journal->note)
                                        <span class="badge fw-bold bg-warning text-dark" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#journal-note-{{ $journal->id }}" 
                                              data-bs-toggle="modal">
                                            <i class="fas fa-book"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @include('modals.view-journal-note')
                            @include('modals.remove-journal-entry')
                            @include('modals.update-journal-status')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cancelled Journals -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h4 class="h4 fw-bold lead text-warning">Cancelled Journals</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table"  style=" max-height: 400px;overflow-y: auto;">
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
                        @foreach ($journals->where('JournalStatus', 'Canceled') as $journal)
                            <tr id="{{ $journal->id }}">
                                <td>{{ $journal->CEO }}, {{ $journal->CompanyName }}</td>
                                <td class="fw-bold">{{ $journal->journal_id }}</td>
                                <td>
                                    @if (Auth::check() && Auth::user()->Role !== 'Bookkeeper')
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold text-warning" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#update-journal-status-{{ $journal->journal_id }}" 
                                              data-bs-toggle="modal">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @else
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold text-secondary" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $journal->LastName }}, {{ $journal->FirstName }} - {{ $journal->Role }}</td>
                                <td>
                                    @if (Auth::check() && (Auth::user()->Role === 'Admin' || Auth::user()->Role === 'Bookkeeper'))
                                        <span class="badge fw-bold bg-warning text-dark audit-journal" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              onclick="window.location.href='{{ route('journal-audit', ['id' => $journal->journal_id]) }}'">
                                            <i class="fas fa-pen"></i>
                                        </span>
                                    @endif
                                    <span class="badge fw-bold bg-warning text-dark" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                          data-bs-target="#remove-journal-entry-{{ $journal->journal_id }}" 
                                          data-bs-toggle="modal">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="badge fw-bold bg-warning text-dark view-journal-btn" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                    @if ($journal->note)
                                        <span class="badge fw-bold bg-warning text-dark" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#journal-note-{{ $journal->id }}" 
                                              data-bs-toggle="modal">
                                            <i class="fas fa-book"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @include('modals.view-journal-note')
                            @include('modals.remove-journal-entry')
                            @include('modals.update-journal-status')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rejected Journals -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h4 class="h4 fw-bold lead text-danger">Rejected Journals</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped accountant-journal-table"  style=" max-height: 400px;overflow-y: auto;">
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
                        @foreach ($journals->where('JournalStatus', 'Rejected') as $journal)
                            <tr id="{{ $journal->id }}">
                                <td>{{ $journal->CEO }}, {{ $journal->CompanyName }}</td>
                                <td class="fw-bold">{{ $journal->journal_id }}</td>
                                <td>
                                    @if (Auth::check() && Auth::user()->Role !== 'Bookkeeper')
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold text-danger" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#update-journal-status-{{ $journal->journal_id }}" 
                                              data-bs-toggle="modal">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @else
                                        <span style="font-size: 12px;" 
                                              class="badge fw-bold text-secondary" 
                                              id="{{ $journal->id }}_{{ $journal->journal_id }}">
                                            {{ $journal->JournalStatus }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $journal->LastName }}, {{ $journal->FirstName }} - {{ $journal->Role }}</td>
                                <td>
                                    @if (Auth::check() && (Auth::user()->Role === 'Admin' || Auth::user()->Role === 'Bookkeeper'))
                                        <span class="badge fw-bold bg-warning text-dark audit-journal" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              onclick="window.location.href='{{ route('journal-audit', ['id' => $journal->journal_id]) }}'">
                                            <i class="fas fa-pen"></i>
                                        </span>
                                    @endif
                                    <span class="badge fw-bold bg-warning text-dark" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                          data-bs-target="#remove-journal-entry-{{ $journal->journal_id }}" 
                                          data-bs-toggle="modal">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="badge fw-bold bg-warning text-dark view-journal-btn" 
                                          id="{{ $journal->client_id }}_{{ $journal->journal_id }}">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                    @if ($journal->note)
                                        <span class="badge fw-bold bg-warning text-dark" 
                                              id="{{ $journal->client_id }}_{{ $journal->journal_id }}" 
                                              data-bs-target="#journal-note-{{ $journal->id }}" 
                                              data-bs-toggle="modal">
                                            <i class="fas fa-book"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @include('modals.view-journal-note')
                            @include('modals.remove-journal-entry')
                            @include('modals.update-journal-status')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
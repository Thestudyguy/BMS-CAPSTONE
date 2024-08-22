@extends('layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mt-5">
            <div class="card-header clients-table-data">
                <div class="card-title lead fw-bold">Clients</div>
                <div class="card-tools">
                    <button class="btn rounded-0 new-client-button" data-bs-target="#new-client-modal"
                        data-bs-toggle="modal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="max-height: 200px; overflow-x: auto;">
                <table class="table table-default table-hover text-sm opacity-50 fw-bold">
                    <tbody>
                        <tr class="client-table-data" style="cursor: pointer;" data-widget="expandable-table"
                            aria-expanded="false">
                            <td>
                                Client Data
                                <span class="visually-hidden action-icons float-right mx-1 text-sm"
                                    data-bs-target="#remove-client-modal" data-bs-toggle="modal">
                                    <i class="fas fa-trash"></i>
                                </span>
                                <span class="visually-hidden action-icons float-right mx-1 text-sm">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </td>
                        </tr>
                        <tr class="expandable-body cheque-expandable-body bg-light">
                            <td>
                                <div class="p-0 text-center expandable-body-append-table">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr data-widget="expandable-table">
                                                <td>Services Availed</td>
                                            </tr>
                                            <tr data-widget="expandable-table">
                                                <td>Accounting</td>
                                            </tr>
                                            <tr data-widget="expandable-table">
                                                <td>Billing Statements</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @include('modals.remove-selected-client')
            @include('modals.new-client-modal')
        </div>

        <div class="card">
            <div class="card-header client-details-header">
                Client Details
            </div>
            <div class="card-body">
                <span><i class="text-sm">Click a Client to see Details</i></span>
                <div class="row visually-hidden">
                    <div class="col-sm-12">
                        {{-- client information --}}
                        <table class="table opacity-75 table-hover">
                            {{-- client data --}}
                        </table>
                    </div>
                    <div class="col-sm-6">
                        {{-- client representative --}}
                        <table class="table opacity-75 table-hover">
                            {{-- client representative data --}}
                        </table>
                    </div>
                    <div class="col-sm-6">
                        {{-- client active service availed & documents progress --}}
                        <table class="table opacity-75 table-hover">
                            {{-- client service & progress data --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

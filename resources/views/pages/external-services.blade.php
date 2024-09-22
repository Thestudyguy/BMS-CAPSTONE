@extends('layout')

@section('content')
    <div class="container-fluid mt-5 pt-5 external-services">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">External Services</div>
                            <div class="card-tools">
                                <button class="btn new-client-modal text-light" data-bs-target='#new-service-modal' data-bs-toggle='modal'>
                                    <i class="fas fa-plus text-light"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 600px; overflow-x: auto;">
                            <table class="table table-hover opacity-50  external-services">
                                @foreach ($services as $service)
                                    <tr id="{{$service->id}}" class="external-service" data-widget="expandable-table" aria-expanded="false">
                                        <td>
                                            {{$service->Service}} <b>|</b> {{number_format($service->Price, 2)}}
                                            <span class="float-right px-1 action-icons new-sub-service-icon visually-hidden text-sm" id="{{$service->id}}" data-bs-target='#sub-service-{{$service->id}}' data-bs-toggle="modal" title="create new sub service/requirement for {{$service->Service}}">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            <span class="float-right px-1 action-icons edit-service-icon visually-hidden text-sm" id="{{$service->id}}" data-bs-target='#edit-service-modal-{{$service->id}}' data-bs-toggle="modal" title="edit {{$service->Service}}">
                                                <i class="fas fa-pen"></i>
                                            </span>
                                            <span class="float-right text-sm remove-service-icon action-icons visually-hidden" id="{{$service->id}}" data-bs-target='#remove-service-modal-{{$service->id}}' data-bs-toggle="modal" title="remove {{$service->Service}}">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body cheque-expandable-body bg-light">
                                        <td>
                                            <div class="p-0 text-center expandable-body-append-table">
                                                <center><div class="loader visually-hidden"></div></center>
                                                <table class="table table-hover float-left append-sub-services-{{$service->id}}">
                                                    {{-- <thead class="text-left">
                                                        <tr>
                                                            <td>Pre Requisite</td>
                                                            <td>Requirements</td>
                                                            <td>Price</td>
                                                        </tr>
                                                    </thead> 
                                                    <tbody class="text-left">
                                                            <tr>
                                                                <td>Community Tax Cedula</td>
                                                                <td>Business Tax Cedula</td>
                                                                <td>250P</td>
                                                            </tr>
                                                    </tbody> --}}
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @include('modals.sub-service-modal')
                                @include('modals.edit-service-modal')
                                @include('modals.remove-service-modal')
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            Service Details
                            <div class="card-tools">
                                <button class="btn service-detail-expand-button" data-card-widget='collapse'><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body"></div>
                    </div>
                </div> --}}
            </div>
            @include('modals.new-service-modal')
            @include('modals.remove-sub-service-modal')
    </div>
    @endsection

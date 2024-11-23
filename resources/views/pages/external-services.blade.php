@extends('layout')

@section('content')
    <div class="container-fluid mt-5 pt-5 external-services">
        <div class="row">
            <div class="col-sm-12 text-sm">
                <div class="float-left ml-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Services</li>
                        </ol>
                </div>
            </div>
        </div>
        <div class="container p-2">
            {{-- <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text lead">Clients</span>
                      <span class="info-box-number">1</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-edit"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Bookkeping Sales</span>
                      <span class="info-box-number">410</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">External Services Sales</span>
                      <span class="info-box-number">13,648</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="ion ion-stats-bars"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Sales</span>
                      <span class="info-box-number">93,139</span>
                    </div>
                  </div>
                </div>
              </div> --}}
                <div class="row">
                    <div class="col-sm-12">
                        {{-- <button class="btn btn-primary">Descriptions</button> --}}
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Services</div>
                                <div class="card-tools">
                                    <button class="btn text-light pdf-services" data-url="{{ route('services/pdf') }}">
                                        <i class="fas fa-file text-light"></i>
                                    </button>
                                    {{-- <button class="btn text-light pdf-services" onclick="location.href='{{ route('services/pdf') }}'"><i class="fas fa-file text-light"></i></button> --}}
                                    <button class="btn new-client-modal text-light" data-bs-target='#new-service-modal' data-bs-toggle='modal'>
                                        <i class="fas fa-plus text-light"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="max-height: 600px; overflow-x: auto;">
                                <table class="table table-hover  external-services">
                                    @foreach ($services as $service)
                                        <tr id="{{$service->id}}" class="external-service" data-widget="expandable-table" aria-expanded="false">
                                            <td>
                                                {{$service->Service}} <b>|</b> {{number_format($service->Price, 2)}} - {{$service->Category}}
                                                <span class="badge bg-warning text-sm float-right action-icons new-sub-service-icon text-sm" id="{{$service->id}}" data-bs-target='#sub-service-{{$service->id}}' data-bs-toggle="modal" title="create new sub service/requirement for {{$service->Service}}">
                                                    <i style="font-size: .8em;" class="fas fa-plus"></i>
                                                </span>
                                                <span class="badge bg-warning text-sm mx-2 float-right action-icons edit-service-icon text-sm" id="{{$service->id}}" data-bs-target='#edit-service-modal-{{$service->id}}' data-bs-toggle="modal" title="edit {{$service->Service}}">
                                                    <i style="font-size: .8em;" class="fas fa-pen"></i>
                                                </span>
                                                <span class="badge bg-warning text-sm float-right text-sm remove-service-icon action-icons" id="{{$service->id}}" data-bs-target='#remove-service-modal-{{$service->id}}' data-bs-toggle="modal" title="remove {{$service->Service}}">
                                                    <i style="font-size: .8em;" class="fas fa-trash"></i>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="expandable-body cheque-expandable-body bg-light">
                                            <td>
                                                <div class="p-0 text-center expandable-body-append-table">
                                                    <center><div class="loader visually-hidden"></div></center>
                                                    <table class="table table-hover float-left append-sub-services-{{$service->id}}">
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @include('modals.sub-service-modal')
                                    @include('modals.edit-service-modal')
                                    @include('modals.edit-sub-service-modal')
                                    @include('modals.remove-service-modal')
                                    @endforeach
                                </table>
                                @include('modals.remove-sub-service')
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-4 mt-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Descriptions</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-striped table-bordered"></table>
                        </div>
                    </div> --}}
                </div>
                @include('modals.new-service-modal')
                @include('modals.remove-sub-service-modal')
        </div>
    </div>
    @endsection
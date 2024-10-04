@extends('layout')
@section('content')
<div class="container-fluid client-form p-5">
    <div class="container p-5 w-100">
      <h4 class="h6 fw-bold lead">Add Service <b>|</b> {{$client->CompanyName}}</h4>
      <div class="row">
        <span class="hidden-client-id" hidden id="{{$client->id}}"></span>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card elevation-1">
                        <h4 class="h6 fw-bold p-4">Services</h4>
                        <div class="card-body d-flex flex-column">
                            <ul style="list-style: none;">
                                @foreach ($services as $service)
                                <li><input class="services" type="radio" value="{{$service->Service}}-{{$service->Price}}" name="Service" id="{{$service->id}}">{{$service->Service}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12 sub-services">
                    <div class="card elevation-1">
                        <h4 class="h6 fw-bold p-4">Sub Services</h4>
                        <div class="card-body">
                           <center> <div class="loader visually-hidden"></div></center>
                           {{-- sub services here --}}
                            <ul style="list-style: none;">
                                <div class="sub-services-append"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card elevation-1">
                <h4 class="h6 fw-bold p-4">Service Details</h4>
                <div class="card-body">
                   <div class="service-input">
                   </div>
                </div>
                <div class="card-footer services-total" style="font-size: 14px; display:none;">
                    <span class="totalAmount"></span>
                    <span class="totalServices"></span>
                    <div class="save-services float-right"><button class="btn btn-primary fw-bold text-sm btn-md text-light submit-services">Save Services</button></div>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-12 m-2">
            <div class="card">
                <span class="fw-bold mt-4 ml-4">Selected Services</span>
                <div class="card-body">
                    <table class="table table-bordered">
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="float-right mt-3">
        <button class="btn btn-primary fw-bold text-light">Save</button>
      </div> --}}
    </div>
</div>

@endsection
@extends('layout')
@section('content')
<div class="container-fluid client-form p-5" style="height: 100vh;">
    <div class="container p-5 w-100">
      <h4 class="h6 fw-bold lead">Add Services</h4>
      <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card elevation-1">
                        <h4 class="h6 fw-bold p-4">Services</h4>
                        <div class="card-body">
                            @foreach ($services as $service)
                                <span class="fw-bold text-sm">{{$service->Service}}</span><br>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card elevation-1">
                        <h4 class="h6 fw-bold p-4">Sub Services</h4>
                        <div class="card-body">
                           {{-- sub services here --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card elevation-1 h-100">
                <h4 class="h6 fw-bold p-4">Service Details</h4>
                <div class="card-body"></div>
            </div>
        </div>
      </div>
      <div class="float-right mt-3">
        <button class="btn btn-primary">Save</button>
      </div>
    </div>
</div>

@endsection

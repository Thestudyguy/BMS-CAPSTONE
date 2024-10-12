@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="container">
            <button class="btn text-light fw-bold m-3" style="background: #063D58;">Billing Statements</button>
            <div class="card border border-dark rounded-1">
                <div class="row g-0">
                    <div class="col-sm-4">
                        <center class="float-right mr-5 "><img class="brand-image" src="{{ asset('images/Rams_logo.png') }}"
                                alt="" width="130"></center>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-12 mt-4">
                                <div class="fw-bold h1 billing-title" style="color: #063D58;">RAM'S ACCOUNTING FIRM</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row ram-creds">
                                    @foreach ($systemProfile as $sp)
                                    <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-phone"></i> {{$sp->PhoneNumber}} </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-envelope"></i>
                                            {{$sp->Email}}</span> 
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-map-marker"></i> {{$sp->Address}}</span>
                                    </div>
                                    @endforeach
                                    {{-- <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-phone"></i> 09550072587 </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-envelope"></i>
                                            rams.bookkeeping22@gmail.com</span> <!-- Fixed missing @ in email -->
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-map-marker"></i> Purok Narra, Briz
                                            District, Magugpo East Tagum City</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mx-5 elevation-0 m-5 client-billing-info">
                    <div class="card-body" style="background: #eff7fe;">
                        Billed to: <span class="fw-bold" style="color: #063D58;">{{$client->CompanyName}}</span> <h1 class="float-right fw-bold text-warning text-uppercase">BILLING STATEMENT</h1><br>
                        <select name="services" id="">
                            <option value="" selected hidden>Select Services</option>
                            @foreach ($services as $service)
                                <option value="{{$service->id}}">{{$service->Service}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endsection

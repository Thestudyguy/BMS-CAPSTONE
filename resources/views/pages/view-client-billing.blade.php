@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="container">
            {{-- <span class="badge bg-warning text-dark rounded-0 p-2 fw-bold"><span class="i fas fa-print"></span></span> --}}
                <button class="btn fw-bold  text-light rounded-1 gen-client-billing-pdf" style="background: #063D58" id="{{$billing->billing_id}}"><i class="fas fa-print"></i></button>
            <div class="card border border-dark rounded-1">
                <div class="row g-0">
                    <div class="col-sm-4">
                        <center class="float-right mr-5 "><img class="brand-image" src="{{ asset('images/Rams_logo.png') }}" alt="" width="130"></center>
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
                                        <span class="text-dark"><i class="fas fa-envelope"></i> {{$sp->Email}}</span> 
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-dark"><i class="fas fa-map-marker"></i> {{$sp->Address}}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mx-5 elevation-0 m-5 client-billing-info">
                    <div class="card-body" style="background: #eff7fe;">
                        Billed to: <span class="fw-bold" style="color: #063D58;">{{$client->CEO}} - {{$client->CompanyName}} <span class="bage fw-bold text-light"><i class="ion ion-clipboard"></i></span></span> 
                        <h1 class="float-right fw-bold text-warning text-uppercase">BILLING STATEMENT</h1><br>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">Due Date: {{$billing->due_date}}</div>
                                <div class="col-sm-4">Date: <span class="date">{{$billing->created_at}}</span></div>
                                <div class="col-sm-4" style="font-size: .9em;">Billing ID: {{$billing->billing_id}} <div id="span" class="billing-id"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="background: #eff7fe;">
                            <table class="table table-bordered client-billing">
                                <thead>
                                    <tr>
                                        <td colspan="10">Services</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                        $adTotal = 0;
                                    @endphp
                                    @foreach ($clientBilling as $services)
                                        <tr>
                                            <td colspan="5">{{$services['service_name']}}</td>
                                            <td colspan="2"></td>
                                            <td colspan="2">{{$services['service_price']}}</td>
                                            @php
                                                $total += $services['service_price'];
                                            @endphp
                                            @foreach ($services['sub_services'] as $sub_services)
                                            @php
                                                $total += $sub_services['sub_service_price'];
                                            @endphp    
                                            <tr>
                                                <td colspan="5"></td>
                                                <td colspan="">{{ $sub_services['sub_service_name'] }}</td>
                                                <td colspan="1">{{ $sub_services['sub_service_price'] }}</td>
                                                <td colspan="1"></td>
                                                @foreach ($sub_services['account_descriptions'] as $ads)
                                                <tr>
                                                <td colspan="6"></td>
                                                <td>{{ $ads->account_description }}</td>
                                                    <td>{{ $ads->account_price }}</td>
                                                </tr>
                                                @php
                                                $total += $ads->account_price;
                                                @endphp   
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    {{-- @foreach ($clientBilling as $service)
                                        <!-- Service Row -->
                                        <tr>
                                            <td rowspan="{{ count($service['sub_services']) + 1 }}">{{ $service['service_name'] }}</td>
                                            <td rowspan="{{ count($service['sub_services']) + 1 }}">{{ $service['service_price'] }}</td>
                                        </tr>
                                        <!-- Sub-Services -->
                                        @foreach ($service['sub_services'] as $subService)
                                            <tr>
                                                <td>{{ $subService['sub_service_name'] }}</td>
                                                <td>{{ $subService['sub_service_price'] }}</td>
                                                <td colspan="2">
                                                    @if (count($subService['account_descriptions']) > 0)
                                                        <table class="table mb-0">
                                                            @foreach ($subService['account_descriptions'] as $account)
                                                                <tr>
                                                                    <td>{{ $account->account_description }}</td>
                                                                    <td>{{ $account->account_price }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    @else
                                                        <i>No accounts available</i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach --}}
                                </tbody>
                            </table>
                            
                               <span class="float-right fw-bold text-dark" style="font-size: .8em;">Total: ₱<span class="total-printed-price">{{number_format($total)}}</span></span>
                        </div>
                    </div>
                    {{-- span.badge.fw-bold> --}}
                    <div class="card">
                        <div class="card-header" style="background: #eff7fe; border-bottom: none;">
                            <span class="fw-bold text-sm text-dark">Additional Descriptions</span>
                            {{-- <span data-bs-target='#adbd' data-bs-toggle='modal' class="badge fw-bold" style="background: #063D58;">
                                <i class="fas fa-plus" style="font-size: .7em;"></i>
                            </span> --}}
                        </div>
                        <div class="card-body" style="background: #eff7fe;">
                            <table class="table table-striped table-hover table-bordered additional-desc-table" id="selected-descriptions-table">
                                <thead>
                                    <tr>
                                        <td>Description</td>
                                        <td>Price</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($addedDescription as $ad)
                                        <tr>
                                            <td>{{$ad->account}}</td>
                                            <td>{{number_format($ad->amount)}}</td>
                                            @php
                                                $adTotal += $ad->amount;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="fw-bold float-right" style="font-size: 12px;">Sub Total: ₱<span class="fw-bold total" id="additional-description-subtotal">{{number_format($adTotal)}}</span></span>
                        </div>
                    </div>
                    <div class="float-right">
                    <span class="fw-bold float-right" style="font-size: 16px;">Overall Total: ₱<span class="fw-bold overall-due">{{number_format($total + $adTotal)}}</span></span>
                    </div>
                    <div class="card rounded-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        Prepared By: <span class="fw-bold text-uppercase">THERESA B. ELLOREN, LPT</span>
                                    <br>Collection in charge
                                    </div><br>
                                    <div class="col-sm-12">
                                        Collected By: <span class="fw-bold text-uppercase">REYMAR A. VILLEGAS</span>
                                    <br>Collection in charge
                                    </div>
                                </div>
                                <div class="col-sm-6 float-right">
                                   <div class="float-right">
                                    Approved By: <span class="fw-bold text-uppercase">RYAN T. RAMAL</span>
                                    <br>RAM'S ACCOUNTING FIRM
                                    <br><span class="text-danger">Proprietor</span>
                                    345-980-034-000 <br>
                                    Purok Narra, Briz District, <br>
                                    Magugpo East Tagum City <br>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
<script>
    
</script>
@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="container">
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
                                <div class="col-sm-4">Due Date: <input type="date" class="form-control w-50" name="due-date" id="dd"></div>
                                <div class="col-sm-4">Date: <span class="date">{{$currentDate}}</span></div>
                                <div class="col-sm-4" style="font-size: .9em;"><div id="span" class="billing-id">Billing ID: {{$uniqueId}}</div></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="background: #eff7fe;">
                            <table class="table table-striped table-hover table-bordered client-billing-services">
                                <thead>
                                    <tr>
                                        <td colspan="10">Services</td>
                                    </tr>
                                </thead>
                               <tbody>
                        @php $totalPrintedPrice = 0; @endphp
                        @php $overAllDue = 0; @endphp
                        @if (!empty($result))
                            
                        @foreach ($result as $clientId => $clientData)
                            @foreach ($clientData['Service'] as $serviceName => $serviceData)
                                <tr>
                                    <td colspan="5" class="fw-bold">{{$serviceName}}</td>
                                    @foreach ($serviceData['parent_service_id'] as $ParentServiceID => $ParentData)
                                    <td colspan="3"></td>
                                    <td colspan="1"  class="price">{{number_format($ParentData['parentServicePrice'], 2)}}</td>
                                     @php $totalPrintedPrice += $ParentData['parentServicePrice']; @endphp
                                    <td colspan="1"><span class="float-right badge fw-bold text-danger remove-parent-service-billing-action" id="{{$serviceName}}_{{$ParentServiceID}}"><i class="fas fa-times"></i></span></td>
                                        @if (!empty($serviceData['sub_service']))
                                        @foreach ($serviceData['sub_service'] as $subService => $subServiceData)
                                        <tr>
                                            <td colspan="5"></td>
                                            <td colspan="3">{{$subService}}</td>
                                            @foreach ($subServiceData['sub_service_id'] as $sub_service_id  => $ssData)
                                            <td colspan="1" class="price">{{number_format($ssData['sub_service_price'], 2)}}</td>
                                            @php $totalPrintedPrice += $ssData['sub_service_price']; @endphp
                                            <td colspan="1"><span class="badge fw-bold text-danger remove-sub-service-billing-action" id="{{$subService}}_{{$sub_service_id}}"><i class="fas fa-times"></i></span></td>
                                        @if (!empty($ssData['account_descriptions']))
                                        
                                        @foreach ($ssData['account_descriptions'] as $ad)
                                            <tr  style="font-size: .9em;">
                                                <td colspan="5"></td>
                                                <td colspan="2">{{ $ad['Description'] }}</td>
                                                <td colspan="2" class="price">{{ number_format($ad['Price'], 2) }}</td>
                                                @php $totalPrintedPrice += $ad['Price']; @endphp
                                                <td colspan="1"><span class="badge text-danger fw-bold remove-account-description-billing-action" id="{{ $ad['Description'] }}_{{$ad['adID']}}"><i class="fas fa-times"></i></span></td></td>
                                            </tr>
                                        @endforeach
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="10" class="text-center" style="font-size: .8em;">no account description for {{$subService}}</td>
                                    </tr>
                                    @endif
                                            @endforeach
                                        </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td colspan="10">No added requirement for this service</td>
                                        </tr>
                                        @endif
                                    {{-- @foreach ($serviceData['sub_service'] as $sub_service => $subServiceDetails)
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="3">{{$sub_service}}</td>
                                            </tr>
                                        @endforeach --}}
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                        @else
                        <tr colspan='6'>No services for this client</tr>
                        @endif
                               </tbody>
                            </table>
                               <span class="float-right fw-bold text-dark" style="font-size: .8em;">Total: ₱<span class="total-printed-price">{{number_format($totalPrintedPrice, 2)}}</span></span>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" style="background: #eff7fe; border-bottom: none;">
                            <span class="fw-bold text-sm text-dark">Additional Descriptions</span>
                            <span data-bs-target='#adbd' data-bs-toggle='modal' class="badge fw-bold" style="background: #063D58;">
                                <i class="fas fa-plus" style="font-size: .7em;"></i>
                            </span>
                        </div>
                        <div class="card-body" style="background: #eff7fe;">
                            <table class="table table-striped table-hover table-bordered additional-desc-table" id="selected-descriptions-table">
                                <thead>
                                    <tr>
                                        <td>Under Service</td>
                                        <td>Account Type</td>
                                        <td>Category</td>
                                        <td>Description</td>
                                        <td>TaxType</td>
                                        <td>FormType</td>
                                        <td>Price</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody class="append-ad">
                                </tbody>
                            </table>
                            <span class="fw-bold float-right" style="font-size: 12px;">Sub Total: ₱<span class="fw-bold total" id="additional-description-subtotal">0.00</span></span>
                        </div>
                    </div>
                    <div class="float-right">
                        @php
                        $overAllDue = $totalPrintedPrice;
                        @endphp
                    <span class="fw-bold float-right" style="font-size: 16px;">Overall Total: ₱<span class="fw-bold overall-due">{{number_format($overAllDue, 2)}}</span></span>
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
            <div class="action-billing-buttons">
               
                <button class="btn fw-bold float-right text-light mb-5 mx-2 mail-client-bs" style="background: #063D58;" id="{{$client->id}}">Send</button>
            </div>
            @include('modals.additional-billing-description-modal')
    <script>
        window.servicesData = @json($result);
        window.ads = @json($ads);
    </script>
    <script src="{{ asset('js/billing.js') }}"></script>
        </div>
    @endsection

@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="container">
            {{-- <button class="btn text-light fw-bold m-3" style="background: #063D58;">Billing Statements</button> --}}
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
                        Billed to: <span class="fw-bold" style="color: #063D58;">{{$client->CEO}} - {{$client->CompanyName}}</span> 
                        <h1 class="float-right fw-bold text-warning text-uppercase">BILLING STATEMENT</h1><br>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">Due Date: <input type="date" class="form-control w-50" name="due-date" id="dd"></div>
                                <div class="col-sm-4">Date: {{$currentDate}}</div>
                                <div class="col-sm-4">Billing ID: #{{$uniqueId}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="background: #eff7fe;">
                            <table class="table table-striped table-hover table-bordered client-billing-services">
                                <thead>
                                    <tr>
                                        <td>Services</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalPrice = 0; @endphp
                                    @if(isset($result[$clientId]['Service']) && count($result[$clientId]['Service']) > 0)
                                        @foreach($result[$clientId]['Service'] as $serviceName => $serviceData)
                                            <tr>
                                                <td><strong>{{ $serviceName }}</strong></td>
                                                <td colspan="4"></td>
                                                <td colspan="1"><span class="badge fw-bold text-danger remove-service-from-billing" id="{{$serviceName}}"><i class="fas fa-times"></i></span></td>
                                            </tr>
                                            @if(count($serviceData['sub_service']) > 0)
                                                @foreach($serviceData['sub_service'] as $subServiceName => $subServiceData)
                                                    <tr>
                                                        <td></td>
                                                        <td><strong>{{ $subServiceName }}</strong></td>
                                                        <td colspan="3"></td>
                                                        <td colspan="1"><span class="badge fw-bold text-danger remove-service-from-billing" id="{{$subServiceName}}"><i class="fas fa-times"></i></span></td>
                                                    </tr>
                                                    @if(count($subServiceData['account_descriptions']) > 0)
                                                        @foreach($subServiceData['account_descriptions'] as $accountDescription)
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ $accountDescription['Category'] }} - {{ $accountDescription['Description'] }}</td>
                                                                <td>{{ $accountDescription['Price'] }}</td>
                                                                <td><span class="badge fw-bold text-danger remove-service-from-billing" id="{{$accountDescription['Description']}}"><i class="fas fa-times"></i></span></td>
                                                            </tr>
                                                            @php $totalPrice += $accountDescription['Price']; @endphp
                                                        @endforeach
                                                    {{-- @else
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td colspan="3">No account descriptions available.</td>
                                                        </tr> --}}
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td></td>
                                                    <td colspan="3">No sub-services available.</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">No services available for this client.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <span class="fw-bold float-right sub-total-existed" style="font-size: 12px;">Sub Total: ₱<span class="test-total">{{ number_format($totalPrice, 2) }}</span></span> <!-- Display total price -->
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
                                <tbody>
                                    <!-- Selected rows will be appended here -->
                                </tbody>
                            </table>
                            <span class="fw-bold float-right" style="font-size: 12px;">Sub Total: ₱<span class="fw-bold total" id="additional-description-subtotal">0.00</span></span>
                        </div>
                    </div>
                    {{-- <div class="card">
                        <div class="card-body" style="background: #eff7fe;">
                            <span class="fw-bold float-right" style="font-size: 16px;">Overall Total: ₱<span class="fw-bold overall-total">0.00</span></span>
                        </div>
                    </div> --}}
                    <div class="float-right">
                    <span class="fw-bold float-right" style="font-size: 16px;">Overall Total: ₱<span class="fw-bold overall-total">0.00</span></span>
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
                            {{-- <div class="row">
                                <div class="col-sm-6">
                                    Prepared By: <span class="fw-bold text-uppercase">THERESA B. ELLOREN, LPT</span>
                                    <br>Collection in charge
                                </div>
                                <div class="col-sm-6">
                                    Collected By: <span class="fw-bold text-uppercase">REYMAR A. VILLEGAS</span>
                                    <br>Collection in charge
                                </div>
                                <div class="col-sm-12">
                                    Approved By: <span class="fw-bold text-uppercase">RYAN T. RAMAL</span>
                                    <br>RAM'S ACCOUNTING FIRM
                                    <br><span class="text-danger">Proprietor</span>
                                    345-980-034-000 <br>
                                    Purok Narra, Briz District, <br>
                                    Magugpo East Tagum City <br>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="action-billing-buttons">
                <button class="btn fw-bold float-right text-light mb-5" style="background: #063D58;" id="{{$client->id}}">Submit</button>
                {{-- <button class="btn fw-bold float-right text-light mb-5 mx-2 mail-client-bs" style="background: #063D58;" id="{{$client->id}}">Send</button> --}}
            </div>
            @include('modals.additional-billing-description-modal')
    <script>
        window.servicesData = @json($result);
        window.ads = @json($ads);
    </script>
    <script src="{{ asset('js/billing.js') }}"></script>
        </div>
    @endsection

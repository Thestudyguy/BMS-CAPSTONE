@extends('layout')
@section('content')
<div class="container-fluid">
        <div class="text-lg lead fw-bold client-form-title mt-5 pt-5">New Client</div><br>
        <div class="row p-5">
            <div class="col-sm-6" style="border-right: 1px solid rgba(4, 4, 4, 0.253);">
                <form action="" class="opacity-75 company-info">
                    <div class="form-group">
                        <label for="CompanyName">Company Name</label>
                        <input type="text" name="CompanyName" id="companyname" class="form-control rounded-0">
                    </div>
    
                    <div class="form-group">
                        <label for="CompanyAddress">Company Address</label>
                        <input type="text" name="CompanyAddress" id="companyaddress" class="form-control rounded-0">
                    </div>
    
                    <div class="form-group">
                        <label for="TIN">TIN</label>
                        <input type="text" name="TIN" id="tin" class="form-control rounded-0">
                    </div>
    
                    <div class="form-group">
                        <label for="CompanyEmail">Company Email</label>
                        <input type="text" name="CompanyEmail" id="companyemail" class="form-control rounded-0">
                    </div>
    
                    <div class="form-group">
                        <label for="CEO">CEO</label>
                        <input type="text" name="CEO" id="ceo" class="form-control rounded-0">
                    </div>

                    <div class="form-group">
                        <label for="CEODateOfBirth">Date of Birth</label>
                        <input type="date" name="CEODateOfBirth" id="dob" class="form-control rounded-0">
                    </div>
    
                    <div class="form-group">
                        <label for="CEOContactInformation">Contact Information <sup class="text-danger"><strong>(email or phone#)</strong></sup></label>
                        <input type="text" name="CEOContactInformation" id="ceoContactInformation" class="form-control rounded-0">
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="" class="opacity-75 client-rep">
                            <div class="form-group">
                                <label for="RepresentativeName">Representative Name</label>
                                <input type="text" name="RepresentativeName" id="representative" class="form-control rounded-0">
                            </div>
    
                            <div class="form-group">
                                <label for="RepresentativeContactInformation">Contact Information</label>
                                <input type="text" name="RepresentativeContactInformation" id="contactinfo" class="form-control rounded-0">
                            </div>
    
                            <div class="form-group">
                                <label for="RepresentativeDateOfBirth">Date of Birth</label>
                                <input type="date" class="form-control rounded-0" name="RepresentativeDateOfBirth" id="repDOB">
                            </div>
    
                            <div class="form-group">
                                <label for="RepresentativeAddress">Address</label>
                                <input type="text" class="form-control rounded-0" name="RepresentativeAddress" id="repAddress">
                            </div>
                            
                            <div class="form-group">
                                <label for="RepresentativePosition">Position</label>
                                <input type="text" name="RepresentativePosition" id="position" class="form-control rounded-0">
                            </div>
                        </form>
                    </div><hr>
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <span class="badge bg-transparent text-danger"><strong>*optional</strong></span>
                            <form action="" id="services">
                                <div class="row">
                                    @foreach ($services as $item)
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="Service[]" id="service-{{$item->id}}" value="{{$item->id}}">{{$item->Service}}
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button class="btn rounded-0 float-right mt-5 position-absolute submit-new-client" style="bottom: 0; right: 0; background-color: #063D58; color: whitesmoke;">Submit</button>
            </div>
        </div>
</div>
@endsection
{{-- <sup class="text-danger"><strong>(email or phone#)</strong></sup> --}}
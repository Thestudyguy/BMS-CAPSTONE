@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="container">
        <!-- Title -->
       
        <div class="lead fw-bold p-3 new-client-prompt"></div>
        <!-- Main content -->
        <div class="row">
            <div class="d-flex p-5 mt-3 justify-content-between align-items-lg-center py-3 flex-column flex-lg-row">
                <h2 class="h5 mb-3 mb-lg-0"><a href="../../pages/admin/customers.html" class="text-muted"><i class="bi bi-arrow-left-square me-2"></i></a> Create new customer</h2>
                <div class="hstack gap-3">
                  <button class="btn btn-light btn-sm btn-icon-text"><i class="bi bi-x"></i> <span class="text">Cancel</span></button>
                  <button class="btn btn-primary btn-sm btn-icon-text"><i class="bi bi-save"></i> <span class="text">Save</span></button>
                </div>
              </div>
          <!-- Left side -->
          <div class="col-lg-8">
            <!-- Basic information -->
            <div class="card mb-4">
              <div class="card-body">
                <h3 class="h6 mb-4">Basic information</h3>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">First name</label>
                      <input type="text" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">Last name</label>
                      <input type="text" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">Phone number</label>
                      <input type="text" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Address -->
            <div class="card mb-4">
              <div class="card-body">
                <h3 class="h6 mb-4">Address</h3>
                <div class="mb-3">
                  <label class="form-label">Address Line 1</label>
                  <input type="text" class="form-control">
                </div>
                <div class="mb-3">
                  <label class="form-label">Address Line 2</label>
                  <input type="text" class="form-control">
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">Country</label>
                      <select class="select2 form-control select2-hidden-accessible" data-select2-placeholder="Select country" data-select2-id="select2-data-1-gy14" tabindex="-1" aria-hidden="true">
                        <option data-select2-id="select2-data-3-ibs9"></option>
                        <option value="AF">Afghanistan</option>
                        <option value="BS">Bahamas</option>
                        <option value="KH">Cambodia</option>
                        <option value="DK">Denmark</option>
                        <option value="TL">East Timor</option>
                        <option value="GM">Gambia</option>
                      </select><span class="select2 select2-container select2-container--bootstrap-5" dir="ltr" data-select2-id="select2-data-2-46y9" style="width: 391px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-vp8l-container" aria-controls="select2-vp8l-container"><span class="select2-selection__rendered" id="select2-vp8l-container" role="textbox" aria-readonly="true" title="Select country"><span class="select2-selection__placeholder">Select country</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">State</label>
                      <select class="select2 form-control select2-hidden-accessible" data-select2-placeholder="Select state" data-select2-id="select2-data-4-680y" tabindex="-1" aria-hidden="true">
                        <option data-select2-id="select2-data-6-cshs"></option>
                        <option value="AL">Alabama</option>
                        <option value="CA">California</option>
                        <option value="DE">Delaware</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="KS">Kansas</option>
                      </select><span class="select2 select2-container select2-container--bootstrap-5" dir="ltr" data-select2-id="select2-data-5-np4c" style="width: 391px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-2fn7-container" aria-controls="select2-2fn7-container"><span class="select2-selection__rendered" id="select2-2fn7-container" role="textbox" aria-readonly="true" title="Select state"><span class="select2-selection__placeholder">Select state</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">City</label>
                      <select class="select2 form-control select2-hidden-accessible" data-select2-placeholder="Select city" data-select2-id="select2-data-7-809c" tabindex="-1" aria-hidden="true">
                        <option data-select2-id="select2-data-9-k35n"></option>
                        <option value="b">Bangkok</option>
                        <option value="d">Dubai</option>
                        <option value="h">Hong Kong</option>
                        <option value="k">Kuala Lumpur</option>
                        <option value="l">London</option>
                        <option value="n">New York City</option>
                        <option value="m">Macau</option>
                        <option value="p">Paris</option>
                      </select><span class="select2 select2-container select2-container--bootstrap-5" dir="ltr" data-select2-id="select2-data-8-3peu" style="width: 391px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-jdfi-container" aria-controls="select2-jdfi-container"><span class="select2-selection__rendered" id="select2-jdfi-container" role="textbox" aria-readonly="true" title="Select city"><span class="select2-selection__placeholder">Select city</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">ZIP code</label>
                      <input type="text" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Right side -->
          <div class="col-lg-4">
            <!-- Status -->
            <div class="card mb-4">
              <div class="card-body">
                <h3 class="h6">Services</h3>
                <select name="Services[]" id="" class="form-control">
                    <option value="" selected hidden>Select Services</option>
                    @foreach ($services as $item)
                    {{-- <div class="m-2">
                        <input type="checkbox" name="Service[]" id="service-{{$item->id}}" value="{{$item->id}}">{{$item->Service}}
                    </div> --}}
                    <option value="{{$item->id}}">{{$item->Service}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                  <h3 class="h6">Sub services</h3>
                  <span class="badge bg-danger text-ligh"><strong>Select service first</strong></span>
                  {{-- @foreach ($services as $item)
                  <div class="m-2">
                      <input type="checkbox" name="Service[]" id="service-{{$item->id}}" value="{{$item->id}}">{{$item->Service}}
                  </div>
              @endforeach --}}
                </div>
              </div>
            <!-- Notes -->
            <div class="card mb-4">
              <div class="card-body">
                <h3 class="h6">Notes</h3>
                <textarea class="form-control" rows="3"></textarea>
              </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                  <h3 class="h6">Company Profile<sup class="text-danger fw-bold"><strong>(option)</strong></sup></h3>
                  <input class="form-control" type="file" accept="image/*" aria-label="Upload Company Profile Image">
                </div>
              </div>
          </div>
        </div>
        {{-- <button class="btn btn-primary float-right">Submit</button> --}}
      </div>
</div>
@endsection
{{-- <sup class="text-danger"><strong>(email or phone#)</strong></sup> --}}
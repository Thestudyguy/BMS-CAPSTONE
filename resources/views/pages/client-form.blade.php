@extends('layout')
@section('content')
<div class="container-fluid client-form p-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="container p-5 w-100">
      <h4 class="h6 fw-bold text-dark">New Client</h4>
      <div class="progress mb-4">
        <div class="progress-bar" role="progressbar" style="width: 0%; background: #063D58;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <div class="card w-100">
        <div class="card-body">
          <form action="" class="client-form multi-step" hidden>
            <h4 class="h6 fw-bold text-dark mb-3"> <b class="" style="color:#063D58;">|</b> Company Information</h4>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Company Name</label>
                  <input type="text" name="CompanyName" id="companyname" class="form-control">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Company Email</label>
                  <input type="email" name="CompanyEmail" id="companyemail" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Company Address</label>
                  <input type="text" name="CompanyAddress" id="companyaddress" class="form-control">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">CEO/Owner</label>
                  <input type="text" name="CEO" id="ceo" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" name="CEODateOfBirth" class="form-control" id="ceodob">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Contact Information<sup class="text-danger fw-bold">(phone or email)</sup></label>
                  <input type="text" name="CEOContactInformation" id="ceoContactInfo" class="form-control">
                </div>
              </div>
            </div>
          </form>

          <form action="" class="client-rep multi-step" style="display: none;">
            <h4 class="h6 fw-bold text-dark mb-3"> <b class="" style="color:#063D58;">|</b> Representative Information</h4>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="RepresentativeName" id="repName" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Contact Information</label>
              <input type="text" name="RepresentativeContactInformation" id="repcontact" class="form-control">
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" name="RepresentativeDateOfBirth" id="repdob" class="form-control">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Position</label>
                  <input type="text" name="RepresentativePosition" class="form-control" id="position">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">Address</label>
                  <input type="text" name="RepresentativeAddress" id="repaddress" class="form-control">
                </div>
              </div>
            </div>
          </form>

          <form action="" class="client-services multi-step" style="display: none;">
            <h4 class="h6 fw-bold">Company Profile</h4>
            <hr>
            <center>
              <div class="image-preview">
                <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 50%; border-radius: 50%; height: auto;"/>
            </div>
            </center>
            <input type="file" name="companyProfile" class="form-control" id="fileInput" accept="image/*">
        </form>

        <form action="" class="data-entry-preview">
          <h4 class="h6 fw-bold text-dark mb-3"> <b class="" style="color:#063D58;">|</b> Review Client Information</h4>
          <hr>
          <div id="preview-container">
            <div class="card">
              <h4 class="h6 fw-bold">Company Information</h4>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6 fw-bold">Company Name: </div>
                      <div class="col-sm-6 fw-bold companyName"></div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6 fw-bold">Company Email:</div>
                      <div class="col-sm-6"></div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6 fw-bold">Address</div>
                      <div class="col-sm-6"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        </div>
       </div>
       <div class="hstack gap-3 float-right">
        <button class="btn btn-secondary btn-icon-text fw-bold text-light visually-hidden previous-form">
          <span class="text" style="letter-spacing: 1px;">Previous</span>
        </button>
         <button class="btn btn-primary btn-icon-text fw-bold text-light next-form">
           <span class="text" style="letter-spacing: 1px;">Next</span>
         </button>
         <button class="btn btn-icon-text fw-bold text-light visually-hidden save" style="background: #063D58;">
          <span class="text" style="letter-spacing: 1px;">Save</span>
        </button>
       </div>
    </div>
</div>

@endsection

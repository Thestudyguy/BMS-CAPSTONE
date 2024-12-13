@extends('layout')
@section('content')
<div class="container-fluid client-form p-5 d-flex justify-content-center align-items-center">
    <div class="container p-5 w-100">
      <h4 class="h6 fw-bold text-dark lead">New Client</h4>
      {{-- <div class="progress mb-4 position-relative">
        <div class="row position-absolute w-100 mt-2 form-step-indicator">
          <div class="col-sm-3 text-center">1</div>
          <div class="col-sm-3 text-center">2</div>
          <div class="col-sm-3 text-center">3</div>
          <div class="col-sm-3 text-center">4</div>
        </div>
        <div class="progress-bar" role="progressbar" style="width: 0%; background: #063D58;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
      </div> --}}
      <div class="client-step-indicator-container text-center mb-4">
        <section class="client-form-indicator">
            <div class="client-step step1 active">
                <div class="client-step-icon">1</div>
                <p>Company</p>
            </div>
            <div class="client-form-indicator-line active"></div>
            
            <div class="client-step step2">
                <div class="client-step-icon">2</div>
                <p>Representative</p>
            </div>
            <div class="client-form-indicator-line"></div>
            
            <div class="client-step step3">
                <div class="client-step-icon">3</div>
                <p>Profile</p>
            </div>
            <div class="client-form-indicator-line"></div>
            
            <div class="client-step step4">
                <div class="client-step-icon">4</div>
                <p>Summary</p>
            </div>
        </section>
    </div>
    
    
    
    
      <div class="card w-100">
        
        <div class="card-body">
          <form action="" class="client-form multi-step">
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
                  <label class="form-label">Contact Number
                    {{-- <sup class="text-danger fw-bold">(phone or email)</sup> --}}
                  </label>
                  <div class="input-group">
                    <span class="input-group-text">+63</span>
                    <input 
                      type="text" 
                      name="CEOContactInformation" 
                      id="ceoContactInfo" 
                      class="form-control" 
                      placeholder="Enter 10-digit phone number or email"
                      maxlength="10">
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">TIN</label>
                  <input type="number" name="TIN" id="tin" class="form-control">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">Account Category</label>
                  <select name="AccountCategory" id="" class="form-control">
                    <option value="" selected hidden>Select Category</option>
                    <option value="Client">Client</option>
                    <option value="Firm">Firm</option>
                  </select>
                </div>
              </div>
            </div>
          </form>

          <form action="" class="client-rep multi-step" style="display: none;">
            <h4 class="h6 fw-bold text-dark mb-3"> <b class="" style="color:#063D58;">|</b> Representative Information
            <span class="badge fw-bold bg-warning get-ceo-info" title="Use CEO info"><i class="fas fa-user"></i></span>
            </h4>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="RepresentativeName" id="repName" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Contact Information<sup class="text-danger fw-bold">(phone or email)</sup></label>
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

          <form action="" class="client-profile multi-step" style="display: none;">
            <h4 class="h6 fw-bold">Company Profile</h4>
            <hr>
            <center>
              <div class="image-preview">
                <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 50%; border-radius: 50%; height: auto;"/>
            </div>
            </center>
            <input type="file" name="companyProfile" class="form-control" id="fileInput" accept="image/*">
        </form>
        <form action="" class="data-entry-preview multi-step" style="display: none;">
          <h4 class="h6 fw-bold text-dark mb-3"> <b class="" style="color:#063D58;">|</b> Review Client Information</h4>
          <hr>
          <div id="preview-container" class="">
            <div class="row">
              <div class="col-sm-6">
                <div class="card mb-3">
                  <h4 class="h6 fw-bold m-3">Company Information</h4>
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-sm-6 fw-bold">Company Name: </div>
                      <div class="col-sm-6 companyName fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-sm-6 fw-bold">Company Email: </div>
                      <div class="col-sm-6 companyEmail fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-sm-6 fw-bold">Company Address: </div>
                      <div class="col-sm-6 companyAddress fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-sm-6 fw-bold">CEO/Owner: </div>
                      <div class="col-sm-6 companyCEO fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-sm-6 fw-bold">CEO Date of Birth: </div>
                      <div class="col-sm-6 companyCEODob fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-sm-6 fw-bold">CEO Contact Information: </div>
                      <div class="col-sm-6 companyCEOContact fw-bold text-dark"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card mb-3">
                      <h4 class="h6 fw-bold m-3">Representative Information</h4>
                      <div class="card-body">
                        <div class="row mb-2">
                          <div class="col-sm-6 fw-bold">Representative Name: </div>
                          <div class="col-sm-6 representativeName fw-bold text-dark"></div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-sm-6 fw-bold">Representative Contact Information: </div>
                          <div class="col-sm-6 representativeContact fw-bold text-dark"></div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-sm-6 fw-bold">Date of Birth: </div>
                          <div class="col-sm-6 representativeDob fw-bold text-dark"></div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-sm-6 fw-bold">Position: </div>
                          <div class="col-sm-6 representativePosition fw-bold text-dark"></div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-sm-6 fw-bold">Address: </div>
                          <div class="col-sm-6 representativeAddress fw-bold text-dark"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="card mb-3">
                      <h4 class="h6 fw-bold m-3">Company Profile</h4>
                      <div class="card-body">
                        <div class="row mb-2">
                          <div class="col-sm-6 fw-bold">Uploaded Profile Picture: </div>
                          <div class="col-sm-6">
                            <img id="previewImage" style="max-width: 50%; border-radius: 50%; display: none;">
                          </div>
                        </div>
                      </div>
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
<script>
  document.getElementById('ceoContactInfo').addEventListener('input', function (e) {
    const input = e.target;
    let value = input.value;

    // Allow empty input for validation purposes
    if (value === "") return;

    // Validate if the first character is '0'
    if (value[0] === '0') {
      input.value = value.slice(1); // Remove the '0'
      // alert("Please do not start the number with '0'. Use a valid 10-digit number.");
      return;
    }

    // Allow only numbers (and remove non-digit characters)
    input.value = value.replace(/[^0-9]/g, '');
  });

  // Prevent pasting invalid data
  document.getElementById('ceoContactInfo').addEventListener('paste', function (e) {
    const paste = (e.clipboardData || window.clipboardData).getData('text');
    if (!/^[1-9][0-9]*$/.test(paste)) {
      e.preventDefault();
      // alert("Please paste only valid 10-digit numbers without leading zeros.");
    }
  });
</script>
@endsection

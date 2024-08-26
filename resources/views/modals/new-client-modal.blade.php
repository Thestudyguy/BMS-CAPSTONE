<div class="modal fade" id="new-client-modal">
    <div class="modal-dialog modal-center modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h4 class="lead fw-bold current-form-title">New Client</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
            <div class="modal-body">
                <div class="row multi-step-container">
                    {{-- client info --}}
                    <div class="col-sm-12 client-info step">
                        <form action="" id="new-client-form-123">
                            <label for="companyName" class="form-label text-secondary fw-normal">Company Name</label>
                            <input type="text" class="form-control rounded-0" id="companyName" name="CompanyName" placeholder="Enter company's name">

                            <label for="ceoContactInfo" class="form-label text-secondary fw-normal">Owner/CEO Contact Info</label>
                            <input type="text" class="form-control rounded-0" id="ceoContactInfo" name="CEOCContactInfo" placeholder="Enter CEO contact info">

                            <label for="tin" class="form-label text-secondary fw-normal">TIN</label>
                            <input type="number" class="form-control rounded-0" id="tin" name="TIN" placeholder="Enter TIN" style="">

                            <label for="companyEmail" class="form-label text-secondary fw-normal">Company Email</label>
                            <input type="email" class="form-control rounded-0" id="companyEmail" name="CompanyEmail"placeholder="Enter company's email">

                            <label for="companyOwner" class="form-label text-secondary fw-normal">Company Owner/CEO</label>
                            <input type="text" class="form-control rounded-0" id="companyOwner" name="CompanyOwner" placeholder="Enter owner's name">

                            <label for="dob" class="form-label text-secondary fw-normal">Date of Birth</label>
                            <input type="date" class="form-control rounded-0" id="dob" name="DOB" placeholder="Enter date of birth">

                            <label for="companyAddress" class="form-label text-secondary fw-normal">Company Address</label>
                            <input type="text" class="form-control rounded-0" id="companyAddress" name="CompanyAddress" placeholder="Enter company address">
                        </form>
                    </div>

                    <div class="representative-info step" style="display: none">
                      <form action="" id="client-rep">
                        <label for="companyName" class="form-label text-secondary fw-normal">Company Representative</label>
                        <input type="text" class="form-control rounded-0" id="companyName" name="CompanyName" placeholder="Enter company's name">
                        <label for="companyName" class="form-label text-secondary fw-normal">Representative Contact Info<sup class="text-warning"><strong>(email or phone#)</strong></sup></label>
                        <input type="text" class="form-control rounded-0" id="companyName" name="CompanyName" placeholder="Enter company's name">

                        <label for="companyName" class="form-label text-secondary fw-normal">Date Of Birth</label>
                        <input type="text" class="form-control rounded-0" id="companyName" name="CompanyName" placeholder="Enter company's name">

                        <label for="companyName" class="form-label text-secondary fw-normal">Position</label>
                        <input type="text" class="form-control rounded-0" id="companyName" name="CompanyName" placeholder="Enter company's name">

                        <label for="companyName" class="form-label text-secondary fw-normal">Address</label>
                        <input type="text" class="form-control rounded-0" id="companyName" name="CompanyName" placeholder="Enter company's name"> 

                      </form>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0 visually-hidden"
                    id="submit-new-client">{{ __('Submit') }}</button>
                <button class="btn next-form rounded-0">Next</button>
                <button type="button" class="btn btn-secondary rounded-0"
                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

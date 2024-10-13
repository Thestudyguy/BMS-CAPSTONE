<div class="modal fade" id="edit-sub-service-modal">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="sub-service-loader visually-hidden" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.8); z-index: 10; display: flex; justify-content:center; align-items: center;">
                <div class="loader"></div>
            </div>
        <div class="modal-header rounded-0" style="background: #063D58;">
          <h4 class="lead text-light">New  Sub Service Service</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="edit-sub-service-form">
                <div class="form-group mb-3">
                    <label for="Service" class="form-label text-secondary fw-normal">Service</label>
                    <input type="text" class="form-control rounded-0" id="Service" name="service" placeholder="Enter Service" required>
                </div>
            
                <div class="form-group mb-3">
                    <label for="Service Price" class="form-label text-secondary fw-normal">Service Price</label>
                    <input type="text" class="form-control rounded-0" id="ServicePrice" name="serviceprice" placeholder="Enter price" required>
                </div>
            
                {{-- <div class="form-group mb-3">
                    <label for="description" class="form-label text-secondary fw-normal">Date of Birth</label>
                    <input type="date" class="form-control rounded-0" id="dateOfBirth" name="DateOfBirth" required>
                </div> --}}
                <input type="hidden" name="" id="sub-service-edit-id">
            
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0" id="submit-new-service">{{__('Save')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </form>
      </div>
    </div>
  </div>  
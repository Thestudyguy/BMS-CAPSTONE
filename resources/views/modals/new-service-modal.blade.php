<div class="modal fade" id="new-service-modal">
    <div class="modal-dialog modal-center modal-lg">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h4 class="lead">New Service</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="new-service-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="Service" class="form-label text-secondary fw-normal">Service</label>
                    <input type="text" class="form-control rounded-0" id="Service" name="Service" placeholder="Enter Service">
                    <span class="badge text-danger text-sm visually-hidden conflict-warning"><strong>Service already exists</strong></span>
                  </div>
            
                <div class="form-group mb-3">
                    <label for="ServicePrice" class="form-label text-secondary fw-normal">Service Price</label>
                    <input type="text" oninput="formatValueInput(this)" class="form-control rounded-0" id="Price" name="Price" placeholder="Enter price">
                </div>
                {{-- <div class="form-group mb-3">
                    <label for="description" class="form-label text-secondary fw-normal">Date of Birth</label>
                    <input type="date" class="form-control rounded-0" id="dateOfBirth" name="DateOfBirth" required>
                </div> --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0" id="submit-new-service">{{__('Save')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </form>
      </div>
    </div>
  </div>  
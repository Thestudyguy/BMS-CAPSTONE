<div class="modal fade" id="new-sub-service-modal-{{$service->id}}">
    <div class="modal-dialog modal-center modal-lg">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h4 class="lead fw-bold">{{$service->Service}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="new-client-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="Service" class="form-label text-secondary fw-normal">Service/Requirement</label>
                    <input type="text" class="form-control rounded-0" id="Service" name="Service" placeholder="Enter Service" required>
                </div>
                <div class="form-group mb-3">
                    <label for="Service Price" class="form-label text-secondary fw-normal">Service Price</label>
                    <input type="text" class="form-control rounded-0" id="ServicePrice" name="ServicePrice" placeholder="Enter price" required>
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
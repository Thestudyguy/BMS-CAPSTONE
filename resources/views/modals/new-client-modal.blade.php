
<div class="modal fade" id="new-client-modal">
    <div class="modal-dialog modal-center modal-fullscreen">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h4 class="lead">New Client</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="new-client-form">
                @csrf
            
                <div class="form-group mb-3">
                    <label for="clientName" class="form-label text-secondary fw-normal">Client Name</label>
                    <input type="text" class="form-control rounded-0" id="clientName" name="ClientName" placeholder="Enter client's name" required>
                </div>
            
                <div class="form-group mb-3">
                    <label for="contactInfo" class="form-label text-secondary fw-normal">Contact Info</label>
                    <input type="text" class="form-control rounded-0" id="contactInfo" name="ContactInfo" placeholder="Enter contact information" required>
                </div>
            
                <div class="form-group mb-3">
                    <label for="dateOfBirth" class="form-label text-secondary fw-normal">Date of Birth</label>
                    <input type="date" class="form-control rounded-0" id="dateOfBirth" name="DateOfBirth" required>
                </div>
            
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary rounded-0" id="submit-new-client">{{__('Remove')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </form>
      </div>
    </div>
  </div>  
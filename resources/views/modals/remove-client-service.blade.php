<div class="modal fade" id="remove-client-service-{{$service->id}}">
    <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
        <div class="modal-header text-light rounded-0 fw-bold" style="background: #063D58;">
          Client Service
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-md">
            Are you sure you want to remove Client Service <span class="fw-bold">{{$service->ClientService}}</span> ?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn text-light remove-client-service rounded-0" id="{{$service->id}}" style="background: #063D58;">{{__('Remove')}}</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
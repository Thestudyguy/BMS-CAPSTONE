<div class="modal fade" id="remove-description-{{$ads->id}}">
    <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
        <div class="modal-header text-light rounded-0 fw-bold" style="background: #063D58;">
          Account Description 
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to remove Account Description <span class="fw-bold">{{$ads->Description}}</span> ?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn text-light remove-description rounded-0" id="{{$ads->id}}" style="background: #063D58;">{{__('Remove')}}</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
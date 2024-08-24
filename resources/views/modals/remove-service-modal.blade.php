<div class="modal fade" id="remove-service-modal-{{$service->id}}">
    <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h4 class="lead">Remove Service</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <strong>
                <span class="text-dark">
                    Are you sure you want to remove service <br>
                    <strong class="text-danger">{{$service->Service}} ?</strong>
                </span>
            </strong>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn remove-service btn-primary rounded-0" id="{{$service->id}}">{{__('Remove')}}</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
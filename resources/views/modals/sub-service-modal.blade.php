<div class="modal fade" id="sub-service-{{$service->id}}">
    <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h4 class="lead">{{$service->Service}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form action="" id="new-sub-service">
                <div class="form-group mb-3">
                    <label for="Service" class="form-label text-secondary fw-normal">Service/Requirement</label>
                    <input type="text" class="form-control rounded-0" id="service" name="ServiceRequirements" placeholder="Enter Service">
                    <span class="badge text-danger text-sm visually-hidden conflict-warning"><strong class="conflict-text"></strong></span>
                  </div>

                  <div class="form-group mb-3">
                    <label for="Service" class="form-label text-secondary fw-normal">Price</label>
                    <input type="text" class="form-control rounded-0" id="serviceprice" name="ServicePrice" placeholder="Enter Price">
                    <span class="badge text-danger text-sm visually-hidden conflict-warning"><strong class="conflict-text"></strong></span>
                  </div>

                  <input type="hidden" name="serviceID" value="{{$service->id}}">
            </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn submit-new-sub-service btn-primary rounded-0" id="{{$service->id}}">{{__('Save')}}</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
<div class="modal fade" id="sub-service-{{$service->id}}">
  <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
          <div class="modal-header">
              <h4 class="lead">{{$service->Service}}</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <form id="new-sub-service-{{$service->id}}">
                  <div class="form-group mb-3">
                      <label for="service-{{$service->id}}" class="form-label text-secondary fw-normal">Service/Requirement</label>
                      <input type="text" class="form-control rounded-0" id="service-{{$service->id}}" name="ServiceRequirements" placeholder="Enter Service">
                      <span class="badge text-danger text-sm visually-hidden conflict-warning"><strong class="conflict-text"></strong></span>
                  </div>
                  <div class="form-group mb-3">
                      <label for="serviceprice-{{$service->id}}" class="form-label text-secondary fw-normal">Amount</label>
                      <input type="text" class="form-control rounded-0" oninput="formatValueInput(this)" id="serviceprice-{{$service->id}}" name="ServicePrice" placeholder="Enter Amount">
                      <span class="badge text-danger text-sm visually-hidden conflict-warning"><strong class="conflict-text"></strong></span>
                  </div>
                  <input type="hidden" name="serviceID" value="{{$service->id}}">
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn submit-new-sub-service btn-primary rounded-0" id="{{$service->id}}">{{__('Save')}}</button>
              <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
  </div>
</div>

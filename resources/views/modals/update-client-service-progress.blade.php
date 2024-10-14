<div class="modal fade" id="update-client-service-{{$service->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <div class="modal-title fw-bold lead">Update service - {{$service->ClientService}}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color:#063D58;">
              <form action="" class="update-service-progress-{{$service->id}}">
                <select name="ClientServiceProgress" class="form-control" id="serviceprogress">
                    <option value="{{$service->ClientServiceProgress}}" selected hidden>{{$service->ClientServiceProgress}}</option>
                    <option value="On progress">On progress</option>
                    <option value="Done">Done</option>
                    <option value="Paid">Paid</option>
                </select>
              </form>
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="btn update-client-service-progress-btn text-light fw-bold" id="{{$service->id}}" style="background: #063D58;">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

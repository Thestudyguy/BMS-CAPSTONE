<div class="modal fade" id="add-service-req-{{$service->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <span class="text-light fw-bold text-md">Add Requirement for Service / {{$service->Service}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" class="service-req-{{$service->id}}">
                    <input type="text" name='req-name' class="form-control" placeholder="Enter requirement name...">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn add-new-service-req fw-bold text-light rounded-0" id="service_{{$service->id}}">{{__('Add')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
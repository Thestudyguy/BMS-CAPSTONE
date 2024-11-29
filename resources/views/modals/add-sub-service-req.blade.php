<div class="modal fade" id="add-sub-service-req">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <span class="text-light fw-bold text-md">Add Requirement for Service / <span class="sub-service-req-service-name"></span></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" class="sub-service-req-form">
                    <input type="text" name='sub-req-name' id="sub-service-req-id" class="form-control" placeholder="Enter requirement name...">
                    <input type="hidden" name="sub-service-id" id="sub_service_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn add-new-sub-service-req fw-bold text-light rounded-0">{{__('Add')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
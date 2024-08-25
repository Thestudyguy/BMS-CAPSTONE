<div class="modal fade" id="edit-service-modal-{{$service->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h4 class="lead">Edit Service</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" id="edit-service-form-{{$service->id}}">
                    <div class="form-group mb-3">
                        <label for="Service" class="form-label text-secondary fw-normal">Service</label>
                        <input value="{{$service->Service}}" type="text" class="form-control rounded-0" id="Service" name="Service" placeholder="Enter Service">
                    </div>
                    <div class="form-group mb-3">
                        <label for="Price" class="form-label text-secondary fw-normal">Price</label>
                        <input value="{{$service->Price}}" max="99999999999" oninput="formatValueInput(this)" type="text" class="form-control rounded-0" id="Price" name="Price" placeholder="Enter Price">
                    </div>
                    <input type="hidden" name="id" value="{{$service->id}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn edit-service btn-success rounded-0" id="{{$service->id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

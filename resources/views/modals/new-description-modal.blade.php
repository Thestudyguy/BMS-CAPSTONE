<div class="modal fade" id="new-description">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header" style="background: #063D58; border-radius: 0px; color: whitesmoke;">
                <h4 class="lead fw-bold">New Description</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="color: whitesmoke;"></button>
            </div>
            <div class="modal-body position-relative">
                <form action="" class="account-description-form">
                    <div class="form-group">
                        <label for="account" class="fw-bold">Select Category</label>
                        <select name="account" class="form-control" id="account-category">
                            <option value="" selected hidden>Category</option>
                            @foreach ($services as $service)
                                <option value="{{$service->id}}">{{$service->Service}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="account" class="fw-bold">Type</label>
                        <select name="Type" class="form-control" id="billing-account-type" disabled>
                            <option value="" selected hidden>select category first</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Description" class="fw-bold">Description</label>
                        <input type="text" name="Description" class="form-control" id="description">
                    </div>
                    <div class="form-group">
                        <label for="TaxType" class="fw-bold">Tax Type</label>
                        <input type="text" name="TaxType" class="form-control" id="tax-type">
                    </div>
                    <div class="form-group">
                        <label for="FormType" class="fw-bold">Form Type</label>
                        <input type="text" name="FormType" class="form-control" id="form-type">
                    </div>
                    <div class="form-group">
                        <label for="FormType" class="fw-bold">Price</label>
                        <input type="text" name="Price" class="form-control" oninput="formatValueInput(this)" id="price">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn save-account-description rounded-0" style="background: #063D58; border-radius: 0px; color: whitesmoke;">{{__('Save')}}</button>
                <button type="button" class="btn btn-secondary close-account-type-modal rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

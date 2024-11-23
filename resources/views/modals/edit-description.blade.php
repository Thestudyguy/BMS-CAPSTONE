<div class="modal fade" id="edit-description-{{$ads->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light text-sm fw-bold">Edit Description {{$ads->Description}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
               <form action="" class="update-description-form-{{$ads->id}}">
                <input type="hidden" name="ad_id" value="{{$ads->id}}">
                <label for="AccountName">Select Service Category</label>
                <select name="account" class="form-control" id="edit-account-category-{{$ads->id}}">
                    <option value="{{$ads->service_id}}" selected hidden>{{$ads->Service}}</option>
                    @foreach ($services as $service)
                        <option value="{{$service->id}}">{{$service->Service}}</option>
                    @endforeach
                </select>
                <label for="Type">Type</label>
                <select name="Type" class="form-control" id="edit-billing-account-type-{{$ads->id}}">
                    <option value="{{$ads->sub_service_id}}" selected hidden>{{$ads->ServiceRequirements}}</option>
                </select>
                
                <label for="category">Select Category</label>
                <select name="category" id="" class="form-control">
                    <option value="{{$ads->adCategory}}" selected hidden>{{$ads->adCategory}}</option>
                    <option value="Internal">Internal</option>
                    <option value="Internal">External</option>
                </select>

                <label for="Description">Description</label>
                <input type="text" name="description" id="" class="form-control" value="{{$ads->Description}}">
                
                <label for="Price">Price</label>
                <input type="text" name="price" oninput="formatValueInput(this)" value="{{$ads->Price}}" id="" class="form-control">

                <label for="taxType">Tax Type</label>
                <input type="text" name="taxType" value="{{$ads->TaxType}}" id="" class="form-control">

                <label for="FormType">Form Type</label>
                <input type="text" name="formType" value="{{$ads->FormType}}" id="" class="form-control">
            </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn fw-bold edit-description text-light rounded-0" id="{{$ads->id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
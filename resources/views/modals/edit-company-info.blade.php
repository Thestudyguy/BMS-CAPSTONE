<div class="modal fade" id="edit-company-profile-{{$client->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light fw-bold">Edit Company Profile - {{ $client->CEO }}, {{ $client->CompanyName }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" class="edit-company-info-{{$client->id}}">
                    <label for="CompanyName" class="fw-bold">CompanyName</label>
                    <input type="text" name="CompanyName" class="form-control" value="{{$client->CompanyName}}">
                    <label for="CompanyName" class="fw-bold">Company Address</label>
                    <input type="text" name="CompanyAddress" class="form-control" value="{{$client->CompanyAddress}}">
                    <label for="CompanyName" class="fw-bold">Company Email</label>
                    <input type="text" name="CompanyEmail" class="form-control" value="{{$client->CompanyEmail}}">
                    <label for="CompanyName" class="fw-bold">TIN</label>
                    <input type="text" name="TIN" class="form-control" value="{{$client->TIN}}">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn edit-company-info fw-bold text-light rounded-0" id="{{$client->id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
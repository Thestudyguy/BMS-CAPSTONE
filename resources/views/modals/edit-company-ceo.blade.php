<div class="modal fade" id="edit-company-ceo-{{$client->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light fw-bold">Edit CEO -  {{ $client->CompanyName }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" class="edit-ceo-{{$client->id}}">
                    <label for="CEO">CEO</label>
                    <input type="text" class="form-control" name="CEO" value="{{$client->CEO}}" id="">
                    <label for="CEODateOfBirth">Date of Birth</label>
                    <input type="date" name="DateOfBirth" class="form-control" id="" value="{{$client->CEODateOfBirth}}">
                    <label for="CEOContactInformation">Contact Information</label>
                    <input type="text" class="form-control" name="CEOContactInformation" value="{{$client->CEOContactInformation}}" id="">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn edit-ceo fw-bold text-light rounded-0" id="{{$client->id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
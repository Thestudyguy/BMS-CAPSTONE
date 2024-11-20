<div class="modal fade" id="edit-company-rep-{{$repInfoData->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light fw-bold">Edit Representative {{ $repInfoData->RepresentativeName }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" class="edit-rep-{{$repInfoData->id}}">
                    <label for="RepName">Representative</label>
                    <input type="text" class="form-control" name="RepresentativeName" value="{{$repInfoData->RepresentativeName}}">
                    <label for="RepresentativeContactInformation">Contact Information</label>
                    <input type="text" class="form-control" name="RepresentativeContactInformation" value="{{$repInfoData->RepresentativeContactInformation}}">
                    <label for="RepresentativeDateOfBirth">Date of Brirth</label>
                    <input type="date" class="form-control" name="RepresentativeDateOfBirth" value="{{$repInfoData->RepresentativeDateOfBirth}}">
                    <label for="RepresentativePosition">Position</label>
                    <input type="text" class="form-control" name="RepresentativePosition" value="{{$repInfoData->RepresentativePosition}}">
                    <label for="RepresentativeAddress">Address</label>
                    <input type="text" class="form-control" name="RepresentativeAddress" value="{{$repInfoData->RepresentativeAddress}}">
                    <input type="hidden" name="rep_id" id="{{$repInfoData->id}}" value="{{$repInfoData->id}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn edit-company-rep fw-bold text-light rounded-0" id="{{$repInfoData->id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
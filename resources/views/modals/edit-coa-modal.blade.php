<div class="modal fade" id="edit-coa-modal-{{$accounts->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light">Edit Account {{$accounts->AccountName}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" id="edit-coa-form-{{$accounts->id}}">
                   <label for="AccountName" class="fw-normal form-label text-secondary">Account Name</label>
                   <input type="text" name="AccountName" id="accountname" class="form-control" value="{{$accounts->AccountName}}">
                   <label for="AccountType" class="fw-normal form-label text-secondary mt-3">Account Type</label>
                    <select name="AccountType" id="accouttype" class="form-control">
                        <option selected hidden value="{{$accounts->ATid}}">{{$accounts->AccountType}}</option>
                        @foreach ($at as $ats)
                            <option value="{{$ats->id}}">{{$ats->AccountType}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="id" value="{{$accounts->id}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" class="btn edit-coa fw-bold text-light rounded-0" id="{{$accounts->id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light " data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="new-COA">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            {{-- <div class="loader-container visually-hidden" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.8); z-index: 10; display: flex; justify-content:center; align-items: center;">
                <div class="loader"></div>
            </div> --}}
            <div class="modal-header" style="background: #063D58; border-radius: 0px; color: whitesmoke;">
                <h4 class="lead fw-bold">New Chart of Account</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <form action="" id="account-form">
                    <div class="form-group mb-3">
                        <label for="Service" class="form-label text-secondary fw-normal">Account Name</label>
                        <input type="text" class="form-control rounded-0" id="accountname" name="AccountName" placeholder="Enter Service">
                    </div>
                    <div class="form-group mb-3">
                        <label for="Price" class="form-label text-secondary fw-normal">Account Type</label>
                        <select name="AccountType" class="form-control">
                            <option value="" selected hidden>Select Account Type</option>
                           @foreach ($at as $ats)
                               <option value="{{$ats->id}}">{{$ats->AccountType}} - {{$ats->Category}}</option>
                           @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group mb-3">
                        <label for="Price" class="form-label text-secondary fw-normal">Category</label>
                        <select name="Category" class="form-control" id="">
                            <option value="" selected hidden>Select Category</option>
                            <option value="Liability">Liability</option>
                            <option value="Assets">Assets</option>
                            <option value="Equity">Equity</option>
                        </select>
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn save-account rounded-0" style="background: #063D58; border-radius: 0px; color: whitesmoke;">{{__('Save')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

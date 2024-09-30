<div class="modal fade" id="new-acc-type">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header" style="background: #063D58; border-radius: 0px; color: whitesmoke;">
                <h4 class="lead fw-bold">New Account Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                   <form action="" class="new-account-type-form">
                    <div class="form-group mb-3">
                        <label for="Account Type" class="form-label text-secondary fw-normal">Account Type</label>
                        <input value="" type="text" class="form-control" id="accountType" name="AccountType" placeholder="Enter Service">
                    </div>
                    <div class="form-group mb-3">
                        <label for="Account Type" class="form-label text-secondary fw-normal">Service</label>
                        <select name="Category" id="category" class="form-control">
                            <option value="" selected hidden>Select Category</option>
                            <option value="Asset">Asset</option>
                            <option value="Liability">Liability</option>
                            <option value="Equity">Equity</option>
                        </select>
                    </div>
                   </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn save-account-type rounded-0" id=""style="background: #063D58; border-radius: 0px; color: whitesmoke;">{{__('Save')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

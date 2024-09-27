<div class="modal fade" id="new-COA">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header" style="background: #063D58; border-radius: 0px; color: whitesmoke;">
                <h4 class="lead fw-bold">New Chart of Account</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" id="">
                    <div class="form-group mb-3">
                        <label for="Service" class="form-label text-secondary fw-normal">Account Name</label>
                        <input type="text" class="form-control rounded-0" id="accountname" name="Account" placeholder="Enter Service">
                    </div>
                    <div class="form-group mb-3">
                        <label for="Price" class="form-label text-secondary fw-normal">Account Type</label>
                        <select name="AccountType" class="form-control" id="">
                            <option value="" selected hidden>Select Account Type</option>
                            <option value="">Type 1 Diabetes hahaha</option>
                            <option value="">Type 2</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Price" class="form-label text-secondary fw-normal">Category</label>
                        <select name="Category" class="form-control" id="">
                            <option value="" selected hidden>Select Category</option>
                            <option value="">Liabilities</option>
                            <option value="">Assets</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn edit-service rounded-0" style="background: #063D58; border-radius: 0px; color: whitesmoke;">{{__('Save')}}</button>
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

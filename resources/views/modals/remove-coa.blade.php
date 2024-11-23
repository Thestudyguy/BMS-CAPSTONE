<div class="modal fade" id="remove-coa-{{$accounts->id}}">
    <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
        <div class="modal-header text-light rounded-0 fw-bold" style="background: #063D58;">
          Chart of Account
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to remove Account <span class="fw-bold">{{$accounts->AccountName}}</span> ?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn text-light remove-coa rounded-0" id="{{$accounts->id}}" style="background: #063D58;">{{__('Remove')}}</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
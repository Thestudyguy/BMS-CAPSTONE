<div class="modal fade" id="remove-journal-entry-{{$journal->journal_id}}">
    <div class="modal-dialog modal-center">
      <div class="modal-content rounded-0">
        <div class="modal-header rounded-0 text-light" style="background: #063D58;">
          <h4 class="lead fw-bold">Remove Journal Entry {{$journal->journal_id}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to remove this journal entry?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn fw-bold text-light rounded-0 remove-journal-entry" id="{{$journal->journal_id}}" style="background: #063D58;" id="client-id">{{__('Remove')}}</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
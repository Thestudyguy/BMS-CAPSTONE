<div class="modal fade" id="update-journal-status-{{$journal->journal_id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header fw-bold rounded-0 text-light" style="background: #063D58;">
                <div class="modal-title fw-bold lead">Update Journal Status - {{$journal->journal_id}}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color:#063D58;">
              <form action="" class="update-journal-status-form-{{$journal->journal_id}}">
                <select name="JournalStatus" class="form-control" id="serviceprogress">
                    <option value="{{$journal->JournalStatus}}" selected hidden>{{$journal->JournalStatus}}</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Canceled">Canceled</option>
                    <option value="Approved">Approved</option>
                </select>
                <input type="hidden" name="journal_id" value="{{$journal->journal_id}}">
                <input type="hidden" name="journalID" value="{{$journal->id}}">
                <textarea name="journal-draft-note" class="form-control my-2 visually-hidden journal-draft-note" placeholder="Note..." id="" cols="30" rows="10"></textarea>
              </form>
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="btn update-journal-status text-light fw-bold" style="background: #063D58;" id="{{$journal->journal_id}}">{{__('Update')}}</button>
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
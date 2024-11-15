<div class="modal fade" id="journal_pin_entry_{{$client->id}}_{{$journal->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light">Enter PIN/ID</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" class="journal-pin-entry">
            <div class="modal-body">
                    <input type="text" name="journal_id" class="form-control" placeholder="Enter pin..." id="">
                    <input type="hidden" name="journalID" value="{{$journal->id}}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn rounded-0 text-white fw-bold" style="background: #063D58;">{{__('Done')}}</button>
                    <button type="button" class="btn btn-secondary rounded-0 fw-bold" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

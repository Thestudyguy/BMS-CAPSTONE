<div class="modal fade" id="remove-user-{{$user->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h4 class="lead">Remove User: {{$user->PIN}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently remove the user with PIN <strong>{{$user->PIN}}?</strong></p>
            </div>
            <div class="modal-footer">
                <button class="btn fw-bold text-light remove-user" style="background: #063D58;" id="{{$user->id}}">Remove</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

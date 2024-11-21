<div class="modal fade" id="disable-user-login-{{$user->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light">
                    Update User LogIn {{$user->LastName}}, {{$user->FirstName}} - {{$user->Role}}
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <span class="fw-bold text-dark">
                    {{ $user->UserPrivilege == 1 ? 'Disable' : 'Enable' }} User LogIn 
                </span>
            </div>
            <div class="modal-footer">
                <button type="submit" style="background: #063D58;" id="{{$user->id}}"
                    class="btn fw-bold text-light rounded-0 edit-user-privilege">
                    {{ $user->UserPrivilege == 1 ? __('Disable') : __('Enable') }}
                </button>
                <button type="button" class="btn btn-secondary rounded-0 fw-bold text-light" 
                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-user-modal-{{$user->id}}">
    <div class="modal-dialog modal-center modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h4 class="lead fw-bold">Edit User <span class="fw-bold" style="color:#063D58;"><strong>{{$user->LastName}}, {{$user->FirstName}} - {{$user->Role}}</strong></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color:#063D58;">
              <form action="" class="edit-user-form-{{$user->id}}" id="user-edit-form">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="FirstName">First Name</label>
                            <input type="text" value="{{$user->FirstName}}" class="form-control" name="FirstName" id="firstname">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="FirstName">Last Name</label>
                            <input type="text" value="{{$user->LastName}}" class="form-control" name="LastName" id="lastname">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="Email">Email</label>
                            <input type="email" value="{{$user->Email}}" class="form-control" name="Email" id="email">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="Role">Role</label>
                            <select name="Role" class="form-control" id="">
                                <option value="{{$user->Role}}">{{$user->Role}}</option>
                                <option value="Admin">Admin</option>
                                <option value="Bookkeeper">Bookkeeper</option>
                                <option value="Accountant">Accountant</option>
                                {{-- <option value="Super User">Super User</option>
                                <option value="User">User</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="PIN">PIN</label>
                            <input type="text" value="{{$user->PIN}}" class="form-control" name="PIN" id="pin">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="UserName">UserName</label>
                            <input type="text" value="{{$user->UserName}}" class="form-control" name="UserName" id="username">
                        </div>
                    </div>
                    {{-- <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="Password">Password</label>
                            <input type="password" value="{{$user->Password}}" class="form-control" name="password" id="Password">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="fw-bold" for="PasswordConfirmation">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="PasswordConfirmation">
                        </div>
                    </div> --}}
                </div>
                <input type="hidden" name="id" value="{{$user->id}}">
              </form>
            </div>
            <div class="modal-footer"> 
                <button type="submit" class="btn edit-user-button text-light fw-bold" style="background: #063D58;">{{__('Create')}}</button>
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

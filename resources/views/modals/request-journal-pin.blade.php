<div class="modal fade" id="request_journal_pin_{{$client->id}}_{{$journal->id}}">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light">Select Accountant/Admin</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr id="user_{{$user->id}}">
                                <td>{{$user->LastName}}, {{$user->FirstName}}</td>
                                <td>{{$user->Role}}</td>
                                <td>{{$user->Email}}</td>
                                <td>
                                    <span class="badge bg-warning fw-bold request-journal-pin" id="{{$client->id}}_{{$journal->id}}_{{$user->id}}"><span class="far fa-envelope"></span></span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-0 fw-bold" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>

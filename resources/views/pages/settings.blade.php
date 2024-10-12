@extends('layout')

@section('content')
    <div class="container-fluid mt-5 pt-5 external-services">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">System Profile</div>
                            <div class="card-body">
                                <img src="{{ asset('images/Rams_logo.png') }}" alt="" width="100">
                                <div class="name fw-bold" style="color: #063D58; font-size: 1.5em;">BOOKKEEPING MANAGEMENT SYSTEM</div>
                               <form action="" class="sys-profile-form">
                                <div class="phone">
                                    <input class="form-control" type="text" name="PhoneNumber" id="phonenumber">
                                </div>
                                <div class="email">
                                    <input class="form-control my-2" type="text" name="Email" id="email">
                                </div>
                                <div class="address">
                                    <input class="form-control" type="text" name="Address" id="address">
                                </div>
                               </form>
                               <button class="btn btn-primary text-light fw-bold float-right mt-2 visually-hidden">Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Users'</div>
                                <div class="card-tools">
                                    <button class="btn text-light"><i class="fas fa-plus text-light"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover table-striped table-bordered settings-user-table">
                                    <thead>
                                        <tr>
                                            <td>Users</td>
                                            <td>Log In</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($users as $user)
                                           <tr id="{{$user->id}}">
                                            <td>{{$user->LastName}}, {{$user->FirstName}} - {{$user->Role}}</td>
                                           <td>
                                            @if ($user->UserPrivilege)
                                            <span class="badge bg-warning fw-bold" title="disable user log in"><strong>Disable</strong></span>
                                        @else
                                            <span class="badge bg-warning fw-bold" title="enable user log in"><strong>Enable</strong></span>
                                        @endif
                                           </td>
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    @endsection
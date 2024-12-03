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
                               <form action="" id="sys-profile-form" class="sys-profile-form">
                                <div class="phone">
                                    <input class="form-control" type="text" name="PhoneNumber" value="{{$sysProfile->PhoneNumber}}" id="phonenumber">
                                </div>
                                <div class="email">
                                    <input class="form-control my-2" type="text" value="{{$sysProfile->Email}}" name="Email" id="email">
                                </div>
                                <div class="address">
                                    <input class="form-control" type="text" value="{{$sysProfile->Address}}" name="Address" id="address">
                                </div>
                               </form>
                               <button form="sys-profile-form" class="btn text-light fw-bold edit-sys-profile float-right mt-2" style="background: #063D58; font-size: 14px;">Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Users</div>
                                <div class="card-tools">
                                    <button class="btn text-light" data-bs-target="#new-user-modal" data-bs-toggle="modal"><i class="fas fa-plus text-light"></i></button>
                                </div>
                                @include('modals.new-user-modal')
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
                                            <span class="badge bg-warning fw-bold" title="disable user log in" data-bs-target="#disable-user-login-{{$user->id}}" data-bs-toggle="modal"><strong>Disable</strong></span>
                                        @else
                                            <span class="badge bg-warning fw-bold" title="enable user log in" data-bs-target="#disable-user-login-{{$user->id}}" data-bs-toggle="modal"><strong>Enable</strong></span>
                                        @endif
                                           </td>
                                        </tr>
                                        @include('modals.disable-user-login')
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Account Descriptions</div>
                            <div class="card-tools"><button class="btn btn-transparent fw-bold" data-bs-target='#new-description' data-bs-toggle='modal'><i class="fas fa-plus text-light"></i></button></div>
                        </div>
                        <div class="card-body">
                            <div class="" style="max-height: 300px; overflow: auto;">
                            <table class="table table-bordered table-hover table-striped" style="font-size: .8em;">
                                <thead>
                                    <tr>
                                        <td class="fw-bold">Under Service</td>
                                        <td class="fw-bold">Account Type</td>
                                        <td class="fw-bold">Category</td>
                                        <td class="fw-bold">Description</td>
                                        <td class="fw-bold">Price</td>
                                        <td class="fw-bold">TaxType</td>
                                        <td class="fw-bold">FormType</td>
                                        <td class="fw-bold">Action</td>
                                    </tr>
                                </thead>
                                <tbody style="max-height: 400px; overflow: auto;">

                                    @foreach ($adac as $ads)
                                        <tr id="{{$ads->id}}">
                                            <td>{{$ads->Service}} {{$ads->AccountName}}</td>
                                            <td>{{$ads->ServiceRequirements}}</td>
                                            <td>{{$ads->adCategory}}</td>
                                            <td>{{$ads->Description}}</td>
                                            <td>{{number_format($ads->Price, 2)}}</td>
                                            <td>{{$ads->TaxType}}</td>
                                            <td>{{$ads->FormType}}</td>
                                            <td>
                                                <span class="badge fw-bold text-dark bg-warning" data-bs-target="#edit-description-{{$ads->id}}" data-bs-toggle="modal"><i class="fas fa-pen"></i></span>
                                                <span class="badge fw-bold text-dark bg-warning" data-bs-target="#remove-description-{{$ads->id}}" data-bs-toggle="modal"><i class="fas fa-trash"></i></span>
                                            </td>
                                        </tr>
                                        @include('modals.remove-account-description')
                                        @include('modals.edit-description')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                   </div>
                </div>
            </div>
            @include('modals.new-description-modal')
    </div>
    @endsection
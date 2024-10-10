@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        {{-- <div class="lead fw-bold mb-3 p-2 fw-bold">Users</div> --}}
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Users</div>
                        <div class="card-tools">
                            <button class="btn btn-transparent text-light" data-bs-target='#new-user-modal' data-bs-toggle='modal'><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-select form-control rounded-0" name="" id="filter-users">
                                    <option value="" selected hidden>Filter</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Bookkeeper">Bookkeeper</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="clear">Clear Filter</option>
                                </select>
                            </div>
                            <input type="search" class="form-control search-users" name="search-coa" id="search-coa"
                                placeholder="search...">
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped users-table" style="font-size: .9em;">
                            <thead>
                                <tr>
                                    <td class="fw-bold">First Name</td>
                                    <td class="fw-bold">Last Name</td>
                                    <td class="fw-bold">Email</td>
                                    <td class="fw-bold">Role</td>
                                    <td class="fw-bold">PIN</td>
                                    <td class="fw-bold">User Name</td>
                                    <td class="fw-bold">action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr id="{{$user->id}}">
                                        <td>{{$user->FirstName}}</td>
                                        <td>{{$user->LastName}}</td>
                                        <td>{{$user->Email}}</td>
                                        <td>{{$user->Role}}</td>
                                        <td>{{$user->PIN}}</td>
                                        <td>{{$user->UserName}}</td>
                                        <td>
                                            <span id="{{$user->id}}" data-bs-target="#edit-user-modal-{{$user->id}}" data-bs-toggle="modal" class="badge bg-warning fw-bold"><i class="fas fa-pen"></i></span>
                                            <span id="{{$user->id}}" data-bs-target="#remove-user-{{$user->id}}" data-bs-toggle="modal" class="badge bg-warning fw-bold"><i class="fas fa-trash"></i></span>
                                        </td>
                                    </tr>
                                @include('modals.edit-user-modal')
                                @include('modals.remove-user-modal')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('modals.new-user-modal')
        </div>
    @endsection

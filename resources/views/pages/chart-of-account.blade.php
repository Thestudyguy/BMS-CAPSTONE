@extends('layout')

@section('content')
    <div class="container-fluid p-5 my-5">
        <div class="container">
            <h6 class="h4 fw-bold">Chart of Accounts</h6>
            <button data-bs-target='#new-COA' data-bs-toggle='modal' class="btn ml-3" style="background: #063D58; color: whitesmoke; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">Add new <i class="fas fa-plus"></i></button>
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <td>test</td>
                                <td>test</td>
                                <td>test</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>test</td>
                                <td>test</td>
                                <td>test</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('modals.create-new-COA')
@endsection

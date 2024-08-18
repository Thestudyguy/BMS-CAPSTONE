@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="lead fw-bold mb-3 p-2">Activity Log</div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card collapsed-card">
                    <div class="card-header">
                        User's Activity
                        <div class="card-tools">
                            <div class="card-tools">
                                <button class="btn btn-tool" data-card-widget='collapse'>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    @endsection

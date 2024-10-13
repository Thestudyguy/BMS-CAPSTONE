@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        <div class="lead fw-bold mb-3 p-2 fw-bold">Activity Log</div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Logs</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover activity-log-table">
                            <thead>
                                <tr>
                                    <td>User</td>
                                    <td>User Agent</td>
                                    <td>Activity Type</td>
                                    <td>Activity Description</td>
                                    <td>Time Stamps</td>
                                    <td>Device</td>
                                    <td>Browser</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dave</td>
                                    <td>Windows NT 10.0; Win64; x64</td>
                                    <td>Remove</td>
                                    <td>Removed a Client</td>
                                    <td>October 1, 2024 1:00PM</td>
                                    <td>Windows 11</td>
                                    <td>Google Chrome</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>Test</td>
                                    <td>Test</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
    @endsection

@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5">
        {{-- <div class="lead fw-bold mb-3 p-2 fw-bold">Activity Log</div> --}}
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Activity Logs</div>
                    </div>
                    <div class="card-body">
          <div style="overflow-x: auto; max-height: 400px;">
                        <table class="table table-hover activity-log-table" style="font-size: 0.7em">
                            <thead>
                                <tr>
                                    <td>User</td>
                                    <td>User Agent</td>
                                    <td>Activity Type</td>
                                    <td>Activity Description</td>
                                    <td>Action</td>
                                    <td>Time Stamps</td>
                                    <td>Browser</td>
                                    <td>Platform</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{$log->LastName}}, {{$log->FirstName}} - {{$log->Role}}</td>
                                        <td>{{$log->user_agent}}</td>
                                        <td>{{$log->action}}</td>
                                        <td>{{$log->activity}}</td>
                                        <td>{{$log->description}}</td>
                                        <td>{{$log->created_at}}</td>
                                        <td>{{$log->browser}}</td>
                                        <td>{{$log->platform}}/{{$log->platform_version}}</td>
                                    </tr>
                                    {{-- <tr data-widget='expandable-table' aria-expanded="false">
                                        <td>{{$log->LastName}}, {{$log->FirstName}} - {{$log->Role}}</td>
                                        <tr class="expandable-body bg-light">
                                            <td>
                                                <div class="">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <td>User</td>
                                                            <td>User Agent</td>
                                                            <td>Activity Type</td>
                                                            <td>Activity Description</td>
                                                            <td>Time Stamps</td>
                                                            <td>Browser</td>
                                                            <td>Platform</td>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{$log->LastName}}, {{$log->FirstName}} - {{$log->Role}}</td>
                                                                <td>{{$log->user_agent}}</td>
                                                                <td>{{$log->action}}</td>
                                                                <td>{{$log->activity}}</td>
                                                                <td>{{$log->created_at}}</td>
                                                                <td>{{$log->browser}}</td>
                                                                <td>{{$log->platform}}/{{$log->platform_version}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    
                                    </tr> --}}
                                @endforeach
                            </tbody>
                        </table>
          </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

@extends('layout')

@section('content')
    <div class="container-fluid mt-5 pt-5 external-services">
        <div class="container client-profile-details">
            <div class="row">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            {{-- <h6 class="h5 fw-bold">Client Profile</h6> --}}
                            <div class="card elevation-0" style="background: transparent;">
                                <div class="card-body">
                                    @if ($clientProfile && $clientProfile->image_path)
                                        <img src="{{ asset('storage/' . $clientProfile->image_path) }}" 
                                             alt="Company Profile Image" width="100">
                                    @else
                                        <img src="default-image-path.jpg" alt="Default Image">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h6 class="h5 fw-bold">CEO Information</h6>
                            <div class="card">
                                <div class="card-body" style="color: #063D58;">
                                    <div class="fw-bold">{{ $client->CEO }}</div>
                                    <div class="fw-bold my-1">{{ $client->CEODateOfBirth }}</div>
                                    <div class="fw-bold">{{ $client->CEOContactInformation }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h6 class="h5 fw-bold">Representative Information</h6>
                            <div class="card">
                                <div class="card-body" style="color: #063D58;">
                                    @foreach ($repInfo as $repInfoData)
                                        <div class="fw-bold">{{ $repInfoData->RepresentativeName }}</div>
                                        <div class="fw-bold my-1">{{ $repInfoData->RepresentativeContactInformation }}</div>
                                        <div class="fw-bold">{{ $repInfoData->RepresentativeDateOfBirth }}</div>
                                        <div class="fw-bold my-1">{{ $repInfoData->RepresentativePosition }}</div>
                                        <div class="fw-bold">{{ $repInfoData->RepresentativeAddress }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h6 class="h5 fw-bold">Created At</h6>
                            <div class="card">
                                <div class="card-body" style="color: #063D58;">
                                    <div class="fw-bold my-1">{{ \Carbon\Carbon::parse($client->created_at)->format('F j, Y g:i A') }}</div>
                                    <div class="fw-bold my-1">Created By: {{ $user ? $user->role . ' ' . ' - '. $user->id : 'Data entry user not found.' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6 class="h5 fw-bold">Services</h6>
                            <div class="card">
                                <div class="card-body" style="max-height: 400px; overflow: auto;">
                                    <table class="table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <td class="fw-bold">Service Name</td>
                                                <td class="fw-bold">Service Progress</td>
                                                <td class="fw-bold">Original File Name</td>
                                                <td class="fw-bold">MIME Type</td>
                                                <td class="fw-bold">File Size</td>
                                                {{-- <td class="fw-bold">File Path</td> --}}
                                                <td class="fw-bold">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clientServices as $service)
                                                <tr>
                                                    <td>{{ $service->ClientService }}</td>
                                                    <td>{{ $service->ClientServiceProgress }}</td>
                                                    <td>{{ $service->getClientOriginalName ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getClientMimeType ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getSize ?: 'No file provided' }}</td>
                                                    {{-- <td>{{ $service->getRealPath ?: 'No file provided' }}</td> --}}
                                                    <td>
                                                        @if ($service->getClientOriginalName)
                                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Download File</span>
                                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Remove Service</span>
                                                        @else
                                                            <span class="text-muted">No Action</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No services available for this client.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h6 class="h5 fw-bold">Journal Entries</h6>
                            <div class="card">
                                <div class="card-body" style="max-height: 400px; overflow: auto;">
                                    <table class="table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <td class="fw-bold">Service Name</td>
                                                <td class="fw-bold">Service Progress</td>
                                                <td class="fw-bold">Original File Name</td>
                                                <td class="fw-bold">MIME Type</td>
                                                <td class="fw-bold">File Size</td>
                                                <td class="fw-bold">File Path</td>
                                                <td class="fw-bold">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clientServices as $service)
                                                <tr>
                                                    <td>{{ $service->ClientService }}</td>
                                                    <td>{{ $service->ClientServiceProgress }}</td>
                                                    <td>{{ $service->getClientOriginalName ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getClientMimeType ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getSize ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getRealPath ?: 'No file provided' }}</td>
                                                    <td>
                                                        @if ($service->getClientOriginalName)
                                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Download File</span>
                                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Remove Service</span>
                                                        @else
                                                            <span class="text-muted">No Action</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No services available for this client.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h6 class="h5 fw-bold">Bookkeeping</h6>
                            <div class="card">
                                <div class="card-body" style="max-height: 400px; overflow: auto;">
                                    <table class="table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <td class="fw-bold">Service Name</td>
                                                <td class="fw-bold">Service Progress</td>
                                                <td class="fw-bold">Original File Name</td>
                                                <td class="fw-bold">MIME Type</td>
                                                <td class="fw-bold">File Size</td>
                                                {{-- <td class="fw-bold">File Path</td> --}}
                                                <td class="fw-bold">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clientServices as $service)
                                                <tr>
                                                    <td>{{ $service->ClientService }}</td>
                                                    <td>{{ $service->ClientServiceProgress }}</td>
                                                    <td>{{ $service->getClientOriginalName ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getClientMimeType ?: 'No file provided' }}</td>
                                                    <td>{{ $service->getSize ?: 'No file provided' }}</td>
                                                    {{-- <td>{{ $service->getRealPath ?: 'No file provided' }}</td> --}}
                                                    <td>
                                                        @if ($service->getClientOriginalName)
                                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Download File</span>
                                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">Remove Service</span>
                                                        @else
                                                            <span class="text-muted">No Action</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No services available for this client.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

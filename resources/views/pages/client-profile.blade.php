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
                                    <div class="client-company-profile-container">
                                        @if ($clientProfile && $clientProfile->image_path)
                                            <center><img src="{{ asset('storage/' . $clientProfile->image_path) }}" 
                                                alt="Company Profile Image" width="100" style="border-radius: 50%; border: 5px solid #063D58;"></center>
                                                <div class="update-company-profile"><button class="btn rounded-circle btn-secondary btn-sm" data-bs-target="#edit-company-profile-{{$client->id}}" data-bs-toggle="modal" style="position: absolute; top: 65%; left: 50%; color: black;"><i class="fas fa-pen fw-bold"></i></button></div>
                                        @else
                                            <img src="default-image-path.jpg" alt="Default Image">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @include('modals.edit-company-profile')
                        </div>
                        <div class="col-sm-12">
                           
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title lead fw-bold text-light">Company Information</div>
                                    <div class="card-tools"><button class="btn btn-tranparent mt-0 pt-0" data-bs-target="#edit-company-profile-{{$client->id}}" data-bs-toggle="modal" id="{{ $client->id }}"><i class="fas fa-pen text-light" style="font-size: 70%;"></i></button></div>
                                </div>
                                <div class="card-body" style="color: #063D58;">
                                    <div class="fw-bold">{{ $client->CompanyName }}</div>
                                    <div class="fw-bold my-1">{{ $client->CompanyAddress }}</div>
                                    <div class="fw-bold">{{ $client->CompanyEmail }}</div>
                                    <div class="fw-bold">Tin: {{ $client->TIN }}</div>
                                    @include('modals.edit-company-info')
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                           
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title lead fw-bold text-light">CEO Information</div>
                                    <div class="card-tools"><button class="btn btn-tranparent mt-0 pt-0" id="{{ $client->id }}"><i class="fas fa-pen text-light" style="font-size: 70%;"></i></button></div>
                                </div>
                                <div class="card-body" style="color: #063D58;">
                                    <div class="fw-bold">{{ $client->CEO }}</div>
                                    <div class="fw-bold my-1">{{ $client->CEODateOfBirth }}</div>
                                    <div class="fw-bold">{{ $client->CEOContactInformation }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            {{-- <h6 class="h5 fw-bold">Representative Information</h6> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title lead fw-bold text-light">Representative Information</div>
                                    <div class="card-tools"><button class="btn btn-tranparent mt-0 pt-0" id="{{ $client->id }}"><i class="fas fa-pen text-light" style="font-size: 70%;"></i></button></div>
                                </div>
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
                            {{-- <h6 class="h5 fw-bold">Created At</h6> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="lead fw-bold text-light">Created At</div>
                                </div>
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
                            {{-- <h6 class="h5 fw-bold">Services</h6> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="lead fw-bold text-light">Services</div>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow: auto;">
                                    <center>
                                        <table class="table-hover table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <td class="fw-bold">Service Name</td>
                                                    <td class="fw-bold">Service Progress</td>
                                                    <td class="fw-bold">Original File Name</td>
                                                    <td class="fw-bold">MIME Type</td>
                                                    <td class="fw-bold">File Size</td>
                                                    <td class="fw-bold">Status</td>
                                                    {{-- <td class="fw-bold">File Path</td> --}}
                                                    <td class="fw-bold">Action</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($clientServices->isEmpty())
                                                <tr>
                                                    <td colspan="7" class="text-center">No services available for this client.</td>
                                                </tr>
                                            @else
                                            @foreach($clientServices as $service)
                                            <tr>
                                                <td>{{ $service->ClientService }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($service->ClientServiceProgress == 'Pending') bg-secondary 
                                                        @elseif($service->ClientServiceProgress == 'On progress') bg-primary 
                                                        @elseif($service->ClientServiceProgress == 'Done') bg-success 
                                                        @elseif($service->ClientServiceProgress == 'Paid') bg-dark 
                                                        @endif">
                                                        <strong>{{ ucfirst($service->ClientServiceProgress) }}</strong>
                                                    </span>
                                                </td>
                                                <td>{{ $service->getClientOriginalName ?: 'No file provided' }}</td>
                                                <td>{{ $service->getClientMimeType ?: 'No file provided' }}</td>
                                                <td>{{ $service->getSize ?: 'No file provided' }}</td>
                                                <td>
                                                    @if ($service->ClientServiceProgress != 'Paid')
                                                        <span class="badge bg-warning" 
                                                            data-bs-target="#update-client-service-{{ $service->id }}" 
                                                            data-bs-toggle="modal">
                                                            <strong>{{ $service->ClientServiceProgress }}</strong>
                                                        </span>
                                                    @else
                                                        <span class="badge bg-dark text-light">
                                                            <strong>Paid</strong>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($service->getClientOriginalName == null)
                                                        <span class="text-muted">No Action</span>
                                                    @else
                                                        <a href="{{ asset('storage/' . $service->getClientOriginalName) }}" 
                                                            download="{{ basename($service->getClientOriginalName) }}" 
                                                            class="badge bg-warning text-dark" 
                                                            style="font-size: 10px;">
                                                            <i class="fas fa-cloud-download-alt"></i> Download
                                                        </a>
                                                        <span class="badge bg-danger text-light" style="font-size: 10px;">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @include('modals.update-client-service-progress')
                                            @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            {{-- <h6 class="h5 fw-bold">Journal Entries</h6> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="lead fw-bold text-light">Journal Entries</div>
                                </div>
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
                            {{-- <h6 class="h5 fw-bold">Bookkeeping</h6> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="lead fw-bold text-light">Billings</div>
                                </div>
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

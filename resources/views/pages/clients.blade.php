@extends('layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="row">
          <div class="col-12">
            <div class="card mt-5">
              <div class="card-header clients-table-data">
                <div class="card-title fw-bold lead">Clients</div>
                  <div class="card-tools">
                      <a href="{{route('new-client-form')}}">
                          <button class="btn btn-rounded-0 new-client-button">
                              <i class="fas fa-plus"></i>
                          </button>
                      </a>
                  </div>
              </div>
              <div class="card-body" style="max-height: 600px; overflow-x: auto;">
                  <table class="table table-hover table-striped table-bordered">
                    <thead>
                      <tr class="text-sm fw-bold">
                        <td class="fw-bold">Company</td>
                        <td class="fw-bold">Owner</td>
                        <td class="fw-bold">Contact</td>
                        <td class="fw-bold">Actions</td>
                      </tr>
                    </thead>
                      <tbody>
                          @foreach ($clients as $client)
                          @if (!$client->AccountCategory)
                          <tr>
                            <td>
                              {{$client->CompanyName}}
                              <div class="action-icons float-right visually-hidden">
                              </div>
                            </td>
                            <td>{{$client->CEO}}</td>
                            <td>{{$client->CompanyEmail}}</td>
                            <td>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('add-services', ['id' => $client->id]) }}'">Add Service</span>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-billing', ['id' => $client->id]) }}'">Billings</span>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-journal', ['id' => $client->id]) }}'">Journal</span>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-profile', ['id' => $client->id]) }}'">Vew Client Profile</span>
                            </td>
                          </tr>
                          @else
                          @endif
                             
                          @endforeach
                      </tbody>
                    </table>
              </div>
          </div>
          </div>
          <div class="col-12">
            <div class="card mt-5">
              <div class="card-header clients-table-data">
                <div class="card-title fw-bold lead">Firm</div>
                  <div class="card-tools">
                      <a href="{{route('new-client-form')}}">
                          <button class="btn btn-rounded-0 new-client-button">
                              <i class="fas fa-plus"></i>
                          </button>
                      </a>
                  </div>
              </div>
              <div class="card-body" style="max-height: 600px; overflow-x: auto;">
                  <table class="table table-hover table-striped table-bordered">
                    <thead>
                      <tr class="text-sm fw-bold">
                        <td class="fw-bold">Company</td>
                        <td class="fw-bold">Owner</td>
                        <td class="fw-bold">Contact</td>
                        <td class="fw-bold">Actions</td>
                      </tr>
                    </thead>
                      <tbody>
                          @foreach ($clients as $client)
                          @if ($client->AccountCategory)
                          <tr>
                            <td>
                              {{$client->CompanyName}}
                              <div class="action-icons float-right visually-hidden">
                              </div>
                            </td>
                            <td>{{$client->CEO}}</td>
                            <td>{{$client->CompanyEmail}}</td>
                            <td>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('add-services', ['id' => $client->id]) }}'">Add Service</span>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-billing', ['id' => $client->id]) }}'">Billings</span>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-journal', ['id' => $client->id]) }}'">Journal</span>
                              <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-profile', ['id' => $client->id]) }}'">Vew Client Profile</span>
                            </td>
                          </tr>
                          @else
                          @endif
                          @endforeach
                      </tbody>
                    </table>
              </div>
          </div>
          </div>
        </div>
    </div>
@endsection

@extends('layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="row">
          <div class="col">
            <div class="card mt-5">
              <div class="card-header clients-table-data">
                <div class="card-title fw-bold lead">Clients</div>
                  <div class="card-tools">
                      {{-- <button class="btn rounded-0 new-client-button" data-bs-target="#new-client-modal"
                          data-bs-toggle="modal">
                          <i class="fas fa-plus"></i>
                      </button> --}}
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
                              <tr>
                                {{-- data-widget="expandable-table" class="client-table-data" aria-expanded="false" --}}
                                  <td>
                                    {{$client->CompanyName}}
                                    <div class="action-icons float-right visually-hidden">
                                      {{-- <i class="fas fa-pen text-secondary opacity-50"></i>
                                      <i class="fas fa-trash text-secondary opacity-50"></i>
                                      <i id="view-client-{{$client->id}}" class="fas fa-eye text-secondary opacity-50"></i> --}}
                                    </div>
                                  </td>
                                  <td>{{$client->CEO}}</td>
                                  <td>{{$client->CompanyEmail}}</td>
                                  <td>
                                    <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('add-services', ['id' => $client->id]) }}'">Add Service</span>
                                    {{-- <span class="badge bg-warning text-dark fw-bold">Generate FS</span> --}}
                                    {{-- <span class="badge bg-warning text-dark fw-bold">Generate FP</span> --}}
                                    <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-billing', ['id' => $client->id]) }}'">Billings</span>
                                    <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-journal', ['id' => $client->id]) }}'">Journal</span>
                                    <span class="badge bg-warning text-dark fw-bold" id="{{$client->id}}" onclick="window.location.href='{{ route('client-profile', ['id' => $client->id]) }}'">Vew Client Profile</span>
                                  </td>
                                </tr>
                                {{-- <tr class="expandable-body">
                                  <td>
                                    <div class="p-0">
                                      <table class="table table-hover">
                                        <tbody>
                                          <tr class="client-sub-table" data-widget="expandable-table" aria-expanded="false" id="services-{{$client->id}}">
                                            <td onclick="window.location.href='{{ route('add-client-services') }}'" style="cursor: pointer;">
                                                Services
                                            </td>
                                          </tr>
                                          <tr class="expandable-body">
                                            <td>
                                              <div class="p-0">
                                                <table class="table table-hover">
                                                  <tbody class="services">
                                                    <tr>
                                                      <td class="services-loader loader-td visually-hidden">
                                                          <div class="loader"></div>
                                                      </td>
                                                      <td class="">
                                                        test
                                                    </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </td>
                                          </tr>
                                          <tr class="client-sub-table" data-widget="expandable-table" aria-expanded="false" id="accounting-{{$client->id}}">
                                            <td>
                                              Accounting
                                            </td>
                                          </tr>
                                          <tr class="expandable-body">
                                            <td>
                                              <div class="p-0">
                                                <table class="table table-hover">
                                                  <tbody class="accounting">
                                                    <tr>
                                                      <td class="accounting-loader loader-td visually-hidden">
                                                          <div class="loader asd"></div>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </td>
                                          </tr>
                                          <tr class="client-sub-table" data-widget="expandable-table" aria-expanded="false" id="billings-{{$client->id}}">
                                              <td>
                                                Billing Statements
                                              </td>
                                            </tr>
                                            <tr class="expandable-body">
                                              <td>
                                                <div class="p-0">
                                                  <table class="table table-hover">
                                                    <tbody class="billings">
                                                      <tr>
                                                        <td class="billing-loader loader-td visually-hidden">
                                                          <div class="loader"></div>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </div>
                                              </td>
                                            </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr> --}}
                          @endforeach
                      </tbody>
                    </table>
              </div>
          </div>
          </div>
          {{-- <div class="col-sm-4 pt-5">
            <div class="card">
              <div class="card-body text-center">
                <span class="badge text-center text-light" style="background: #063D58;"><strong>Click any client to view details</strong></span>
              </div>
            </div>
          </div> --}}
        </div>
    </div>
@endsection

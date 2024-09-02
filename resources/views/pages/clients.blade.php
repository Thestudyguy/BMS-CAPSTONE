@extends('layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mt-5">
            <div class="card-header clients-table-data">
                <div class="card-title lead fw-bold">Clients</div>
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
                <table class="table table-hover">
                    <tbody>
                        @foreach ($clients as $client)
                            <tr data-widget="expandable-table" class="client-table-data" aria-expanded="false">
                                <td>
                                  {{$client->CEO}} <b>-</b> {{$client->CompanyName}}
                                  <div class="action-icons float-right visually-hidden">
                                    <i class="fas fa-pen"></i>
                                    <i class="fas fa-trash"></i>
                                  </div>
                                </td>
                              </tr>
                              <tr class="expandable-body">
                                <td>
                                  <div class="p-0">
                                    <table class="table table-hover">
                                      <tbody>
                                        <tr class="client-sub-table" data-widget="expandable-table" aria-expanded="false" id="services-{{$client->id}}">
                                          <td>
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
                              </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>

            @include('modals.remove-selected-client')
            @include('modals.new-client-modal')
        </div>
    </div>
@endsection

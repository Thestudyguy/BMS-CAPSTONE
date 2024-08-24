@extends('layout')

@section('content')
    <div class="container-fluid mt-5 pt-5 external-services">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">External Services</div>
                            <div class="card-tools">
                                <button class="btn new-client-modal" data-bs-target='#new-service-modal' data-bs-toggle='modal'>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 600px; overflow-x: auto;">
                            <table class="table table-hover opacity-50  external-services-action-icons">
                                @foreach ($services as $service)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>
                                            {{$service->Service}} <b>|</b> {{number_format($service->Price, 2)}}
                                            <span class="float-right px-2 action-icons visually-hidden text-sm">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                            <span class="float-right text-sm action-icons visually-hidden">
                                                <i class="fas fa-file-import"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body cheque-expandable-body bg-light">
                                        <td>
                                            <div class="p-0 text-center expandable-body-append-table">
                                                {{-- <table class="table table-hover float-left">
                                                    <thead class="text-left">
                                                        <tr>
                                                            <td>Pre Requisite</td>
                                                            <td>Requirements</td>
                                                            <td>Price</td>
                                                        </tr>
                                                    </thead> 
                                                    <tbody class="text-left">
                                                            <tr>
                                                                <td>Community Tax Cedula</td>
                                                                <td>Business Tax Cedula</td>
                                                                <td>250P</td>
                                                            </tr>
                                                    </tbody>
                                                </table> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            Service Details
                            <div class="card-tools">
                                <button class="btn service-detail-expand-button" data-card-widget='collapse'><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body"></div>
                    </div>
                </div>
            </div>
    </div>
@endsection
{{-- services --}}
{{-- <div class="col-sm-6 services">
    <div class="row">
        
    </div>
</div> --}}
{{-- end of services --}}



{{-- services details --}}
{{-- <div class="col-sm-6 service-details">

</div> --}}
{{-- end of services details --}}

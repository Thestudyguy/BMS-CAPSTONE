@extends('layout')

@section('content')
    <div class="container-fluid p-5 mt-5 external-services">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">External Services</div>
                    <div class="card-tools">
                        <button class="btn new-client-modal" data-bs-target='#new-service-modal' data-bs-toggle='modal'>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover opacity-50">
                        <tr class="" data-widget="expandable-table" aria-expanded="false">
                            <td>
                                Business Permit Processing/Renewal
                                <span class="float-right px-2 text-sm">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span class="float-right text-sm">
                                    <i class="fas fa-file-import"></i>
                                </span>
                            </td>
                        </tr>
                        <tr class="expandable-body cheque-expandable-body bg-light">
                            <td>
                                <div class="p-0 text-center expandable-body-append-table">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <td>Requirement</td>
                                                <td>Price</td>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                                <tr>
                                                    <td>Business Tax Cedula</td>
                                                    <td>250P</td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
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

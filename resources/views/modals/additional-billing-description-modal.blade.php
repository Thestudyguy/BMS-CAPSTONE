<div class="modal fade" id="adbd">
    <div class="modal-dialog modal-center modal-lg">
        <div class="modal-content rounded-0">
            <div class="sub-service-loader visually-hidden" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.8); z-index: 10; display: flex; justify-content:center; align-items: center;">
                <div class="loader"></div>
            </div>
            <div class="modal-header rounded-0" style="background: #063D58;">
                <div class="modal-title fw-bold text-light">
                    Additional Account Descriptions
                </div>
                <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover table-striped" style="font-size: .8em;">
                    <thead>
                        <tr>
                            <td class="fw-bold">Under Service</td>
                            <td class="fw-bold">Account Type</td>
                            <td class="fw-bold">Category</td>
                            <td class="fw-bold">Description</td>
                            <td class="fw-bold">Tax Type</td>
                            <td class="fw-bold">Form Type</td>
                            <td class="fw-bold">Price</td>
                            <td class="fw-bold">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ads as $ad)
                        <tr id="{{$ad->id}}" data-service="{{$ad->Service}}" data-requirements="{{$ad->ServiceRequirements}}" data-category="{{$ad->adCategory}}" data-description="{{$ad->Description}}" data-taxtype="{{$ad->TaxType}}" data-formtype="{{$ad->FormType}}" data-price="{{ $ad->Price }}">
                            <td>{{$ad->Service}}</td>
                            <td>{{$ad->ServiceRequirements}}</td>
                            <td>{{$ad->adCategory}}</td>
                            <td>{{$ad->Description}}</td>
                            <td>{{$ad->TaxType}}</td>
                            <td class="modal-ad-price">₱{{ number_format($ad->Price, 2) }}</td>
                            <td>{{$ad->FormType}}</td>
                            <td>
                                <span class="badge fw-bolder add-selected-description-{{$ad->id}} bg-warning add-description" style="cursor: pointer;">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-light fw-bold rounded-0" data-bs-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

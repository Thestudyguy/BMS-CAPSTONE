<div class="modal fade" id="edit-company-profile-{{$client->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background: #063D58;">
                <h4 class="lead text-light fw-bold">Edit Company Profile - {{ $client->CEO }}, {{ $client->CompanyName }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('update-company-profile') }}" 
                      method="POST" enctype="multipart/form-data" id="edit-company-info-{{$client->id}}">
                    @csrf
                    @method('PUT')

                    <center>
                        <img id="profilePreview-{{$client->id}}" 
                            src="{{ asset('storage/' . $clientProfile->image_path) }}" 
                            alt="Company Profile Image" 
                            width="100" 
                            style="border-radius: 50%; border: 5px solid #063D58; cursor: pointer;"
                            onclick="document.getElementById('profileInput-{{$client->id}}').click();">
                    </center>
                    
                    <input type="file" 
       id="profileInput-{{$client->id}}" 
       name="profile" 
       accept="image/*" 
       style="display: none;" 
       onchange="previewImage(event, 'profilePreview-{{$client->id}}')">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <div class="mt-3">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" 
                        style="background: #063D58;" 
                        class="btn fw-bold text-light rounded-0" 
                        form="edit-company-info-{{$client->id}}">{{ __('Update') }}</button>
                <button type="button" 
                        class="btn btn-secondary rounded-0 fw-bold text-light" 
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

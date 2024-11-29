<div class="modal fade" id="view-service-docs-{{$service->id}}_{{$service->serviceCategory}}">
    <div class="modal-dialog modal-center modal-lg">
      <div class="modal-content rounded-0">
        <div class="service-docs-loader visually-hidden" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.8); z-index: 10; display: flex; justify-content:center; align-items: center;">
            <div class="loader"></div>
        </div>
        <div class="modal-header text-light rounded-0 fw-bold" style="background: #063D58;">
          {{$client->CompanyName}} Service Documents / {{$service->ClientService}}
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-md">
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Req Name</td>
                    <td>File Name</td>
                    <td>Mime</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody class="append-service-docs">
                
            </tbody>
           </table>
        </div>
        <div class="modal-footer">
            <button class="btn fw-bold text-md text-light rounded-0 save-req-files" style="background: #063D58;">Save</button>
          <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">{{__('Cancel')}}</button>
          </div>
      </div>
    </div>
  </div>  
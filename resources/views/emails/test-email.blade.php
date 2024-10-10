<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="lead">URL: hoy mao ni imo service {{$test}} </h5>
        <h5 class="lead text-primary mr-2" id="title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{$test}}
        {{$testemail}}

          {{-- <div class="spinner-border text-secondary" role="status" id="loadingIndicatorEmailModal" style="display: none; align-items: center; justify-content: center; font-size: 12px;">
            <span class="visually-hidden">Loading...</span>
            <div class="debug"></div> --}}
        </div>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary">Save</button> --}}
        </div>
      </div>
    </div>
  </div>
  
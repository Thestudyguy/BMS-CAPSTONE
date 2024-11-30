<div class="modal fade" id="journal-note-{{$journal->id}}">
    <div class="modal-dialog modal-center">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0 text-light" style="background: #063D58">
                <div class="modal-title fw-bold">
                    Note From: {{$journal->accountantLname}}, {{$journal->accountantFname}} - {{$journal->accountantRole}}
                </div>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> --}}
            </div>
            <div class="modal-body">

                <div class="card-body" style="
                background-color: #f9fafb; /* Light neutral background */
                color: #333; /* Dark text for good contrast */
                padding: 20px; /* Comfortable spacing */
                font-size: 1rem; /* Readable text size */
                border-left: 4px solid #063D58; /* Accent border for focus */
                border-radius: 0.25rem; /* Slight rounding for softness */
                line-height: 1.6; /* Good text readability */
                font-family: 'Arial', sans-serif;">
                <strong>Note:</strong> {{$journal->note}}

                </div>
            </div>
            <div class="modal-footer">
                <span class="fw-normal text-sm float-left text-dark lead">{{ \Carbon\Carbon::parse($journal->NoteTimeStamp)->format('F d, Y \a\t h:i A') }}</span>
                {{-- <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">
                    {{__('Close')}}
                </button> --}}
            </div>
        </div>
    </div>
</div>

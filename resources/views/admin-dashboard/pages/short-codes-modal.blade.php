<div class="modal-header">
    <h5 class="modal-title">Available Short Codes</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross-sm"></em>
    </button>
</div>
<div class="modal-body">
    <ol>
        @foreach ($shortCodes as $key => $shortCode)
            <li>{{ $shortCode }}</li>
        @endforeach
    </ol>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-primary text-uppercase">Close</button>
</div>

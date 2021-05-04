@php
    $isEdit = isset($translation) ? true : false;
    $url = $isEdit ? route('translations.update', $translation->id) : route('translations.store');

    $encoded = json_encode($translation->text);
    $decoded = json_decode($encoded, true);
@endphp
<form data-form="ajax-form" method="post" action="{{ $url }}" data-modal="#ajax_model" data-datatable="#translations-table">
    @csrf
    @if($isEdit)
        @method('put')
    @endif
    <div class="modal-body">
        @if(!$isEdit)
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="form-group position-relative mb-3">
                        <label for="validationTooltip01">Group</label>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="form-group">
                        <input type="text" name="group" class="form-control" placeholder="Group" required>
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                        <div class="invalid-tooltip">
                            This field is required
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="form-group position-relative mb-3">
                        <label for="validationTooltip01">Key</label>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="form-group">
                        <input type="text" name="key" class="form-control" placeholder="Key" required>
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                        <div class="invalid-tooltip">
                            This field is required
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group position-relative mb-3">
                    <label for="validationTooltip01">Name <small>(en)</small></label>
                </div>
            </div>
            <div class="col-md-9 col-sm-9">
                <div class="form-group">
                    <input type="text" name="text[en]" class="form-control" placeholder="Translation (en)" value="{{ $isEdit ? $decoded['en'] : '' }}" required>
                    <div class="valid-tooltip">
                        Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        This field is required
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-light" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success" data-button="submit">Save</button>
    </div>
</form>

<script>
    
</script>

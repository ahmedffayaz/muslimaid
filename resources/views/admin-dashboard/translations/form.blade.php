@php
    $isEdit = isset($translation) ? true : false;
    $url = $isEdit ? route('translations.update', $translation->id) : route('translations.store');
@endphp

<form action="{{ $url }}" class="needs-validation" novalidate method="post">
    @csrf
    @if ($isEdit)
        @method('put')
    @else
        <div class="row">
            <div class="col-md-6">
                <div class="form-group position-relative mb-3">
                    <label for="validationTooltip01">Group</label>
                    <input type="text" name="group" class="form-control" placeholder="Group" required>
                    <div class="valid-tooltip">
                        Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        This field is required
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group position-relative mb-3">
                    <label for="validationTooltip01">Key</label>
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
        @foreach($languages as $language)
            @if ($language->code === 'en')
                <div class="col-md-6">
                    <div class="form-group position-relative mb-3">
                        <label for="validationTooltip01">{{ $language->name }}</label>
                        <input type="text" name="text[{{ $language->code }}]" class="form-control" placeholder="{{ $language->name }} Translation" value="{{ $isEdit ? $translation->text[$language->code] : '' }}" required>
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                        <div class="invalid-tooltip">
                            This field is required
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <div class="form-group position-relative mb-3">
                        <label for="validationTooltip01">{{ $language->name }}</label>
                        <input type="text" name="text[{{ $language->code }}]" class="form-control" placeholder="{{ $language->name }} Translation" value="{{ $isEdit ? $translation->text[$language->code] : '' }}">
                    </div>
                </div>
            @endif
        @endforeach
        <div class="col-lg-12">
            <input class="btn btn-primary" type="submit" value="{{ $isEdit ? 'Update' : 'Add' }}" />
        </div>
    </div>
</form>
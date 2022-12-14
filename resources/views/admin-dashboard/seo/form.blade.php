@php
    $isEdit = isset($seoData) ? true : false;
    // $url = $isEdit ? route('admin.seo.update', $seoData) : route('admin.seo.store');
@endphp
<!-- Modal Form -->
<form class="form-validate is-alter" method="POST" id="save_modal_form">
    @csrf
    @if($isEdit)
        @method('put')
    @endif
    <input type="hidden" value="" id="id">
    <div class="modal-body">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="reviewer">URL</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">{{ url('/') }}</span>
                            </div>
                            <input type="text" class="form-control" id="blog-title" name="url" placeholder="URL" value="{{ $isEdit ? str_replace(url('/'), '', $seoData->url) :  '/' }}" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Button --}}
            <div class="col-lg-12" style="margin-bottom: 20px;">
                <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-primary" style="float: right;" id="append_fields">
                    <em class="icon ni ni-plus"></em>
                </a>
            </div>
            {{-- End --}}
        </div>
        <div class="modal-corsi">
            @if ($isEdit)
                <div class="col-lg-11">
                    @foreach ($seoData->ruleData as $key => $rule_data)
                        <div>
                            <div class="row gy-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label" for="key-{{ $loop->index + 1 }}">Choose Key</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select form-control" id="key-{{ $loop->index + 1 }}}" name="type[{{ $loop->index + 1 }}}][key]">
                                                <option selected disabled>Choose key</option>
                                                <option value="meta_title" {{ $rule_data->key == 'meta_title' ? 'selected' : '' }}>Meta: Title</option>
                                                <option value="meta_description" {{ $rule_data->key == 'meta_description' ? 'selected' : '' }}>Meta: Description</option>
                                                <option value="meta_keyword" {{ $rule_data->key == 'meta_keyword' ? 'selected' : '' }}>Meta: Keyword</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Value</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" id="default-01" name="type[{{ $loop->index + 1 }}}][value]" rows="3" placeholder="Value" required>{{$rule_data->value}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12" style="margin-left: 50px;">
                                <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-danger delBtn" style="float: right;" data-type_counter="{{ $loop->index + 1 }}">
                                    <em class="icon ni ni-minus"></em>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="col-lg-11" id="corsi"></div>
        </div>

        <div class="col-12 mt-3">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" id='save-btn'></button>
            </div>
        </div>
    </div>
</form>

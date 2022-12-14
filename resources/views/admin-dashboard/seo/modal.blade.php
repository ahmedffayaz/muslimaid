@php
    $isEdit = isset($seoData) ? true : false;
    // $url = $isEdit ? route('admin.seo.update', $seoData) : route('admin.seo.store');
@endphp
<!-- Modal Form -->
<div class="modal fade show" tabindex="-1" id="modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$isEdit ? 'Edit Seo' : 'Add Seo'}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form class="form-validate is-alter" method="POST" id="save_modal_form">
                @csrf
                @if($isEdit)
                    @method('put')
                @endif
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
                                        <input type="text" class="form-control" id="blog-title" name="url" placeholder="URL" value="{{ $isEdit ? $seoData->url :  '/' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Add Button --}}
                        <div class="col-lg-12" style="color: green; margin-bottom: 20px;">
                            <em class="icon ni ni-plus-c append_fields" style="float: right;" id="append_fields"></em>
                        </div>
                        {{-- End --}}
                    </div>
                    <div class="modal-corsi">
                        <div class="col-lg-11" id="corsi"></div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id='save-btn'></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

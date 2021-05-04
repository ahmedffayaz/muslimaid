@php
    $url = isset($code) ? route('translation.lines.update', [$translation->id, $code])
        : route('translation.lines.store', [$translation->id]);

    $encoded = json_encode($translation->text);
    $decoded = json_decode($encoded, true);
@endphp
<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Translation</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form action="{{route('admin.lines.store')}}" class="form-validate is-alter" method="POST">
                @csrf
                <input type="hidden" name="translation_id" value="{{$translation->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group"><label class="control-label">Title</label></div>
                        </div>
                        <div class="col-md-9 col-sm-9">
                            <div class="form-group">{{$decoded['en']}}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="language_id">Language</label>
                        <div class="form-control-wrap ">
                            <select class="form-select form-control" data-search="on" id="langugae" name="code">
                                <option disabled selected>Select Language</option>
                                
                                @foreach ($languages as $language)
                                <option value="{{$language['code']}}">{{$language['name']}}</option>
                                @endforeach
                            </select>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="text">Title Translation</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="text" name="text" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id='add-btn'>Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

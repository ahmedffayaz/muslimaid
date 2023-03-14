
<form class="form-validate is-alter" method="POST" id="save_modal_form">
    @csrf
    <input type="hidden" value="{{ $country->id }}" id="id">
    <div class="modal-body">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="name">Title</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Title" value="{{ $country->name  }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
   
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="iso_code">ISO Code</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="text" class="form-control" id="iso_code" name="iso_code" placeholder="ISO Code" value="{{$country->iso_code }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
   
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="region_id">Region</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="region_id" disabled>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" {{ $country->region_id == $region->id ? 'selected':'' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
   
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="currency_id">Currency</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="currency_id" disabled>
                                <option value="0">Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ $country->currency_id == $currency->id ? 'selected' :'' }}>{{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
   
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="upload_type">Upload Type</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="upload_type" id="upload_type" disabled>
                                <option disabled selected>Select Upload Type</option>
                                <option value="upload" {{$country->upload_type == 'upload' ? 'selected':'' }}>Upload</option>
                                <option value="url" {{  $country->upload_type == 'url' ? 'selected':''  }}>URL</option>
                                <option value="code" {{  $country->upload_type == 'code' ? 'selected':'' }}>Code</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="status">
                                <option value="1" {{ $country->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $country->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id='save-btn'></button>
                </div>
            </div>
         </div>
    
        
     </div>
    </form> 
   


